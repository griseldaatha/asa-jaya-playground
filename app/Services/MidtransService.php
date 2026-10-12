<?php

namespace App\Services;

use App\Models\Transaksi;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    /**
     * Cek apakah kredensial Midtrans sudah dikonfigurasi
     */
    public static function isConfigured(): bool
    {
        return !empty(config('midtrans.server_key'));
    }

    /**
     * Base URL Midtrans API
     */
    protected function getBaseUrl(): string
    {
        return rtrim(config('midtrans.base_url', 'https://api.sandbox.midtrans.com'), '/');
    }

    /**
     * Server key Midtrans
     */
    protected function getServerKey(): string
    {
        $key = config('midtrans.server_key');
        if (empty($key)) {
            throw new Exception('QRIS belum dikonfigurasi di sistem.');
        }
        return $key;
    }

    /**
     * Membuat pembayaran QRIS via Midtrans Core API (/v2/charge)
     */
    public function chargeQris(Transaksi $t): array
    {
        $serverKey = $this->getServerKey();
        $baseUrl = $this->getBaseUrl();

        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $t->kode_transaksi,
                'gross_amount' => (int) $t->total_harga,
            ],
            'qris' => [
                'acquirer' => 'gopay',
            ],
            'customer_details' => [
                'first_name' => substr($t->nama_pemesan ?? 'Pelanggan', 0, 50),
            ],
            'custom_expiry' => [
                'expiry_duration' => 15,
                'unit' => 'minute',
            ],
        ];

        // Item details (opsional namun baik jika totalnya cocok persis)
        if ($t->detailTransaksi && $t->detailTransaksi->count() > 0) {
            $itemDetails = [];
            $itemsSum = 0;
            foreach ($t->detailTransaksi as $dt) {
                $nama = substr($dt->produk->nama_produk ?? 'Item', 0, 50);
                $itemDetails[] = [
                    'id' => (string) $dt->produk_id,
                    'price' => (int) $dt->harga_satuan,
                    'quantity' => (int) $dt->jumlah,
                    'name' => $nama,
                ];
                $itemsSum += ((int) $dt->harga_satuan * (int) $dt->jumlah);
            }

            if ($itemsSum === (int) $t->total_harga) {
                $payload['item_details'] = $itemDetails;
            }
        }

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->timeout(15)
                ->post("{$baseUrl}/v2/charge", $payload);
        } catch (\Throwable $e) {
            Log::error('Midtrans Charge Connection Error: ' . $e->getMessage(), [
                'order_id' => $t->kode_transaksi,
            ]);
            throw new Exception('Gagal terhubung ke layanan pembayaran QRIS.');
        }

        $body = $response->json();
        $statusCode = $response->status();

        if (! $response->successful() || ! isset($body['status_code']) || $body['status_code'] !== '201') {
            $msg = $body['status_message'] ?? 'Respons tidak sesuai dari penyedia pembayaran.';
            Log::error('Midtrans Charge Failed', [
                'order_id' => $t->kode_transaksi,
                'status_code' => $statusCode,
                'response' => $body,
            ]);
            throw new Exception('Gagal membuat transaksi QRIS: ' . $msg);
        }

        $transactionId = $body['transaction_id'] ?? null;
        $qrisUrl = null;
        $actionsMap = [];

        // Ambil URL aksi: prioritas 'generate-qr-code-v2', fallback 'generate-qr-code'
        if (isset($body['actions']) && is_array($body['actions'])) {
            foreach ($body['actions'] as $action) {
                if (isset($action['name'], $action['url'])) {
                    $actionsMap[$action['name']] = $action['url'];
                }
            }
        }

        $qrisUrl = $actionsMap['generate-qr-code-v2'] ?? $actionsMap['generate-qr-code'] ?? null;

        // Fallback jika url ada di qr_string / url langsung
        if (!$qrisUrl && isset($body['qr_string'])) {
            $qrisUrl = $body['qr_string'];
        }

        if (!$qrisUrl) {
            Log::error('Midtrans QRIS URL not found in actions', [
                'order_id' => $t->kode_transaksi,
                'response' => $body,
            ]);
            throw new Exception('QR Code tidak ditemukan dari respons penyedia pembayaran.');
        }

        if (strlen($qrisUrl) > 255) {
            Log::warning('QRIS URL length exceeds 255 characters: ' . strlen($qrisUrl), [
                'order_id' => $t->kode_transaksi,
                'url' => $qrisUrl,
            ]);
        }

        // Parse expiry_time (Zona Asia/Jakarta)
        $qrisExpiredAt = null;
        if (!empty($body['expiry_time'])) {
            try {
                $qrisExpiredAt = Carbon::createFromFormat('Y-m-d H:i:s', $body['expiry_time'], 'Asia/Jakarta')
                    ->setTimezone(config('app.timezone', 'UTC'));
            } catch (\Throwable $e) {
                $qrisExpiredAt = now()->addMinutes(15);
            }
        } else {
            $qrisExpiredAt = now()->addMinutes(15);
        }

        return [
            'transaction_id' => $transactionId,
            'qris_url' => $qrisUrl,
            'qris_url_v1' => $actionsMap['generate-qr-code'] ?? null,
            'qris_url_v2' => $actionsMap['generate-qr-code-v2'] ?? null,
            'qris_expired_at' => $qrisExpiredAt,
            'raw_response' => $body,
        ];
    }

    /**
     * Cek status transaksi ke Midtrans GET /v2/{order_id}/status
     */
    public function getStatus(string $orderId): array
    {
        $serverKey = $this->getServerKey();
        $baseUrl = $this->getBaseUrl();

        try {
            $response = Http::withBasicAuth($serverKey, '')
                ->timeout(15)
                ->get("{$baseUrl}/v2/{$orderId}/status");

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::warning('Midtrans getStatus error: ' . $e->getMessage(), [
                'order_id' => $orderId,
            ]);
            return [];
        }
    }

    /**
     * Membatalkan transaksi di Midtrans POST /v2/{order_id}/cancel (Best Effort)
     */
    public function cancel(string $orderId): bool
    {
        try {
            if (!self::isConfigured()) {
                return false;
            }
            $serverKey = $this->getServerKey();
            $baseUrl = $this->getBaseUrl();

            $response = Http::withBasicAuth($serverKey, '')
                ->timeout(15)
                ->post("{$baseUrl}/v2/{$orderId}/cancel");

            Log::info("Midtrans Cancel Request for {$orderId}", [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('Midtrans cancel error (ignored): ' . $e->getMessage(), [
                'order_id' => $orderId,
            ]);
            return false;
        }
    }

    /**
     * Verifikasi signature SHA-512 Midtrans
     */
    public function verifySignature(array $payload): bool
    {
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';
        $serverKey = config('midtrans.server_key');

        if (empty($serverKey) || empty($signatureKey)) {
            return false;
        }

        // Formula: sha512(order_id + status_code + gross_amount + server_key)
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }

    /**
     * Menerapkan status transaksi dari Midtrans secara idempoten
     */
    public function applyStatus(Transaksi $t, string $transactionStatus, ?string $fraudStatus = null): bool
    {
        return DB::transaction(function () use ($t, $transactionStatus, $fraudStatus) {
            $transaksi = Transaksi::lockForUpdate()->with('detailTransaksi.produk')->find($t->id);

            if (!$transaksi || $transaksi->status_pembayaran !== 'pending') {
                return false; // Idempoten: hanya proses jika masih pending
            }

            // Settlement atau capture accept -> Lunas & Diproses
            if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                $transaksi->status_pembayaran = 'lunas';
                $transaksi->status_pesanan = 'diproses';
                $transaksi->save();
                return true;
            }

            // Pending -> tidak berubah
            if ($transactionStatus === 'pending') {
                return true;
            }

            // Expire -> Kadaluarsa & Dibatalkan, kembalikan stok
            if ($transactionStatus === 'expire') {
                $transaksi->status_pembayaran = 'kadaluarsa';
                $transaksi->status_pesanan = 'dibatalkan';
                $transaksi->save();

                foreach ($transaksi->detailTransaksi as $detail) {
                    $produk = $detail->produk;
                    if ($produk) {
                        $produk->stok += $detail->jumlah;
                        $produk->save();
                    }
                }
                return true;
            }

            // Cancel / Deny -> Batal & Dibatalkan, kembalikan stok
            if (in_array($transactionStatus, ['cancel', 'deny'])) {
                $transaksi->status_pembayaran = 'batal';
                $transaksi->status_pesanan = 'dibatalkan';
                $transaksi->save();

                foreach ($transaksi->detailTransaksi as $detail) {
                    $produk = $detail->produk;
                    if ($produk) {
                        $produk->stok += $detail->jumlah;
                        $produk->save();
                    }
                }
                return true;
            }

            return false;
        });
    }
}

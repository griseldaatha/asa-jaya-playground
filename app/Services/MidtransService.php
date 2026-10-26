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
     * Base URL Midtrans Core API (untuk status & cancel)
     */
    protected function getBaseUrl(): string
    {
        return rtrim(config('midtrans.base_url', 'https://api.sandbox.midtrans.com'), '/');
    }

    /**
     * Base URL Midtrans Snap
     */
    protected function getSnapBaseUrl(): string
    {
        return rtrim(config('midtrans.snap_base_url', 'https://app.sandbox.midtrans.com'), '/');
    }

    /**
     * Server key Midtrans
     */
    protected function getServerKey(): string
    {
        $key = config('midtrans.server_key');
        if (empty($key)) {
            throw new Exception('Midtrans belum dikonfigurasi di sistem.');
        }
        return $key;
    }

    /**
     * Membangun HTTP Client dengan konfigurasi SSL yang aman untuk Windows/Laragon
     */
    protected function buildHttpClient(): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::withBasicAuth($this->getServerKey(), '')
            ->timeout(15);

        if (file_exists('C:/laragon/etc/ssl/cacert.pem')) {
            $client = $client->withOptions(['verify' => 'C:/laragon/etc/ssl/cacert.pem']);
        } elseif (config('app.env') === 'local') {
            $client = $client->withOptions(['verify' => false]);
        }

        return $client;
    }

    /**
     * Membuat transaksi Snap Midtrans untuk channel terpilih
     *
     * @param Transaksi $t
     * @param string $channel bca_va, bni_va, bri_va, gopay, shopeepay
     * @return array
     * @throws Exception
     */
    public function createSnapTransaction(Transaksi $t, string $channel): array
    {
        $validChannels = ['bca_va', 'bni_va', 'bri_va', 'gopay', 'shopeepay'];
        $channel = strtolower(trim($channel));
        if (!in_array($channel, $validChannels, true)) {
            throw new Exception("Metode pembayaran non-tunai '{$channel}' tidak valid.");
        }

        $snapBase = $this->getSnapBaseUrl();
        $durationMinutes = (int) config('midtrans.payment_expiry_minutes', 60);

        $finishUrl = route('status.pesanan', [
            'kode_transaksi' => $t->kode_transaksi,
            'token' => $t->access_token,
        ]);

        $payload = [
            'transaction_details' => [
                'order_id' => $t->kode_transaksi,
                'gross_amount' => (int) $t->total_harga,
            ],
            'customer_details' => [
                'first_name' => substr($t->nama_pemesan ?? 'Pelanggan', 0, 50),
            ],
            'enabled_payments' => [$channel],
            'expiry' => [
                'unit' => 'minutes',
                'duration' => $durationMinutes,
            ],
            'callbacks' => [
                'finish' => $finishUrl,
            ],
        ];

        try {
            $response = $this->buildHttpClient()
                ->post("{$snapBase}/snap/v1/transactions", $payload);
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap Connection Error: ' . $e->getMessage(), [
                'order_id' => $t->kode_transaksi,
                'channel' => $channel,
            ]);
            throw new Exception('Gagal terhubung ke layanan pembayaran Snap Midtrans.');
        }

        $body = $response->json();
        $statusCode = $response->status();

        if (!$response->successful() || empty($body['redirect_url'])) {
            Log::error('Midtrans Snap Transaction Failed', [
                'order_id' => $t->kode_transaksi,
                'channel' => $channel,
                'status_code' => $statusCode,
                'response' => $body,
            ]);
            $msg = $body['error_messages'][0] ?? ($body['status_message'] ?? 'Tidak ada tautan pembayaran dari penyedia.');
            throw new Exception('Gagal membuat transaksi pembayaran: ' . $msg);
        }

        $redirectUrl = $body['redirect_url'];
        $expiredAt = now()->addMinutes($durationMinutes);

        $t->payment_url = $redirectUrl;
        $t->payment_channel = $channel;
        $t->payment_expired_at = $expiredAt;
        $t->save();

        return [
            'redirect_url' => $redirectUrl,
            'payment_channel' => $channel,
            'payment_expired_at' => $expiredAt,
            'token' => $body['token'] ?? null,
            'raw_response' => $body,
        ];
    }

    /**
     * Cek status transaksi ke Midtrans GET /v2/{order_id}/status
     * Catatan: Sebelum customer memilih/membayar di Snap, Midtrans mengembalikan 404.
     * Perlakukan 404 sebagai "belum ada pembayaran", bukan error dan tidak mengubah status.
     */
    public function getStatus(string $orderId): array
    {
        $baseUrl = $this->getBaseUrl();

        try {
            $response = $this->buildHttpClient()
                ->get("{$baseUrl}/v2/{$orderId}/status");

            if ($response->status() === 404) {
                return [
                    'status_code' => '404',
                    'transaction_status' => null,
                ];
            }

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
     * Error 404 diabaikan karena customer mungkin belum menyelesaikan alur di Midtrans.
     */
    public function cancel(string $orderId): bool
    {
        try {
            if (!self::isConfigured()) {
                return false;
            }
            $baseUrl = $this->getBaseUrl();

            $response = $this->buildHttpClient()
                ->post("{$baseUrl}/v2/{$orderId}/cancel");

            if ($response->status() === 404) {
                return true;
            }

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
    public function applyStatus(Transaksi $t, string $transactionStatus, ?string $fraudStatus = null, array $payload = []): bool
    {
        return DB::transaction(function () use ($t, $transactionStatus, $fraudStatus, $payload) {
            $transaksi = Transaksi::lockForUpdate()->with('detailTransaksi.produk')->find($t->id);

            if (!$transaksi || $transaksi->status_pembayaran !== 'pending') {
                return false; // Idempoten: hanya proses jika masih pending
            }

            // Simpan midtrans_transaction_id jika ada dalam payload
            if (!empty($payload['transaction_id'])) {
                $transaksi->midtrans_transaction_id = $payload['transaction_id'];
            }

            // Simpan payment_channel dari payload jika terdeteksi
            if (!empty($payload['payment_type'])) {
                $pt = strtolower($payload['payment_type']);
                if ($pt === 'bank_transfer') {
                    if (!empty($payload['va_numbers'][0]['bank'])) {
                        $transaksi->payment_channel = strtolower($payload['va_numbers'][0]['bank']) . '_va';
                    }
                } elseif (in_array($pt, ['gopay', 'shopeepay'], true)) {
                    $transaksi->payment_channel = $pt;
                }
            }

            // Settlement atau capture accept -> Lunas & Diproses
            if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
                $transaksi->status_pembayaran = 'lunas';
                $transaksi->status_pesanan = 'diproses';
                $transaksi->save();
                return true;
            }

            // Pending -> tetap simpan perubahan id/channel jika ada
            if ($transactionStatus === 'pending') {
                $transaksi->save();
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

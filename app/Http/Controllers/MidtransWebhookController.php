<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    /**
     * Menangani notifikasi webhook dari Midtrans
     */
    public function handle(Request $request, ?MidtransService $midtrans = null)
    {
        $midtrans = $midtrans ?? app(MidtransService::class);
        $payload = $request->all();

        // 1. Verifikasi Signature Midtrans (gagal = 403)
        if (! $midtrans->verifySignature($payload)) {
            Log::warning('Midtrans Webhook: Signature tidak valid', [
                'order_id' => $payload['order_id'] ?? null,
                'ip' => $request->ip(),
            ]);
            abort(403, 'Invalid Midtrans signature');
        }

        $orderId = $payload['order_id'] ?? '';
        $grossAmountPayload = (int) ($payload['gross_amount'] ?? 0);
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? null;

        // 2. Cari transaksi berdasarkan order_id (= kode_transaksi)
        $transaksi = Transaksi::where('kode_transaksi', $orderId)->first();
        if (! $transaksi) {
            Log::warning("Midtrans Webhook: Transaksi tidak ditemukan untuk order_id {$orderId}");
            abort(404, 'Transaksi tidak ditemukan');
        }

        // 3. Pastikan gross_amount cocok dengan total_harga
        if ($grossAmountPayload !== (int) $transaksi->total_harga) {
            Log::warning("Midtrans Webhook: Gross amount tidak cocok untuk {$orderId}", [
                'payload_gross' => $grossAmountPayload,
                'db_total' => $transaksi->total_harga,
            ]);
            abort(400, 'Gross amount mismatch');
        }

        // 4. Terapkan status transaksi secara idempoten
        $midtrans->applyStatus($transaksi, $transactionStatus, $fraudStatus);

        // 5. Catat log ringkas tanpa data sensitif
        Log::info("Midtrans Webhook berhasil diproses: Order [{$orderId}], Status: [{$transactionStatus}], Waktu: " . now()->toDateTimeString());

        // 6. Balas HTTP 200
        return response()->json([
            'success' => true,
            'message' => 'Notification processed successfully',
        ], 200);
    }
}

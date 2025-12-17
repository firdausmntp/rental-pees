<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PakasirWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        if (! config('services.pakasir.enabled')) {
            return response()->json(['message' => 'Pakasir gateway disabled'], 503);
        }

        $payload = $request->all();
        Log::info('Pakasir webhook received', ['payload' => $payload]);

        if (($payload['project'] ?? null) !== config('services.pakasir.project_slug')) {
            return response()->json(['message' => 'Project mismatch'], 403);
        }

        $voucher = Voucher::where('payment_reference', $payload['order_id'] ?? null)->first();

        if (! $voucher) {
            Log::warning('Pakasir webhook voucher not found', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'Voucher not found'], 404);
        }

        if (isset($payload['amount']) && $this->normalizeAmount($voucher->total_harga) !== (int) $payload['amount']) {
            Log::warning('Pakasir amount mismatch', [
                'order_id' => $voucher->payment_reference,
                'expected' => $this->normalizeAmount($voucher->total_harga),
                'received' => $payload['amount'],
            ]);

            return response()->json(['message' => 'Invalid amount'], 422);
        }

        $status = $payload['status'] ?? null;

        if ($status === 'completed') {
            $this->markVoucherPaid($voucher, $payload['payment_method'] ?? null);
        } elseif ($status === 'pending') {
            $voucher->update(['status_pembayaran' => 'pending']);
        } elseif (in_array($status, ['failed', 'expired', 'cancelled'], true)) {
            $voucher->update([
                'status_pembayaran' => 'failed',
                'status' => 'expired',
            ]);
        }

        return response()->json(['message' => 'Webhook processed']);
    }

    protected function markVoucherPaid(Voucher $voucher, ?string $method = null): void
    {
        if (! $voucher->kode_voucher) {
            $voucher->kode_voucher = Voucher::generateKodeVoucher();
        }

        $voucher->status = 'aktif';
        $voucher->status_pembayaran = 'paid';
        $voucher->payment_gateway = 'pakasir';
        $voucher->metode_pembayaran = 'pakasir';
        $voucher->save();

        Log::info('Voucher marked as paid via Pakasir', [
            'voucher_id' => $voucher->id,
            'payment_method' => $method,
        ]);
    }

    protected function normalizeAmount(float|int|string $value): int
    {
        return (int) round((float) $value);
    }
}

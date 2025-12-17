<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        if (! config('services.midtrans.enabled')) {
            return response()->json(['message' => 'Midtrans gateway disabled'], 503);
        }

        // Set konfigurasi midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Cari voucher berdasarkan order_id (payment_reference)
        $voucher = Voucher::where('payment_reference', $orderId)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $voucher->update(['status_pembayaran' => 'pending']);
                } else {
                    $this->setVoucherPaid($voucher);
                }
            }
        } else if ($transaction == 'settlement') {
            $this->setVoucherPaid($voucher);
        } else if ($transaction == 'pending') {
            $voucher->update(['status_pembayaran' => 'pending']);
        } else if ($transaction == 'deny') {
            $voucher->update(['status_pembayaran' => 'failed', 'status' => 'expired']);
        } else if ($transaction == 'expire') {
            $voucher->update(['status_pembayaran' => 'failed', 'status' => 'expired']);
        } else if ($transaction == 'cancel') {
            $voucher->update(['status_pembayaran' => 'failed', 'status' => 'expired']);
        }

        return response()->json(['message' => 'Notification handled']);
    }

    private function setVoucherPaid($voucher)
    {
        // Jika sudah paid, jangan update lagi
        if ($voucher->status_pembayaran === 'paid') {
            return;
        }

        // Generate kode voucher jika belum ada
        if (!$voucher->kode_voucher) {
            $voucher->kode_voucher = Voucher::generateKodeVoucher();
        }

        $voucher->update([
            'status_pembayaran' => 'paid',
            'status' => 'aktif',
            'payment_gateway' => 'midtrans',
        ]);
    }

    public function handleCallback(Request $request)
    {
        if (! config('services.midtrans.enabled')) {
            return redirect()->route('member.dashboard')->with('error', 'Midtrans tidak aktif');
        }

        $orderId = $request->query('order_id');
        $statusCode = $request->query('status_code');
        $transactionStatus = $request->query('transaction_status');

        if (! $orderId) {
            return redirect()->route('member.dashboard')->with('error', 'Order ID tidak ditemukan');
        }

        // Cari voucher berdasarkan order_id
        $voucher = Voucher::where('payment_reference', $orderId)
            ->where('member_id', auth()->id())
            ->first();

        if (! $voucher) {
            return redirect()->route('member.dashboard')->with('error', 'Voucher tidak ditemukan');
        }

        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            // Cek status terbaru dari Midtrans API
            $status = \Midtrans\Transaction::status($orderId);
            $apiTransactionStatus = $status->transaction_status;

            if ($apiTransactionStatus === 'settlement' || $apiTransactionStatus === 'capture') {
                $this->setVoucherPaid($voucher);
                return redirect()->route('member.dashboard')->with('success', 'Pembayaran berhasil! Voucher sudah aktif.');
            }

            if ($apiTransactionStatus === 'pending') {
                return redirect()->route('member.dashboard')->with('info', 'Pembayaran masih diproses, mohon tunggu.');
            }

            if (in_array($apiTransactionStatus, ['deny', 'expire', 'cancel'], true)) {
                $voucher->update([
                    'status_pembayaran' => 'failed',
                    'status' => 'expired',
                ]);
                return redirect()->route('member.dashboard')->with('error', 'Pembayaran gagal atau dibatalkan.');
            }

            return redirect()->route('member.dashboard')->with('info', 'Status: ' . $apiTransactionStatus);
        } catch (\Exception $e) {
            \Log::error('Midtrans callback error', [
                'message' => $e->getMessage(),
                'order_id' => $orderId,
            ]);

            return redirect()->route('member.dashboard')->with('error', 'Tidak dapat memverifikasi status pembayaran.');
        }
    }
}

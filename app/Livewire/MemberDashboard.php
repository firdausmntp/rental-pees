<?php

namespace App\Livewire;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Services\PakasirGateway;

class MemberDashboard extends Component
{
    use WithPagination, WithFileUploads;

    public $filterStatus = 'all';
    
    public $uploadingVoucherId = null;
    public $buktiPembayaran = null;
    public $cancelVoucherId = null; // Untuk modal cancel
    // public $snapToken = null; // No longer needed for redirect

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        // Base query
        $query = Voucher::where('member_id', auth()->id())->latest();

        // Apply filter
        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'aktif') {
                $query->where('status', 'aktif')->where('status_pembayaran', 'paid');
            } elseif ($this->filterStatus === 'pending') {
                $query->where('status_pembayaran', 'pending');
            } else {
                $query->where('status', $this->filterStatus);
            }
        } else {
            // "Semua" tampilkan semua status (aktif, pending, expired, dll)
            // Urutkan: Pending paling atas, lalu Aktif, lalu sisanya
            $query->orderByRaw("CASE 
                WHEN status_pembayaran = 'pending' THEN 1 
                WHEN status = 'aktif' THEN 2 
                ELSE 3 
            END");
        }

        // Get vouchers with pagination
        $vouchers = $query->paginate(9);

        // Calculate stats
        $activeVouchersCount = Voucher::where('member_id', auth()->id())
            ->where('status', 'aktif')
            ->where('status_pembayaran', 'paid')
            ->count();

        $pendingVouchersCount = Voucher::where('member_id', auth()->id())
            ->where('status_pembayaran', 'pending')
            ->count();

        $totalVouchersCount = Voucher::where('member_id', auth()->id())->count();

        $totalSpent = Voucher::where('member_id', auth()->id())
            ->where('status_pembayaran', 'paid')
            ->sum('total_harga');

        return view('livewire.member-dashboard', [
            'vouchers' => $vouchers,
            'activeVouchersCount' => $activeVouchersCount,
            'pendingVouchersCount' => $pendingVouchersCount,
            'totalVouchersCount' => $totalVouchersCount,
            'totalSpent' => $totalSpent,
        ])->layout('layouts.app');
    }

    // Reset pagination when filter changes
    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function openUploadModal($voucherId)
    {
        $this->uploadingVoucherId = $voucherId;
        $this->buktiPembayaran = null;
    }

    public function uploadBuktiPembayaran()
    {
        $this->validate([
            'buktiPembayaran' => 'required|image|max:2048', // Max 2MB
        ]);

        $voucher = Voucher::where('id', $this->uploadingVoucherId)
            ->where('member_id', auth()->id())
            ->first();

        if (!$voucher) {
            session()->flash('error', 'Voucher tidak ditemukan');
            return;
        }

        if ($voucher->status_pembayaran !== 'pending') {
            session()->flash('error', 'Voucher ini tidak dalam status pending');
            return;
        }

        // Delete old image if exists
        if ($voucher->qris_image && \Storage::exists('public/' . $voucher->qris_image)) {
            \Storage::delete('public/' . $voucher->qris_image);
        }

        // Store new image
        $path = $this->buktiPembayaran->store('bukti-pembayaran', 'public');

        // Update voucher
        $voucher->update([
            'qris_image' => $path,
        ]);

        // Reset
        $this->uploadingVoucherId = null;
        $this->buktiPembayaran = null;

        session()->flash('success', 'Bukti pembayaran berhasil diupload! Menunggu approval dari admin.');
        
        // Dispatch browser event to close modal
        $this->dispatch('close-upload-modal');
    }

    public function closeUploadModal()
    {
        $this->uploadingVoucherId = null;
        $this->buktiPembayaran = null;
    }

    public function confirmCancel($id)
    {
        $this->cancelVoucherId = $id;
    }

    public function closeCancelModal()
    {
        $this->cancelVoucherId = null;
    }

    public function cancelTransaction()
    {
        if (!$this->cancelVoucherId) return;

        $voucher = Voucher::where('id', $this->cancelVoucherId)
            ->where('member_id', auth()->id())
            ->first();

        if (!$voucher || $voucher->status_pembayaran !== 'pending') {
            session()->flash('error', 'Voucher tidak dapat dibatalkan.');
            $this->closeCancelModal();
            return;
        }

        $voucher->update([
            'status' => 'cancelled',
            'status_pembayaran' => 'cancelled'
        ]);

        session()->flash('success', 'Transaksi berhasil dibatalkan.');
        $this->closeCancelModal();
    }

    // QRIS Logic
    public $showQrisModal = false;
    public $qrisData = null;
    public $previewQrisNominal = null;

    public function showQris($voucherId)
    {
        $voucher = Voucher::where('id', $voucherId)
            ->where('member_id', auth()->id())
            ->first();

        if (!$voucher || $voucher->metode_pembayaran !== 'qris') {
            return;
        }

        $this->previewQrisNominal = $voucher->qris_nominal ?? $voucher->total_harga;
        $this->qrisData = $this->generateQrisString($this->previewQrisNominal);
        $this->showQrisModal = true;
    }

    public function closeQrisModal()
    {
        $this->showQrisModal = false;
        $this->qrisData = null;
        $this->previewQrisNominal = null;
    }

    private function generateQrisString($amount)
    {
        // Base QRIS string from user (GoPay/GoJek)
        $baseQris = "00020101021126610014COM.GO-JEK.WWW01189360091430404966290210G0404966290303UMI51440014ID.CO.QRIS.WWW0215ID10243614510490303UMI5204549953033605802ID5919JSK STORE, SNDNG JY6009TANGERANG61051556062070703A01";
        
        $step1 = str_replace("010211", "010212", $baseQris);
        $parts = explode("5802ID", $step1);
        
        $amountStr = (string) $amount;
        $amountTag = "54" . str_pad(strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
        
        $fix = $parts[0] . $amountTag . "5802ID" . $parts[1] . "6304";
        $crc = $this->crc16($fix);
        
        return $fix . $crc;
    }

    private function crc16($str)
    {
        $crc = 0xFFFF;
        $strlen = strlen($str);
        
        for($c = 0; $c < $strlen; $c++) {
            $crc ^= ord($str[$c]) << 8;
            for($i = 0; $i < 8; $i++) {
                if($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
            }
        }
        
        $hex = $crc & 0xFFFF;
        $hex = strtoupper(dechex($hex));
        return str_pad($hex, 4, '0', STR_PAD_LEFT);
    }

    public function payPakasir($voucherId)
    {
        if (! config('services.pakasir.enabled')) {
            session()->flash('error', 'Pembayaran otomatis sedang tidak tersedia.');
            return;
        }

        $voucher = Voucher::where('id', $voucherId)
            ->where('member_id', auth()->id())
            ->first();

        if (! $voucher || $voucher->status_pembayaran !== 'pending') {
            session()->flash('error', 'Voucher tidak valid untuk pembayaran.');
            return;
        }

        try {
            $gateway = app(PakasirGateway::class);
            $url = $gateway->buildPaymentUrl($voucher, [
                'redirect' => route('member.dashboard'),
            ]);
        } catch (\Throwable $throwable) {
            \Log::error('Failed to start Pakasir payment', [
                'message' => $throwable->getMessage(),
                'voucher_id' => $voucher->id,
            ]);

            session()->flash('error', 'Gagal menyiapkan pembayaran Pakasir, silakan kontak admin.');
            return;
        }

        return redirect()->away($url);
    }

    public function checkPakasirStatus($voucherId)
    {
        $voucher = Voucher::where('id', $voucherId)
            ->where('member_id', auth()->id())
            ->first();

        if (! $voucher || $voucher->metode_pembayaran !== 'pakasir' || ! $voucher->payment_reference) {
            session()->flash('error', 'Voucher tidak valid untuk pengecekan status.');
            return;
        }

        try {
            $gateway = app(PakasirGateway::class);
            $transaction = $gateway->fetchTransactionStatus($voucher);
        } catch (\Throwable $throwable) {
            \Log::error('Failed to check Pakasir status', [
                'message' => $throwable->getMessage(),
                'voucher_id' => $voucher->id,
            ]);

            session()->flash('error', 'Tidak dapat mengecek status pembayaran saat ini.');
            return;
        }

        if (! $transaction) {
            session()->flash('error', 'Status pembayaran belum tersedia, coba lagi nanti.');
            return;
        }

        $status = $transaction['status'] ?? null;

        if ($status === 'completed') {
            $this->markVoucherPaid($voucher);
            session()->flash('success', 'Pembayaran Pakasir terkonfirmasi! Voucher sudah aktif.');
            return;
        }

        if ($status === 'pending') {
            session()->flash('info', 'Pembayaran masih menunggu konfirmasi dari Pakasir.');
            return;
        }

        if (in_array($status, ['failed', 'expired', 'cancelled'], true)) {
            $voucher->update([
                'status_pembayaran' => 'failed',
                'status' => 'expired',
            ]);

            session()->flash('error', 'Pembayaran Pakasir gagal atau kedaluwarsa.');
            return;
        }

        session()->flash('info', 'Status pembayaran terakhir: ' . $status);
    }

    private function markVoucherPaid(Voucher $voucher): void
    {
        if ($voucher->status_pembayaran === 'paid') {
            return;
        }

        if (! $voucher->kode_voucher) {
            $voucher->kode_voucher = Voucher::generateKodeVoucher();
        }

        $voucher->update([
            'status_pembayaran' => 'paid',
            'status' => 'aktif',
            'payment_gateway' => 'pakasir',
        ]);
    }

    public function payMidtrans($voucherId)
    {
        if (! config('services.midtrans.enabled')) {
            session()->flash('error', 'Pembayaran Midtrans belum diaktifkan.');
            return;
        }

        $voucher = Voucher::where('id', $voucherId)
            ->where('member_id', auth()->id())
            ->first();

        if (! $voucher || $voucher->status_pembayaran !== 'pending' || ! $voucher->snap_token) {
            session()->flash('error', 'Voucher tidak valid untuk pembayaran.');
            return;
        }

        // Redirect to Snap payment page using redirect_url
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            $snapUrl = \Midtrans\Snap::createTransaction([
                'transaction_details' => [
                    'order_id' => $voucher->payment_reference,
                    'gross_amount' => (int) $voucher->total_harga,
                ],
            ])->redirect_url;

            return redirect()->away($snapUrl);
        } catch (\Exception $e) {
            \Log::error('Failed to redirect to Midtrans', [
                'message' => $e->getMessage(),
                'voucher_id' => $voucher->id,
            ]);

            session()->flash('error', 'Gagal membuka halaman pembayaran Midtrans.');
            return;
        }
    }

    public function checkMidtransStatus($voucherId)
    {
        if (! config('services.midtrans.enabled')) {
            session()->flash('error', 'Pembayaran Midtrans belum diaktifkan.');
            return;
        }

        $voucher = Voucher::where('id', $voucherId)
            ->where('member_id', auth()->id())
            ->first();

        if (! $voucher || $voucher->metode_pembayaran !== 'midtrans' || ! $voucher->payment_reference) {
            session()->flash('error', 'Voucher tidak valid untuk pengecekan status.');
            return;
        }

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

        try {
            $status = \Midtrans\Transaction::status($voucher->payment_reference);
            $transactionStatus = $status->transaction_status;

            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                if (! $voucher->kode_voucher) {
                    $voucher->kode_voucher = Voucher::generateKodeVoucher();
                }

                $voucher->update([
                    'status_pembayaran' => 'paid',
                    'status' => 'aktif',
                    'payment_gateway' => 'midtrans',
                ]);

                session()->flash('success', 'Pembayaran Midtrans terkonfirmasi! Voucher sudah aktif.');
                return;
            }

            if ($transactionStatus === 'pending') {
                session()->flash('info', 'Pembayaran masih menunggu konfirmasi dari Midtrans.');
                return;
            }

            if (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
                $voucher->update([
                    'status_pembayaran' => 'failed',
                    'status' => 'expired',
                ]);

                session()->flash('error', 'Pembayaran Midtrans gagal atau kedaluwarsa.');
                return;
            }

            session()->flash('info', 'Status pembayaran: ' . $transactionStatus);
        } catch (\Exception $e) {
            \Log::error('Failed to check Midtrans status', [
                'message' => $e->getMessage(),
                'voucher_id' => $voucher->id,
            ]);

            session()->flash('error', 'Tidak dapat mengecek status pembayaran saat ini.');
        }

        // Redirect to clean URL
        return redirect()->route('member.dashboard');
    }
}


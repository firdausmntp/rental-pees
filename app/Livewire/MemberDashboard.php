<?php

namespace App\Livewire;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class MemberDashboard extends Component
{
    use WithPagination, WithFileUploads;

    public $filterStatus = 'all';
    
    public $uploadingVoucherId = null;
    public $buktiPembayaran = null;

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
            // "Semua" hanya tampilkan yang sudah paid (approved)
            // Pending hanya muncul kalau user klik tab "Pending"
            $query->where('status_pembayaran', 'paid');
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

    public function cancelUpload()
    {
        $this->uploadingVoucherId = null;
        $this->buktiPembayaran = null;
    }
}


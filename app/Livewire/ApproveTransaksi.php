<?php

namespace App\Livewire;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ApproveTransaksi extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'pending';

    public function render()
    {
        $vouchers = Voucher::with(['member', 'tarif', 'approvedBy'])
            ->when($this->search, function($query) {
                $query->where('kode_voucher', 'like', '%' . $this->search . '%')
                    ->orWhereHas('member', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filter, function($query) {
                if ($this->filter !== 'all') {
                    $query->where('status_pembayaran', $this->filter);
                }
            })
            ->latest()
            ->paginate(10);

        return view('livewire.approve-transaksi', [
            'vouchers' => $vouchers,
        ])->layout('layouts.app');
    }

    public function approveVoucher($id)
    {
        $voucher = Voucher::find($id);
        
        if (!$voucher || $voucher->status_pembayaran !== 'pending') {
            session()->flash('error', 'Voucher tidak dapat diapprove');
            return;
        }

        $voucher->update([
            'status_pembayaran' => 'paid',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        session()->flash('message', 'Voucher berhasil diapprove! Kode: ' . $voucher->kode_voucher);
        
        $this->dispatch('voucher-approved');
    }

    public function rejectVoucher($id)
    {
        $voucher = Voucher::find($id);
        
        if (!$voucher || $voucher->status_pembayaran !== 'pending') {
            session()->flash('error', 'Voucher tidak dapat ditolak');
            return;
        }

        $voucher->update([
            'status_pembayaran' => 'cancelled',
            'status' => 'expired',
        ]);

        session()->flash('message', 'Voucher ditolak');
    }

    #[On('echo:vouchers,VoucherCreated')]
    public function onVoucherCreated()
    {
        // Auto refresh when new voucher created
        $this->render();
    }
}


<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class TransaksiKembali extends Component
{
    use WithPagination;

    public $transaksi_id;
    public $waktu_selesai;
    public $search = '';

    public function prosesPengembalian($id)
    {
        $this->transaksi_id = $id;
        $this->waktu_selesai = now()->format('Y-m-d\TH:i');
    }

    public function selesaikanTransaksi()
    {
        $this->validate([
            'waktu_selesai' => 'required|date',
        ]);

        $transaksi = Transaksi::with('playStation')->find($this->transaksi_id);
        
        $transaksi->waktu_selesai = Carbon::parse($this->waktu_selesai);
        $transaksi->hitungTotalAkhir();
        $transaksi->status = 'selesai';
        $transaksi->save();

        // Update status PS jadi tersedia
        $transaksi->playStation->update(['status' => 'tersedia']);

        // Tambah poin member jika ada
        if ($transaksi->pelanggan->isMemberActive()) {
            $poin = floor($transaksi->total_biaya / 10000); // 1 poin per 10rb
            $transaksi->pelanggan->member->tambahPoin($poin);
        }

        session()->flash('message', 'Transaksi selesai! Total: Rp ' . number_format($transaksi->total_biaya, 0, ',', '.'));
        $this->reset(['transaksi_id', 'waktu_selesai']);
    }

    public function render()
    {
        $query = Transaksi::with(['pelanggan', 'playStation', 'user'])
            ->berlangsung();

        if ($this->search) {
            $query->where('kode_transaksi', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pelanggan', function($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  });
        }

        return view('livewire.transaksi-kembali', [
            'transaksis' => $query->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}

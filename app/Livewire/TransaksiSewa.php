<?php

namespace App\Livewire;

use App\Models\Pelanggan;
use App\Models\PlayStation;
use App\Models\Transaksi;
use App\Models\Tarif;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class TransaksiSewa extends Component
{
    use WithPagination;

    public $pelanggan_id, $play_station_id, $durasi_jam;
    public $search_pelanggan = '';
    public $show_transaksi_form = false;

    protected $rules = [
        'pelanggan_id' => 'required|exists:pelanggans,id',
        'play_station_id' => 'required|exists:play_stations,id',
        'durasi_jam' => 'required|integer|min:1',
    ];

    public function mulaiTransaksi()
    {
        $this->show_transaksi_form = true;
    }

    public function store()
    {
        $this->validate();

        $pelanggan = Pelanggan::find($this->pelanggan_id);
        $playStation = PlayStation::find($this->play_station_id);
        $tarif = Tarif::getTarifByTipe($playStation->tipe);

        // Cek apakah pelanggan member
        $diskonPersen = $pelanggan->getDiskonMember();

        // Buat transaksi
        $transaksi = new Transaksi();
        $transaksi->kode_transaksi = Transaksi::generateKodeTransaksi();
        $transaksi->pelanggan_id = $this->pelanggan_id;
        $transaksi->play_station_id = $this->play_station_id;
        $transaksi->user_id = auth()->id();
        $transaksi->waktu_mulai = now();
        $transaksi->durasi_jam = $this->durasi_jam;
        $transaksi->tarif_per_jam = $tarif->harga_per_jam;
        $transaksi->diskon_persen = $diskonPersen;
        $transaksi->status = 'berlangsung';
        
        // Hitung total
        $transaksi->hitungTotalBiayaAwal();
        $transaksi->save();

        // Update status PS
        $playStation->update(['status' => 'dipakai']);

        session()->flash('message', 'Transaksi berhasil dibuat! Kode: ' . $transaksi->kode_transaksi);
        $this->reset(['pelanggan_id', 'play_station_id', 'durasi_jam', 'show_transaksi_form']);
    }

    public function render()
    {
        $pelanggans = Pelanggan::when($this->search_pelanggan, function($q) {
            $q->where('nama', 'like', '%' . $this->search_pelanggan . '%')
              ->orWhere('nomor_hp', 'like', '%' . $this->search_pelanggan . '%');
        })->get();

        $playStations = PlayStation::tersedia()->with('tarif')->get();
        
        $transaksiBerlangsung = Transaksi::with(['pelanggan', 'playStation', 'user'])
            ->berlangsung()
            ->latest()
            ->paginate(10);

        return view('livewire.transaksi-sewa', [
            'pelanggans' => $pelanggans,
            'playStations' => $playStations,
            'transaksiBerlangsung' => $transaksiBerlangsung,
        ])->layout('layouts.app');
    }
}

<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Models\PlayStation;
use App\Models\Transaksi;
use App\Models\Pelanggan;
use Livewire\Component;

class VoucherRedeem extends Component
{
    public $kode_voucher = '';
    public $voucher = null;
    public $playstation_id = '';
    public $showRedeemForm = false;

    protected $rules = [
        'playstation_id' => 'required|exists:play_stations,id',
    ];

    public function render()
    {
        // Filter PlayStation berdasarkan tipe dari tarif voucher (jika ada)
        $playstations = PlayStation::where('status', 'tersedia');
        
        // Jika voucher sudah di-check dan punya tarif, filter berdasarkan tipe PS
        if ($this->voucher && $this->voucher->tarif) {
            $playstations->where('tipe', $this->voucher->tarif->tipe_ps);
        }
        
        $playstations = $playstations->get();
        
        return view('livewire.voucher-redeem', [
            'playstations' => $playstations,
        ])->layout('layouts.app');
    }

    public function checkVoucher()
    {
        $this->voucher = null;
        $this->showRedeemForm = false;

        if (empty($this->kode_voucher)) {
            session()->flash('error', 'Masukkan kode voucher terlebih dahulu');
            return;
        }

        $voucher = Voucher::with(['member', 'tarif'])
            ->where('kode_voucher', $this->kode_voucher)
            ->first();

        if (!$voucher) {
            session()->flash('error', 'Voucher tidak ditemukan');
            return;
        }

        if ($voucher->status_pembayaran === 'pending') {
            session()->flash('error', 'Voucher masih pending pembayaran. Menunggu approval dari owner/karyawan.');
            return;
        }

        if ($voucher->status_pembayaran === 'failed') {
            session()->flash('error', 'Voucher pembayaran ditolak. Silakan hubungi admin.');
            return;
        }

        if ($voucher->status === 'terpakai') {
            session()->flash('error', 'Voucher sudah terpakai pada ' . $voucher->tanggal_pakai->format('d/m/Y H:i'));
            return;
        }

        if ($voucher->status === 'expired' || $voucher->isExpired()) {
            session()->flash('error', 'Voucher sudah expired pada ' . $voucher->expired_at->format('d/m/Y'));
            return;
        }

        $this->voucher = $voucher;
        $this->showRedeemForm = true;
        session()->flash('success', 'Voucher ditemukan! Silakan pilih PlayStation dan redeem.');
    }

    public function redeem()
    {
        $this->validate();

        if (!$this->voucher || !$this->voucher->canBeUsed()) {
            session()->flash('error', 'Voucher tidak valid atau sudah tidak bisa digunakan');
            return;
        }

        $ps = PlayStation::find($this->playstation_id);
        
        if ($ps->status !== 'tersedia') {
            session()->flash('error', 'PlayStation tidak tersedia');
            return;
        }

        // Hitung waktu dengan +5 menit toleransi
        $durasiJam = $this->voucher->durasi_jam;
        $durasiMenit = ($durasiJam * 60) + 5; // +5 menit toleransi setup

        // Cari atau buat data pelanggan untuk member ini
        $member = $this->voucher->member;
        $pelanggan = Pelanggan::firstOrCreate(
            ['nomor_hp' => $member->email], // Gunakan email sebagai unique identifier
            [
                'nama' => $member->name,
                'nomor_hp' => $member->email,
                'alamat' => '-',
                'is_member' => true,
            ]
        );

        // Buat transaksi dengan snapshot harga dari voucher
        $transaksi = Transaksi::create([
            'kode_transaksi' => 'TRX-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'play_station_id' => $this->playstation_id,
            'pelanggan_id' => $pelanggan->id,
            'user_id' => auth()->id(), // karyawan yang redeem
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addMinutes($durasiMenit),
            'durasi_jam' => $durasiJam,
            'tarif_per_jam' => $this->voucher->harga_per_jam, // Snapshot dari voucher
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total_biaya' => 0, // sudah dibayar saat beli voucher
            'status' => 'berlangsung',
            'keterangan' => 'Redeem voucher: ' . $this->voucher->kode_voucher,
        ]);

        // Update voucher
        $this->voucher->update([
            'status' => 'terpakai',
            'transaksi_id' => $transaksi->id,
            'tanggal_pakai' => now(),
        ]);

        // Update PS status - set to "dipakai" not "digunakan"
        $ps->update(['status' => 'dipakai']);

        $waktuSelesai = now()->addMinutes($durasiMenit)->format('H:i');

        session()->flash('message', 'Voucher berhasil diredeem! PlayStation ' . $ps->kode_ps . ' aktif hingga ' . $waktuSelesai . ' (' . $durasiJam . ' jam 5 menit).');
        
        $this->reset(['kode_voucher', 'voucher', 'playstation_id', 'showRedeemForm']);
    }

    public function resetForm()
    {
        $this->reset(['kode_voucher', 'voucher', 'playstation_id', 'showRedeemForm']);
    }
}


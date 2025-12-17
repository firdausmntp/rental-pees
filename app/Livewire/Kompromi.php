<?php

namespace App\Livewire;

use App\Models\Transaksi;
use App\Models\Tarif;
use App\Models\Voucher;
use Carbon\Carbon;
use Livewire\Component;

class Kompromi extends Component
{
    public $successMessage = '';
    public $errorMessage = '';
    public $customMinutes = []; // Array untuk store custom menit per transaksi

    public function render()
    {
        $transaksis = Transaksi::with(['playStation', 'pelanggan'])
            ->where('status', 'berlangsung')
            ->orderByDesc('waktu_mulai')
            ->get();

        return view('livewire.kompromi', [
            'transaksis' => $transaksis,
            'customMinutes' => $this->customMinutes,
        ])->layout('layouts.app');
    }

    public function stopAndGenerateVoucher(int $transaksiId): void
    {
        $transaksi = Transaksi::with(['playStation', 'pelanggan'])->find($transaksiId);

        if (! $transaksi) {
            $this->errorMessage = 'Transaksi tidak ditemukan';
            return;
        }

        if ($transaksi->status !== 'berlangsung') {
            $this->errorMessage = 'Transaksi sudah tidak aktif';
            return;
        }

        $tarif = Tarif::getTarifByTipe($transaksi->playStation->tipe ?? null);
        if (! $tarif) {
            $this->errorMessage = 'Tarif untuk PS ini tidak ditemukan';
            return;
        }

        $waktuMulai = Carbon::parse($transaksi->waktu_mulai);
        $waktuSelesai = Carbon::parse($transaksi->waktu_selesai);
        $now = Carbon::now();
        
        // Calculate actual total minutes from waktu_selesai - waktu_mulai (not durasi_jam!)
        $totalMinutes = (int) $waktuMulai->diffInMinutes($waktuSelesai);
        
        // Calculate remaining from waktu_selesai
        $remainingMinutes = max(0, (int) $now->diffInMinutes($waktuSelesai, false));

        // Get custom minutes jika ada di array, otherwise gunakan sisa waktu
        $customMenit = isset($this->customMinutes[$transaksiId]) && $this->customMinutes[$transaksiId] > 0 
            ? (int) $this->customMinutes[$transaksiId]
            : $remainingMinutes;
        
        if ($customMenit <= 0) {
            $this->errorMessage = 'Durasi voucher harus lebih dari 0 menit';
            return;
        }

        // Simpan menit asli, hitung jam untuk harga
        $kodeVoucher = Voucher::generateKodeVoucher();
        $durationMinutes = max(0, $customMenit);
        $durationHours = max(1, (int) ceil($durationMinutes / 60));

        $voucher = Voucher::create([
            'kode_voucher' => $kodeVoucher,
            'tarif_id' => $tarif->id,
            'durasi_jam' => $durationHours,
            'durasi_menit' => $durationMinutes,
            'harga_per_jam' => $tarif->harga_per_jam,
            'total_harga' => $tarif->harga_per_jam * $durationHours,
            'metode_pembayaran' => 'kompromi',
            'status' => 'aktif',
            'status_pembayaran' => 'paid',
            'payment_gateway' => 'kompromi',
            'nama_pembeli' => $transaksi->pelanggan->nama ?? 'Kompromi',
            'tanggal_beli' => now(),
            'expired_at' => now()->addDays(30),
        ]);

        // Update transaksi to selesai
        $transaksi->update([
            'status' => 'selesai',
            'waktu_selesai' => $now,
            'durasi_aktual' => (int) ceil($elapsedMinutes / 60),
            'keterangan' => 'Disetop (kompromi), dibuat voucher ' . $durationMinutes . ' menit',
        ]);

        // Set PlayStation status kembali ke tersedia
        $playStation = $transaksi->playStation;
        $playStation->update(['status' => 'tersedia']);
        $playStation->refresh(); // Refresh instance untuk memastikan data terbaru

        $this->successMessage = 'Voucher kompensasi dibuat: ' . $voucher->kode_voucher . ' (' . $durationMinutes . ' menit). PlayStation ' . $playStation->kode_ps . ' kembali tersedia.';
        $this->errorMessage = '';
        unset($this->customMinutes[$transaksiId]);
    }
}

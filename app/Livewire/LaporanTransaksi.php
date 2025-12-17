<?php

namespace App\Livewire;

use App\Models\Transaksi;
use App\Models\PlayStation;
use App\Models\Pelanggan;
use App\Models\Voucher;
use App\Exports\LaporanTransaksiExport;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanTransaksi extends Component
{
    use WithPagination;

    public $tanggal_awal;
    public $tanggal_akhir;
    public $status = '';
    public $tipe_ps = '';

    public function mount()
    {
        // Default: bulan ini
        $this->tanggal_awal = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggal_akhir = Carbon::now()->format('Y-m-d');
    }

    public function updatedTanggalAwal()
    {
        $this->resetPage();
    }

    public function updatedTanggalAkhir()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedTipePs()
    {
        $this->resetPage();
    }

    public function exportExcel()
    {
        $filename = 'Laporan_Transaksi_' . Carbon::now()->format('d-m-Y_H-i-s') . '.xlsx';
        
        return Excel::download(
            new LaporanTransaksiExport($this->tanggal_awal, $this->tanggal_akhir), 
            $filename
        );
    }

    public function render()
    {
        // Get transaksi IDs that came from kompromi vouchers (should be excluded from main list)
        $transaksiIdsFromKompromi = Voucher::where('metode_pembayaran', 'kompromi')
            ->whereNotNull('transaksi_id')
            ->pluck('transaksi_id')
            ->toArray();

        // Query transaksi dengan filter - EXCLUDE transaksi dari kompromi voucher
        $transaksis = Transaksi::with(['pelanggan', 'playStation', 'voucher'])
            ->whereNotIn('id', $transaksiIdsFromKompromi) // Exclude kompromi transactions
            ->when($this->tanggal_awal, function($query) {
                $query->whereDate('waktu_mulai', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('waktu_mulai', '<=', $this->tanggal_akhir);
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->when($this->tipe_ps, function($query) {
                $query->whereHas('playStation', function($q) {
                    $q->where('tipe', $this->tipe_ps);
                });
            })
            ->latest('waktu_mulai')
            ->paginate(10);

        // Query voucher kompromi terpisah
        $kompromis = Voucher::with(['tarif'])
            ->where('metode_pembayaran', 'kompromi')
            ->when($this->tanggal_awal, function($query) {
                $query->whereDate('tanggal_beli', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('tanggal_beli', '<=', $this->tanggal_akhir);
            })
            ->latest('tanggal_beli')
            ->paginate(10);

        // Statistik - Total transaksi TANPA DOUBLE COUNTING dan TANPA KOMPROMI
        // 1. Hitung transaksi sewa PS yang TIDAK menggunakan voucher DAN bukan dari kompromi
        // Caranya: cari transaksi yang ID-nya TIDAK ADA di vouchers.transaksi_id
        $transaksiIdsYangPakaiVoucher = Voucher::whereNotNull('transaksi_id')
            ->pluck('transaksi_id')
            ->toArray();
        
        $total_transaksi_ps_tanpa_voucher = Transaksi::when($this->tanggal_awal, function($query) {
                $query->whereDate('waktu_mulai', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('waktu_mulai', '<=', $this->tanggal_akhir);
            })
            ->whereNotIn('id', $transaksiIdsYangPakaiVoucher) // Exclude transaksi yang pakai voucher
            ->whereNotIn('id', $transaksiIdsFromKompromi) // Exclude transaksi dari kompromi
            ->count();
        
        // 2. Hitung voucher yang sudah DIBELI dan DIBAYAR - EXCLUDE KOMPROMI
        $total_voucher_dibeli = Voucher::when($this->tanggal_awal, function($query) {
                $query->whereDate('tanggal_beli', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('tanggal_beli', '<=', $this->tanggal_akhir);
            })
            ->where('status_pembayaran', 'paid')
            ->where('metode_pembayaran', '!=', 'kompromi') // Exclude kompromi
            ->count();
        
        // Total = Sewa tanpa voucher + Voucher dibeli (no double count!)
        $total_transaksi = $total_transaksi_ps_tanpa_voucher + $total_voucher_dibeli;

        // Hitung total pendapatan dari transaksi - EXCLUDE kompromi transactions
        $total_pendapatan = Transaksi::when($this->tanggal_awal, function($query) {
                $query->whereDate('waktu_mulai', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('waktu_mulai', '<=', $this->tanggal_akhir);
            })
            ->where('status', 'selesai')
            ->whereNotIn('id', $transaksiIdsFromKompromi) // Exclude transaksi dari kompromi
            ->sum('total_biaya');
        
        // Tambahkan pendapatan dari voucher yang sudah dibayar (EXCLUDE kompromi karena kompensasi)
        $voucher_pendapatan = Voucher::where('metode_pembayaran', '!=', 'kompromi')
            ->where(function($query) {
                // Voucher yang dibeli dalam range tanggal dan sudah dibayar
                $query->where(function($subQuery) {
                    $subQuery->when($this->tanggal_awal, function($q) {
                        $q->whereDate('tanggal_beli', '>=', $this->tanggal_awal);
                    })
                    ->when($this->tanggal_akhir, function($q) {
                        $q->whereDate('tanggal_beli', '<=', $this->tanggal_akhir);
                    })
                    ->where('status_pembayaran', 'paid')
                    ->whereNotNull('member_id');
                })
                // ATAU voucher yang sudah dipakai (redeemed) dalam range tanggal
                ->orWhere(function($subQuery) {
                    $subQuery->when($this->tanggal_awal, function($q) {
                        $q->whereDate('tanggal_pakai', '>=', $this->tanggal_awal);
                    })
                    ->when($this->tanggal_akhir, function($q) {
                        $q->whereDate('tanggal_pakai', '<=', $this->tanggal_akhir);
                    })
                    ->where('status', 'terpakai');
                });
            })
            ->sum('total_harga');
        
        $total_pendapatan += $voucher_pendapatan;

        // Removed denda calculation since column no longer exists

        // PS terpopuler - EXCLUDE transaksi dari kompromi voucher
        $ps_terpopuler = Transaksi::select('play_station_id', DB::raw('COUNT(*) as total'))
            ->when($this->tanggal_awal, function($query) {
                $query->whereDate('waktu_mulai', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('waktu_mulai', '<=', $this->tanggal_akhir);
            })
            ->whereNotIn('id', $transaksiIdsFromKompromi) // Exclude transaksi dari kompromi
            ->with('playStation')
            ->groupBy('play_station_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Pelanggan teraktif - TANPA DOUBLE COUNTING DAN TANPA KOMPROMI
        // Step 1: Dari transaksi sewa PS yang TIDAK pakai voucher DAN bukan dari kompromi
        $pelangganStats = [];
        
        // Cari transaksi yang TIDAK pakai voucher
        $transaksiIdsYangPakaiVoucher = Voucher::whereNotNull('transaksi_id')
            ->pluck('transaksi_id')
            ->toArray();
        
        $transaksiPerPelanggan = Transaksi::select(
                'pelanggan_id', 
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN total_biaya > 0 THEN total_biaya ELSE 0 END) as total_biaya')
            )
            ->when($this->tanggal_awal, function($query) {
                $query->whereDate('waktu_mulai', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('waktu_mulai', '<=', $this->tanggal_akhir);
            })
            ->whereNotIn('id', $transaksiIdsYangPakaiVoucher) // HANYA yang TIDAK pakai voucher
            ->whereNotIn('id', $transaksiIdsFromKompromi) // Exclude transaksi dari kompromi
            ->with(['pelanggan'])
            ->groupBy('pelanggan_id')
            ->get();
        
        foreach($transaksiPerPelanggan as $item) {
            $pelangganStats[$item->pelanggan_id] = [
                'pelanggan' => $item->pelanggan,
                'total' => $item->total,
                'total_biaya' => $item->total_biaya,
            ];
        }
        
        // Step 2: Tambahkan aktivitas dari voucher yang dibeli (member beli voucher = aktivitas) - EXCLUDE KOMPROMI
        $vouchersPaidByMember = Voucher::select(
                'member_id',
                DB::raw('COUNT(*) as voucher_count'),
                DB::raw('SUM(total_harga) as voucher_total')
            )
            ->whereNotNull('member_id')
            ->where('status_pembayaran', 'paid')
            ->where('metode_pembayaran', '!=', 'kompromi')
            ->when($this->tanggal_awal, function($query) {
                $query->whereDate('tanggal_beli', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('tanggal_beli', '<=', $this->tanggal_akhir);
            })
            ->groupBy('member_id')
            ->with('member') // Load user/member
            ->get();
        
        foreach($vouchersPaidByMember as $voucherData) {
            $user = $voucherData->member; // Ambil user dari relasi
            if ($user) {
                // Cari pelanggan yang match dengan user ini
                // Prioritas 1: Email sama dengan nomor_hp
                $pelanggan = Pelanggan::where('nomor_hp', $user->email)->first();
                
                // Prioritas 2: Nama exact match
                if (!$pelanggan) {
                    $pelanggan = Pelanggan::where('nama', $user->name)->first();
                }
                
                // Jika tidak ketemu, buat entry virtual dengan data user
                if (!$pelanggan) {
                    // Buat entry virtual untuk member yang belum jadi pelanggan sewa
                    $virtualId = 'member_' . $user->id;
                    $pelangganStats[$virtualId] = [
                        'pelanggan' => (object)[
                            'id' => $virtualId,
                            'nama' => $user->name,
                            'nomor_hp' => $user->email,
                            'isMemberActive' => function() { return true; }
                        ],
                        'total' => $voucherData->voucher_count,
                        'total_biaya' => $voucherData->voucher_total,
                    ];
                } else {
                    // Tambahkan ke pelanggan yang sudah ada
                    if (!isset($pelangganStats[$pelanggan->id])) {
                        $pelangganStats[$pelanggan->id] = [
                            'pelanggan' => $pelanggan,
                            'total' => 0,
                            'total_biaya' => 0,
                        ];
                    }
                    $pelangganStats[$pelanggan->id]['total'] += $voucherData->voucher_count;
                    $pelangganStats[$pelanggan->id]['total_biaya'] += $voucherData->voucher_total;
                }
            }
        }
        
        // Sort dan ambil top 5
        $pelanggan_teraktif_final = collect($pelangganStats)
            ->sortByDesc('total')
            ->take(5)
            ->map(function($item) {
                return (object) $item;
            })
            ->values();

        return view('livewire.laporan-transaksi', [
            'transaksis' => $transaksis,
            'kompromis' => $kompromis,
            'total_transaksi' => $total_transaksi,
            'total_pendapatan' => $total_pendapatan,
            'ps_terpopuler' => $ps_terpopuler,
            'pelanggan_teraktif' => $pelanggan_teraktif_final
        ])->layout('layouts.app');
    }
}

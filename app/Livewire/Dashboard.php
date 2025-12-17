<?php

namespace App\Livewire;

use App\Models\PlayStation;
use App\Models\Transaksi;
use App\Models\Voucher;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function mount()
    {
        $user = auth()->user();
        
        // Redirect member ke dashboard member mereka sendiri
        if ($user->role === 'member') {
            return redirect()->route('member.dashboard');
        }
        
        // Jika bukan owner atau karyawan, forbidden
        if (!in_array($user->role, ['owner', 'karyawan'])) {
            abort(403, 'Akses ditolak');
        }
    }

    public function render()
    {
        $user = auth()->user();
        
        // Get PlayStation stats for live monitoring
        $playstations = PlayStation::with(['transaksis' => function($query) {
            $query->where('status', 'berlangsung')->latest();
        }])->get();

        $psStats = [
            'total' => $playstations->count(),
            'tersedia' => $playstations->where('status', 'tersedia')->count(),
            'dipakai' => $playstations->where('status', 'dipakai')->count(),
            'rusak' => $playstations->where('status', 'rusak')->count(),
        ];

        // Get active transactions with time remaining
        $activeTransactions = Transaksi::with(['playStation', 'pelanggan'])
            ->where('status', 'berlangsung')
            ->get()
            ->map(function($transaksi) {
                $now = Carbon::now();
                $end = Carbon::parse($transaksi->waktu_selesai);
                
                // Auto-complete jika sudah lewat waktu
                if ($now->isAfter($end)) {
                    // Update status transaksi menjadi selesai
                    $transaksi->update([
                        'status' => 'selesai',
                        'durasi_aktual' => $transaksi->durasi_jam, // Set durasi aktual = durasi booking
                    ]);
                    
                    // Update status PlayStation menjadi tersedia
                    $transaksi->playStation->update([
                        'status' => 'tersedia'
                    ]);
                    
                    // Skip transaksi ini dari active list
                    return null;
                }
                
                $transaksi->time_remaining = $end->diff($now);
                $transaksi->is_overtime = false; // Never overtime, auto-complete instead
                $transaksi->progress_percentage = $this->calculateProgress($transaksi);
                
                return $transaksi;
            })
            ->filter() // Remove null values
            ->sortBy('waktu_selesai');

        // Income stats - today
        $today = Carbon::today();
        $todayIncome = Transaksi::whereDate('waktu_mulai', $today)
            ->where('status', 'selesai')
            ->sum('total_biaya');
        
        // Tambahkan pemasukan dari voucher yang dibeli hari ini (EXCLUDE kompromi karena kompensasi)
        $todayVoucherIncome = Voucher::whereDate('tanggal_beli', $today)
            ->where('status_pembayaran', 'paid')
            ->whereNotNull('member_id')
            ->where('metode_pembayaran', '!=', 'kompromi')
            ->sum('total_harga');
        
        $todayIncome += $todayVoucherIncome;

        // Income stats - this month
        $thisMonth = Carbon::now()->startOfMonth();
        $monthIncome = Transaksi::whereDate('waktu_mulai', '>=', $thisMonth)
            ->where('status', 'selesai')
            ->sum('total_biaya');
        
        // Tambahkan pemasukan dari voucher yang dibeli bulan ini (EXCLUDE kompromi karena kompensasi)
        $monthVoucherIncome = Voucher::whereDate('tanggal_beli', '>=', $thisMonth)
            ->where('status_pembayaran', 'paid')
            ->whereNotNull('member_id')
            ->where('metode_pembayaran', '!=', 'kompromi')
            ->sum('total_harga');
        
        $monthIncome += $monthVoucherIncome;

        // Total transactions this month - TANPA DOUBLE COUNTING
        // 1. Transaksi sewa PS yang TIDAK pakai voucher
        $transaksiIdsYangPakaiVoucher = Voucher::whereNotNull('transaksi_id')
            ->pluck('transaksi_id')
            ->toArray();
        
        $monthTransactionsTanpaVoucher = Transaksi::whereDate('waktu_mulai', '>=', $thisMonth)
            ->where('status', 'selesai')
            ->whereNotIn('id', $transaksiIdsYangPakaiVoucher) // Exclude transaksi yang pakai voucher
            ->count();
        
        // 2. Voucher yang sudah DIBELI dan DIBAYAR
        $monthVoucherCount = Voucher::whereDate('tanggal_beli', '>=', $thisMonth)
            ->where('status_pembayaran', 'paid')
            ->count();
        
        // Total = Sewa tanpa voucher + Voucher dibeli (no double count!)
        $monthTransactions = $monthTransactionsTanpaVoucher + $monthVoucherCount;

        // Total members (User dengan role member)
        $totalMembers = User::where('role', 'member')->count();

        // Active vouchers
        $activeVouchers = Voucher::where('status', 'aktif')->count();

        return view('livewire.dashboard', [
            'user' => $user,
            'isOwner' => $user->isOwner(),
            'isKaryawan' => $user->isKaryawan(),
            'playstations' => $playstations,
            'psStats' => $psStats,
            'activeTransactions' => $activeTransactions,
            'todayIncome' => $todayIncome,
            'monthIncome' => $monthIncome,
            'monthTransactions' => $monthTransactions,
            'totalMembers' => $totalMembers,
            'activeVouchers' => $activeVouchers,
        ])->layout('layouts.app');
    }

    private function calculateProgress($transaksi)
    {
        $start = Carbon::parse($transaksi->waktu_mulai);
        $end = Carbon::parse($transaksi->waktu_selesai);
        $now = Carbon::now();

        $total = $start->diffInMinutes($end);
        $elapsed = $start->diffInMinutes($now);

        $percentage = ($elapsed / $total) * 100;
        
        return min(100, max(0, $percentage));
    }
}


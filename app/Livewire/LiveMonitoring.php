<?php

namespace App\Livewire;

use App\Models\PlayStation;
use App\Models\Transaksi;
use Livewire\Component;
use Carbon\Carbon;

class LiveMonitoring extends Component
{
    public function render()
    {
        $playstations = PlayStation::with(['transaksis' => function($query) {
            $query->where('status', 'berlangsung')->latest();
        }])->get();

        $stats = [
            'total' => $playstations->count(),
            'tersedia' => $playstations->where('status', 'tersedia')->count(),
            'digunakan' => $playstations->where('status', 'digunakan')->count(),
            'maintenance' => $playstations->where('status', 'maintenance')->count(),
        ];

        // Get active transactions with time remaining
        $activeTransactions = Transaksi::with(['playStation', 'tarif'])
            ->where('status', 'berlangsung')
            ->get()
            ->map(function($transaksi) {
                $now = Carbon::now();
                $end = Carbon::parse($transaksi->waktu_selesai);
                
                $transaksi->time_remaining = $end->diff($now);
                $transaksi->is_overtime = $now->isAfter($end);
                $transaksi->progress_percentage = $this->calculateProgress($transaksi);
                
                return $transaksi;
            })
            ->sortBy('waktu_selesai');

        return view('livewire.live-monitoring', [
            'playstations' => $playstations,
            'stats' => $stats,
            'activeTransactions' => $activeTransactions,
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


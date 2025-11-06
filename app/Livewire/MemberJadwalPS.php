<?php

namespace App\Livewire;

use App\Models\PlayStation;
use App\Models\Transaksi;
use Livewire\Component;
use Carbon\Carbon;

class MemberJadwalPS extends Component
{
    public function render()
    {
        // Get all PlayStation with their active transactions
        $playstations = PlayStation::with(['transaksis' => function($query) {
            $query->where('status', 'berlangsung')
                  ->with(['pelanggan'])
                  ->latest();
        }])->orderBy('tipe', 'desc')->orderBy('kode_ps')->get();

        // Group by type
        $psGrouped = [
            'PS5' => $playstations->where('tipe', 'PS5'),
            'PS4' => $playstations->where('tipe', 'PS4'),
            'PS3' => $playstations->where('tipe', 'PS3'),
        ];

        // Calculate stats
        $stats = [
            'total' => $playstations->count(),
            'tersedia' => $playstations->where('status', 'tersedia')->count(),
            'dipakai' => $playstations->where('status', 'dipakai')->count(),
            'rusak' => $playstations->where('status', 'rusak')->count(),
        ];

        return view('livewire.member-jadwal-p-s', [
            'psGrouped' => $psGrouped,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}

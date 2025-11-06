<?php

namespace App\Exports;

use App\Models\Transaksi;
use App\Models\Voucher;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanTransaksiExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $tanggal_awal;
    protected $tanggal_akhir;

    public function __construct($tanggal_awal, $tanggal_akhir)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }

    public function collection()
    {
        return Transaksi::with(['pelanggan', 'playStation', 'user', 'voucher'])
            ->when($this->tanggal_awal, function($query) {
                $query->whereDate('waktu_mulai', '>=', $this->tanggal_awal);
            })
            ->when($this->tanggal_akhir, function($query) {
                $query->whereDate('waktu_mulai', '<=', $this->tanggal_akhir);
            })
            ->orderBy('waktu_mulai', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Tanggal',
            'Jam',
            'Pelanggan',
            'Status Member',
            'PlayStation',
            'Tipe',
            'Durasi (Jam)',
            'Durasi Aktual (Jam)',
            'Tarif/Jam',
            'Total Biaya',
            'Jenis',
            'Status',
            'Petugas',
        ];
    }

    public function map($transaksi): array
    {
        $isMember = $transaksi->pelanggan && $transaksi->pelanggan->is_member ? 'Member' : 'Reguler';
        
        // Jika transaksi pakai voucher
        if ($transaksi->voucher) {
            $tarifPerJam = $transaksi->voucher->harga_per_jam; // Ambil dari voucher
            $totalBiaya = $transaksi->voucher->total_harga;
            $jenis = 'Voucher';
        } else {
            $tarifPerJam = $transaksi->tarif_per_jam; // Ambil dari transaksi
            $totalBiaya = $transaksi->total_biaya;
            $jenis = 'Reguler';
        }

        return [
            $transaksi->kode_transaksi,
            $transaksi->waktu_mulai->format('d/m/Y'),
            $transaksi->waktu_mulai->format('H:i'),
            $transaksi->pelanggan->nama ?? '-',
            $isMember,
            $transaksi->playStation->kode_ps ?? '-',
            $transaksi->playStation->tipe ?? '-',
            $transaksi->durasi_jam,
            $transaksi->durasi_aktual ?? $transaksi->durasi_jam,
            'Rp ' . number_format($tarifPerJam, 0, ',', '.'),
            'Rp ' . number_format($totalBiaya, 0, ',', '.'),
            $jenis,
            ucfirst($transaksi->status),
            $transaksi->user->name ?? '-',
        ];
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

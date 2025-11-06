<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_transaksi',
        'pelanggan_id',
        'play_station_id',
        'user_id',
        'waktu_mulai',
        'durasi_jam',
        'waktu_selesai',
        'durasi_aktual',
        'tarif_per_jam',
        'diskon_persen',
        'diskon_nominal',
        'denda',
        'total_biaya',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'tarif_per_jam' => 'decimal:2',
        'diskon_persen' => 'decimal:2',
        'diskon_nominal' => 'decimal:2',
        'denda' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function playStation()
    {
        return $this->belongsTo(PlayStation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->hasOne(Voucher::class);
    }

    // Generate kode transaksi otomatis
    public static function generateKodeTransaksi()
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', now())->latest('id')->first();
        $number = $lastTransaction ? intval(substr($lastTransaction->kode_transaksi, -4)) + 1 : 1;
        return 'TRX' . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Hitung total biaya awal (saat penyewaan)
    public function hitungTotalBiayaAwal()
    {
        $subtotal = $this->tarif_per_jam * $this->durasi_jam;
        $diskon = ($this->diskon_persen / 100) * $subtotal;
        $this->diskon_nominal = $diskon;
        $this->total_biaya = $subtotal - $diskon;
        return $this->total_biaya;
    }

    // Hitung denda dan total akhir (saat pengembalian)
    public function hitungTotalAkhir()
    {
        if (!$this->waktu_selesai) {
            return $this->total_biaya;
        }

        $waktuMulai = Carbon::parse($this->waktu_mulai);
        $waktuSelesai = Carbon::parse($this->waktu_selesai);
        $waktuHarusKembali = $waktuMulai->copy()->addHours($this->durasi_jam);
        
        // Hitung durasi aktual dalam jam (pembulatan ke atas)
        $this->durasi_aktual = ceil($waktuMulai->diffInMinutes($waktuSelesai) / 60);
        
        // Hitung denda jika terlambat
        if ($waktuSelesai->greaterThan($waktuHarusKembali)) {
            $jamTerlambat = ceil($waktuHarusKembali->diffInMinutes($waktuSelesai) / 60);
            $tarifDenda = Tarif::getTarifByTipe($this->playStation->tipe)->denda_per_jam ?? 0;
            $this->denda = $jamTerlambat * $tarifDenda;
        } else {
            $this->denda = 0;
        }

        // Total akhir
        $this->total_biaya = ($this->tarif_per_jam * $this->durasi_aktual) - $this->diskon_nominal + $this->denda;
        return $this->total_biaya;
    }

    // Scopes
    public function scopeBerlangsung($query)
    {
        return $query->where('status', 'berlangsung');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeBatal($query)
    {
        return $query->where('status', 'batal');
    }

    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', now());
    }
}

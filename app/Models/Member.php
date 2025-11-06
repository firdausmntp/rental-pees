<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'kode_member',
        'tanggal_daftar',
        'tanggal_berakhir',
        'is_active',
        'poin',
        'diskon_persen',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_daftar' => 'date',
        'tanggal_berakhir' => 'date',
    ];

    // Relationship dengan Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Check apakah member masih aktif
    public function isExpired()
    {
        return $this->tanggal_berakhir < now();
    }

    // Tambah poin
    public function tambahPoin($jumlah)
    {
        $this->increment('poin', $jumlah);
    }

    // Generate kode member otomatis
    public static function generateKodeMember()
    {
        $lastMember = self::latest('id')->first();
        $number = $lastMember ? intval(substr($lastMember->kode_member, 3)) + 1 : 1;
        return 'MBR' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}

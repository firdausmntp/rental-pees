<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nomor_hp',
        'alamat',
        'is_member',
    ];

    protected $casts = [
        'is_member' => 'boolean',
    ];

    // Relationship dengan Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Check apakah pelanggan adalah member aktif
    public function isMemberActive()
    {
        return $this->is_member;
    }

    // Get diskon member jika ada (deprecated - sekarang tidak ada diskon)
    public function getDiskonMember()
    {
        return 0;
    }
}

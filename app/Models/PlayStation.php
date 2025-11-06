<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ps',
        'tipe',
        'nama_konsol',
        'status',
        'keterangan',
    ];

    // Relationship dengan Transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    // Relationship dengan Tarif
    public function tarif()
    {
        return $this->hasOne(Tarif::class, 'tipe_ps', 'tipe');
    }

    // Scope untuk filter berdasarkan status
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeDipakai($query)
    {
        return $query->where('status', 'dipakai');
    }

    public function scopeRusak($query)
    {
        return $query->where('status', 'rusak');
    }
}

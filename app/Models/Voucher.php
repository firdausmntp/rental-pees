<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $fillable = [
        'kode_voucher',
        'member_id',
        'nama_pembeli',
        'tarif_id',
        'durasi_jam',
        'harga_per_jam',
        'total_harga',
        'status',
        'metode_pembayaran',
        'status_pembayaran',
        'qris_image',
        'approved_by',
        'approved_at',
        'transaksi_id',
        'tanggal_beli',
        'tanggal_pakai',
        'expired_at',
    ];

    protected $casts = [
        'tanggal_beli' => 'datetime',
        'tanggal_pakai' => 'datetime',
        'expired_at' => 'datetime',
        'approved_at' => 'datetime',
        'harga_per_jam' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function tarif(): BelongsTo
    {
        return $this->belongsTo(Tarif::class);
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expired_at);
    }

    public function canBeUsed(): bool
    {
        return $this->status === 'aktif' 
            && $this->status_pembayaran === 'paid' 
            && !$this->isExpired();
    }

    public function isPending(): bool
    {
        return $this->status_pembayaran === 'pending';
    }
}


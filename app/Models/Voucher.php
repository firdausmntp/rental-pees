<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

class Voucher extends Model
{
    protected $fillable = [
        'kode_voucher',
        'member_id',
        'nama_pembeli',
        'tarif_id',
        'durasi_jam',
        'durasi_menit',
        'harga_per_jam',
        'total_harga',
        'status',
        'metode_pembayaran',
        'payment_gateway',
        'payment_reference',
        'snap_token',
        'va_number',
        'status_pembayaran',
        'qris_image',
        'qris_nominal',
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
        'qris_nominal' => 'decimal:2',
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

    /**
     * Generate unique QRIS nominal for manual transfers (adds 1-99 IDR).
     */
    public static function generateUniqueQrisNominal(float $baseTotal): float
    {
        $baseTotal = round($baseTotal, 2);

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $offset = random_int(1, 99);
            $candidate = round($baseTotal + $offset, 2);

            $exists = self::whereDate('created_at', now())
                ->where('qris_nominal', $candidate)
                ->exists();

            if (! $exists) {
                return $candidate;
            }
        }

        throw new RuntimeException('Gagal membuat nominal unik QRIS, silakan coba lagi.');
    }

    public static function generateKodeVoucher()
    {
        $prefix = 'V-';
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        return $prefix . $random;
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_ps',
        'harga_per_jam',
        'denda_per_jam',
    ];

    protected $casts = [
        'harga_per_jam' => 'decimal:2',
        'denda_per_jam' => 'decimal:2',
    ];

    // Relationship dengan PlayStation
    public function playStations()
    {
        return $this->hasMany(PlayStation::class, 'tipe', 'tipe_ps');
    }

    // Get tarif by tipe PS
    public static function getTarifByTipe($tipe)
    {
        return self::where('tipe_ps', $tipe)->first();
    }
}

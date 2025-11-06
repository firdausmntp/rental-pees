<?php

namespace Database\Seeders;

use App\Models\Tarif;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarifs = [
            [
                'tipe_ps' => 'PS3',
                'harga_per_jam' => 5000,
                'denda_per_jam' => 2000,
            ],
            [
                'tipe_ps' => 'PS4',
                'harga_per_jam' => 8000,
                'denda_per_jam' => 3000,
            ],
            [
                'tipe_ps' => 'PS5',
                'harga_per_jam' => 12000,
                'denda_per_jam' => 5000,
            ],
        ];

        foreach ($tarifs as $tarif) {
            Tarif::create($tarif);
        }
    }
}

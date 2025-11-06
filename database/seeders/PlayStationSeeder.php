<?php

namespace Database\Seeders;

use App\Models\PlayStation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $playstations = [
            // PS3
            ['kode_ps' => 'PS3-001', 'tipe' => 'PS3', 'nama_konsol' => 'PlayStation 3 Slim #1', 'status' => 'tersedia'],
            ['kode_ps' => 'PS3-002', 'tipe' => 'PS3', 'nama_konsol' => 'PlayStation 3 Slim #2', 'status' => 'tersedia'],
            
            // PS4
            ['kode_ps' => 'PS4-001', 'tipe' => 'PS4', 'nama_konsol' => 'PlayStation 4 Pro #1', 'status' => 'tersedia'],
            ['kode_ps' => 'PS4-002', 'tipe' => 'PS4', 'nama_konsol' => 'PlayStation 4 Slim #2', 'status' => 'tersedia'],
            ['kode_ps' => 'PS4-003', 'tipe' => 'PS4', 'nama_konsol' => 'PlayStation 4 #3', 'status' => 'tersedia'],
            
            // PS5
            ['kode_ps' => 'PS5-001', 'tipe' => 'PS5', 'nama_konsol' => 'PlayStation 5 Digital #1', 'status' => 'tersedia'],
            ['kode_ps' => 'PS5-002', 'tipe' => 'PS5', 'nama_konsol' => 'PlayStation 5 Disc #2', 'status' => 'tersedia'],
        ];

        foreach ($playstations as $ps) {
            PlayStation::create($ps);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Owner
        User::create([
            'name' => 'Owner',
            'email' => 'owner@rental-ps.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        // Create Karyawan
        User::create([
            'name' => 'Karyawan 1',
            'email' => 'karyawan@rental-ps.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
        ]);

        // Create Member
        User::create([
            'name' => 'Member',
            'email' => 'member@rental-ps.com',
            'password' => Hash::make('member123'),
            'role' => 'member',
        ]);
    }
}

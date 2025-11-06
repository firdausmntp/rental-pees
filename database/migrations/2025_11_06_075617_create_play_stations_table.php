<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('play_stations', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ps')->unique();
            $table->enum('tipe', ['PS3', 'PS4', 'PS5']);
            $table->string('nama_konsol');
            $table->enum('status', ['tersedia', 'dipakai', 'rusak'])->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('play_stations');
    }
};

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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
            $table->foreignId('play_station_id')->constrained('play_stations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Karyawan yang input
            $table->dateTime('waktu_mulai');
            $table->integer('durasi_jam'); // Durasi yang dipesan (jam)
            $table->dateTime('waktu_selesai')->nullable();
            $table->integer('durasi_aktual')->nullable(); // Durasi aktual (jam)
            $table->decimal('tarif_per_jam', 10, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0);
            $table->decimal('diskon_nominal', 10, 2)->default(0);
            $table->decimal('total_biaya', 10, 2);
            $table->enum('status', ['berlangsung', 'selesai', 'batal'])->default('berlangsung');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

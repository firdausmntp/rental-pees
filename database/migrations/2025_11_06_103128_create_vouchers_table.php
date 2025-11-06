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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_voucher')->unique();
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tarif_id')->constrained()->onDelete('cascade');
            $table->integer('durasi_jam'); // durasi dalam jam
            $table->enum('status', ['aktif', 'terpakai', 'expired'])->default('aktif');
            $table->foreignId('transaksi_id')->nullable()->constrained()->onDelete('no action'); // jika sudah dipakai
            $table->timestamp('tanggal_beli');
            $table->timestamp('tanggal_pakai')->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

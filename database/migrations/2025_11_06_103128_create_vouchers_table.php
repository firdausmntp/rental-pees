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
            $table->string('kode_voucher')->nullable()->unique();
            $table->foreignId('member_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama_pembeli')->nullable(); // Untuk pembeli non-member/manual
            
            $table->foreignId('tarif_id')->constrained()->onDelete('cascade');
            $table->decimal('harga_per_jam', 10, 2); // Snapshot harga saat beli
            $table->integer('durasi_jam'); // durasi dalam jam
            $table->decimal('total_harga', 10, 2);
            
            // Status & Pembayaran
            $table->string('status')->default('pending'); // pending, aktif, terpakai, expired
            $table->string('metode_pembayaran'); // cash, qris, midtrans
            $table->string('status_pembayaran')->default('pending'); // pending, paid, failed
            
            // Payment Gateway Details
            $table->string('payment_gateway')->nullable(); // midtrans, xendit, etc
            $table->string('payment_reference')->nullable(); // order_id / external_id
            
            // QRIS Manual Details
            $table->decimal('qris_nominal', 12, 2)->nullable(); // Nominal unik
            $table->string('qris_image')->nullable(); // Bukti transfer
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();

            // Usage
            $table->foreignId('transaksi_id')->nullable()->constrained()->onDelete('no action');
            $table->timestamp('tanggal_beli')->useCurrent();
            $table->timestamp('tanggal_pakai')->nullable();
            $table->timestamp('expired_at')->useCurrent();
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

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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->onDelete('cascade');
            $table->string('kode_member')->unique();
            $table->date('tanggal_daftar');
            $table->date('tanggal_berakhir');
            $table->boolean('is_active')->default(true);
            $table->integer('poin')->default(0);
            $table->decimal('diskon_persen', 5, 2)->default(10.00); // Default 10%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

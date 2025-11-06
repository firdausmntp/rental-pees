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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['cash', 'qris'])->default('cash')->after('status');
            $table->enum('status_pembayaran', ['pending', 'paid', 'cancelled'])->default('pending')->after('metode_pembayaran');
            $table->decimal('total_harga', 10, 2)->after('status_pembayaran');
            $table->text('qris_image')->nullable()->after('total_harga');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('no action')->after('qris_image');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'status_pembayaran', 'total_harga', 'qris_image', 'approved_by', 'approved_at']);
        });
    }
};

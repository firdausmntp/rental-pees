<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('method')->unique();
            $table->string('label');
            $table->boolean('enabled')->default(true);
            $table->boolean('auto_cancel_pending')->default(true);
            $table->timestamps();
        });

        DB::table('payment_settings')->insert([
            [
                'method' => 'cash',
                'label' => 'Cash',
                'enabled' => true,
                'auto_cancel_pending' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'method' => 'qris',
                'label' => 'QRIS Manual',
                'enabled' => true,
                'auto_cancel_pending' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'method' => 'pakasir',
                'label' => 'Pakasir Gateway',
                'enabled' => true,
                'auto_cancel_pending' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'method' => 'midtrans',
                'label' => 'Midtrans Gateway',
                'enabled' => false,
                'auto_cancel_pending' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};

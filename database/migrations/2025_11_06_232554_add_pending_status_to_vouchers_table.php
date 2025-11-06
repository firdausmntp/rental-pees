<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the existing CHECK constraint
        DB::statement("ALTER TABLE vouchers DROP CONSTRAINT CK__vouchers__status__318258D2");
        
        // Modify the status column to include 'pending'
        DB::statement("ALTER TABLE vouchers ALTER COLUMN status VARCHAR(50)");
        
        // Add new CHECK constraint with 'pending' included
        DB::statement("ALTER TABLE vouchers ADD CONSTRAINT CK_vouchers_status CHECK (status IN ('pending', 'aktif', 'terpakai', 'expired'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new CHECK constraint
        DB::statement("ALTER TABLE vouchers DROP CONSTRAINT CK_vouchers_status");
        
        // Add back the old CHECK constraint without 'pending'
        DB::statement("ALTER TABLE vouchers ADD CONSTRAINT CK__vouchers__status__318258D2 CHECK (status IN ('aktif', 'terpakai', 'expired'))");
    }
};

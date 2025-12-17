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
        // Only run this for SQL Server as it seems to have created implicit constraints for strings/enums
        if (DB::getDriverName() === 'sqlsrv') {
            // Drop constraint for 'status' column if it exists
            try {
                DB::statement('ALTER TABLE vouchers DROP CONSTRAINT CK_vouchers_status');
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }

            // Drop constraint for 'status_pembayaran' column if it exists
            try {
                DB::statement('ALTER TABLE vouchers DROP CONSTRAINT CK_vouchers_status_pembayaran');
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to restore these constraints as we want the columns to be flexible strings
    }
};

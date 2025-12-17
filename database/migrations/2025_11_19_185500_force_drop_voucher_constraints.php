<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlsrv') {
            // Force drop constraints using raw SQL with existence check
            // This handles cases where the previous migration might have failed silently or constraints were recreated
            
            try {
                DB::statement("
                    IF EXISTS (SELECT * FROM sys.check_constraints WHERE name = 'CK_vouchers_status')
                    ALTER TABLE vouchers DROP CONSTRAINT CK_vouchers_status;
                ");
                
                DB::statement("
                    IF EXISTS (SELECT * FROM sys.check_constraints WHERE name = 'CK_vouchers_status_pembayaran')
                    ALTER TABLE vouchers DROP CONSTRAINT CK_vouchers_status_pembayaran;
                ");
            } catch (\Exception $e) {
                // Log error but don't stop migration if possible, or rethrow if critical
                throw $e;
            }
        }
    }

    public function down(): void
    {
    }
};

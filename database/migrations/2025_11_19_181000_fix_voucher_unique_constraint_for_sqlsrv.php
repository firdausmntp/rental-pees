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
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlsrv') {
            // MySQL/Postgres already allow multiple NULLs, so keep existing index intact
            return;
        }

        // Drop the standard unique constraint which doesn't allow multiple NULLs in SQL Server
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropUnique('vouchers_kode_voucher_unique');
        });

        // Create a filtered unique index that allows multiple NULLs
        // This syntax is specific to SQL Server (and SQLite/Postgres, but MySQL uses a different approach or allows NULLs by default)
        // Since the user is using SQL Server (sqlsrv), we use the WHERE clause.
        DB::statement("CREATE UNIQUE INDEX vouchers_kode_voucher_unique ON vouchers(kode_voucher) WHERE kode_voucher IS NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver !== 'sqlsrv') {
            return;
        }

        // Drop the filtered index
        DB::statement("DROP INDEX vouchers_kode_voucher_unique ON vouchers");

        // Restore the standard unique constraint
        Schema::table('vouchers', function (Blueprint $table) {
            $table->unique('kode_voucher');
        });
    }
};

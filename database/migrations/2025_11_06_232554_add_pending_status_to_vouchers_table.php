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
        // Drop DEFAULT constraint if exists
        $defaultConstraint = DB::selectOne("
            SELECT dc.name 
            FROM sys.default_constraints dc
            INNER JOIN sys.columns c ON dc.parent_object_id = c.object_id AND dc.parent_column_id = c.column_id
            WHERE dc.parent_object_id = OBJECT_ID('vouchers') 
            AND c.name = 'status'
        ");
        
        if ($defaultConstraint) {
            DB::statement("ALTER TABLE vouchers DROP CONSTRAINT {$defaultConstraint->name}");
        }
        
        // Drop CHECK constraint if exists
        $checkConstraint = DB::selectOne("
            SELECT name 
            FROM sys.check_constraints 
            WHERE parent_object_id = OBJECT_ID('vouchers') 
            AND COL_NAME(parent_object_id, parent_column_id) = 'status'
        ");
        
        if ($checkConstraint) {
            DB::statement("ALTER TABLE vouchers DROP CONSTRAINT {$checkConstraint->name}");
        }
        
        // Modify the status column to include 'pending'
        DB::statement("ALTER TABLE vouchers ALTER COLUMN status VARCHAR(50) NOT NULL");
        
        // Add new CHECK constraint with 'pending' included
        DB::statement("ALTER TABLE vouchers ADD CONSTRAINT CK_vouchers_status CHECK (status IN ('pending', 'aktif', 'terpakai', 'expired'))");
        
        // Add back DEFAULT constraint
        DB::statement("ALTER TABLE vouchers ADD CONSTRAINT DF_vouchers_status DEFAULT 'aktif' FOR status");
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ MySQL / MariaDB: widen the ENUM to include 'biweekly'
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE `projects`
                MODIFY `recurring_type`
                ENUM('weekly','biweekly','monthly','yearly') NULL
            ");
        }

    }

    public function down(): void
    {
        // Rollback: remove 'biweekly' from ENUM (MySQL/MariaDB)
        if (DB::getDriverName() === 'mysql') {
            // ⚠ If any row currently has 'biweekly', change it before shrinking the enum
            DB::statement("
                UPDATE `projects`
                SET `recurring_type` = NULL
                WHERE `recurring_type` = 'biweekly'
            ");

            DB::statement("
                ALTER TABLE `projects`
                MODIFY `recurring_type`
                ENUM('weekly','monthly','yearly') NULL
            ");
        }
    }
};

<?php
// database/migrations/2025_10_01_000001_add_company_type_to_companies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'company_type')) {
                // not nullable so existing rows get default = 1 automatically
                $table->unsignedTinyInteger('company_type')
                      ->default(1) // 1=Indian, 2=Non-Indian
                      ->comment('1=Indian, 2=Non-Indian')
                      ->after('name'); // move if you prefer
                $table->index('company_type', 'companies_company_type_idx');
            }
        });

        // Optional CHECK constraint (MySQL 8.0.16+). Safe to ignore if not supported.
        try {
            DB::statement("
                ALTER TABLE companies
                ADD CONSTRAINT chk_companies_company_type
                CHECK (company_type IN (1,2))
            ");
        } catch (\Throwable $e) {
            // ignore on older MySQL or if already exists
        }
    }

    public function down(): void
    {
        // Drop CHECK if present
        try {
            DB::statement("ALTER TABLE companies DROP CONSTRAINT chk_companies_company_type");
        } catch (\Throwable $e) {}

        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'company_type')) {
                $table->dropIndex('companies_company_type_idx');
                $table->dropColumn('company_type');
            }
        });
    }
};

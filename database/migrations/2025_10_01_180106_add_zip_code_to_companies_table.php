<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'zip_code')) {
                $table->string('zip_code', 20)
                      ->nullable()                    // default NULL
                      ->comment('Postal/ZIP code')
                      ->after('name');               // move if you prefer
                $table->index('zip_code', 'companies_zip_code_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'zip_code')) {
                $table->dropIndex('companies_zip_code_idx');
                $table->dropColumn('zip_code');
            }
        });
    }
};

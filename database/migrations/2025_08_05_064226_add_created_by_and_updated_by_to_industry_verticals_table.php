<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('industry_verticals', function (Blueprint $table) {
            // created_by
            if (!Schema::hasColumn('industry_verticals', 'created_by')) {
                $table->foreignId('created_by')
                      ->nullable()
                      ->after('status')
                      ->constrained('users')
                      ->nullOnDelete(); // SET NULL on user delete
            }

            // updated_by
            if (!Schema::hasColumn('industry_verticals', 'updated_by')) {
                $table->foreignId('updated_by')
                      ->nullable()
                      ->after('created_by')
                      ->constrained('users')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('industry_verticals', function (Blueprint $table) {
            // These helpers drop the FK + the column in one go,
            // but only if the column exists.
            if (Schema::hasColumn('industry_verticals', 'created_by')) {
                $table->dropConstrainedForeignId('created_by');
            }
            if (Schema::hasColumn('industry_verticals', 'updated_by')) {
                $table->dropConstrainedForeignId('updated_by');
            }
        });
    }
};

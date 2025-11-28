<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add the column only if it doesn't already exist
        if (! Schema::hasColumn('departments', 'industry_verticals_id')) {
            Schema::table('departments', function (Blueprint $table) {
                // Nullable is safer if some departments don't have a vertical yet
                $table->unsignedBigInteger('industry_verticals_id')->nullable()->after('id');

                // Foreign key to industry_verticals(id)
                $table->foreign('industry_verticals_id', 'departments_industry_verticals_id_fk')
                      ->references('id')
                      ->on('industry_verticals')
                      ->nullOnDelete()   // set NULL if an industry vertical is removed
                      ->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        // Drop FK first (if present), then column
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'industry_verticals_id')) {
                // Use the same constraint name as in up()
                $table->dropForeign('departments_industry_verticals_id_fk');
                $table->dropColumn('industry_verticals_id');
            }
        });
    }
};

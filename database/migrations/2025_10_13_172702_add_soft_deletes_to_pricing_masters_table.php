<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pricing_masters', function (Blueprint $table) {
            // adds nullable TIMESTAMP `deleted_at`
            if (!Schema::hasColumn('pricing_masters', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pricing_masters', function (Blueprint $table) {
            if (Schema::hasColumn('pricing_masters', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

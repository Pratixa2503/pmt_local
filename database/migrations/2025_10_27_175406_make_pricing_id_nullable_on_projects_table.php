<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('projects', 'pricing_id')) {
            Schema::table('projects', function (Blueprint $table) {
                // Make existing column nullable
                $table->unsignedBigInteger('pricing_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('projects', 'pricing_id')) {
            Schema::table('projects', function (Blueprint $table) {
                // Revert to NOT NULL (adjust if you had a default)
                $table->unsignedBigInteger('pricing_id')->nullable(false)->change();
            });
        }
    }
};

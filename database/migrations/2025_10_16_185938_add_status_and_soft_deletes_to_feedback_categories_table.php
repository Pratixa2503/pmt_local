<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('feedback_categories', 'status')) {
                $table->boolean('status')->default(1)->after('name');
            }
            if (!Schema::hasColumn('feedback_categories', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_categories', function (Blueprint $table) {
            if (Schema::hasColumn('feedback_categories', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('feedback_categories', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

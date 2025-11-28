<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('query_statuses', function (Blueprint $table) {
            if (!Schema::hasColumn('query_statuses', 'status')) {
                $table->boolean('status')->default(1)->after('name');
            }
            if (!Schema::hasColumn('query_statuses', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('query_statuses', function (Blueprint $table) {
            if (Schema::hasColumn('query_statuses', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('query_statuses', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_intakes', function (Blueprint $table) {
            if (Schema::hasColumn('project_intakes', 'project_manager')) {
                $table->dropColumn('project_manager');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_intakes', function (Blueprint $table) {
            if (!Schema::hasColumn('project_intakes', 'project_manager')) {
                $table->string('project_manager')->nullable()->after('parent_id');
            }
        });
    }
};

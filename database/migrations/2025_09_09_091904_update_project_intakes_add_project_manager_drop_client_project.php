<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_intakes', function (Blueprint $table) {
            // Add new project_manager field
            if (!Schema::hasColumn('project_intakes', 'project_manager')) {
                $table->string('project_manager')->nullable()->after('parent_id');
            }

            // Drop old fields
            if (Schema::hasColumn('project_intakes', 'client_name')) {
                $table->dropColumn('client_name');
            }
            if (Schema::hasColumn('project_intakes', 'project_name')) {
                $table->dropColumn('project_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_intakes', function (Blueprint $table) {
            // Rollback: restore dropped fields
            if (!Schema::hasColumn('project_intakes', 'client_name')) {
                $table->string('client_name')->nullable()->after('parent_id');
            }
            if (!Schema::hasColumn('project_intakes', 'project_name')) {
                $table->string('project_name')->nullable()->after('client_name');
            }

            // Remove new field
            if (Schema::hasColumn('project_intakes', 'project_manager')) {
                $table->dropColumn('project_manager');
            }
        });
    }
};

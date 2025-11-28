<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add suite_id to project_intakes (nullable)
        Schema::table('project_intakes', function (Blueprint $table) {
            if (!Schema::hasColumn('project_intakes', 'suite_id')) {
                $table->string('suite_id', 255)->nullable()->after('tenant_or_lease_id');
                $table->index('suite_id', 'pi_suite_id_idx');
            }
        });

        // Drop suite_id from projects (if it exists)
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'suite_id')) {
                $table->dropColumn('suite_id');
            }
        });
    }

    public function down(): void
    {
        // Re-add suite_id to projects (nullable)
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'suite_id')) {
                $table->string('suite_id', 255)->nullable()->after('project_name');
                $table->index('suite_id', 'p_suite_id_idx');
            }
        });

        // Remove suite_id from project_intakes
        Schema::table('project_intakes', function (Blueprint $table) {
            if (Schema::hasColumn('project_intakes', 'suite_id')) {
                // Drop index first if needed
                $table->dropIndex('pi_suite_id_idx');
                $table->dropColumn('suite_id');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_intakes', function (Blueprint $table) {
            // Place them near their related fields; adjust "after(...)" if needed for your schema.
            if (Schema::hasColumn('project_intakes', 'reviewer_id')) {
                $table->date('review_start_date')->nullable()->after('reviewer_id');
            } else {
                $table->date('review_start_date')->nullable();
            }

            if (Schema::hasColumn('project_intakes', 'sense_check_ddr_id')) {
                $table->date('sense_check_start_date')->nullable()->after('sense_check_ddr_id');
            } else {
                $table->date('sense_check_start_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_intakes', function (Blueprint $table) {
            // Drop columns if they exist (safe for partial deployments)
            if (Schema::hasColumn('project_intakes', 'review_start_date')) {
                $table->dropColumn('review_start_date');
            }
            if (Schema::hasColumn('project_intakes', 'sense_check_start_date')) {
                $table->dropColumn('sense_check_start_date');
            }
        });
    }
};

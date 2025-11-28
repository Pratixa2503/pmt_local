<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            // Ensure soft deletes column exists (skip if you already have it)
            if (!Schema::hasColumn('invoice_lines', 'deleted_at')) {
                $table->softDeletes();
            }

            // Drop old unique index (name might differ in your DB; change if needed)
            $table->dropUnique('uniq_inv_lines_project_intake_month');

            // Recreate unique index including deleted_at
            $table->unique(
                ['project_id', 'source_intake_id', 'billing_month', 'deleted_at'],
                'uniq_inv_lines_project_intake_month'
            );
        });
    }

    public function down(): void
    {
        Schema::table('invoice_lines', function (Blueprint $table) {
            $table->dropUnique('uniq_inv_lines_project_intake_month');
            // Old index without deleted_at
            $table->unique(
                ['project_id', 'source_intake_id', 'billing_month'],
                'uniq_inv_lines_project_intake_month'
            );
        });
    }
};

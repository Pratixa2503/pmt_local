<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private array $tables = [
        'service_offerings',
        'unit_of_measurements',
        'currencies',
        'descriptions',
        'skill_masters',
        'project_types',
        'departments',
        'project_priorities',
        'project_statuses',
        'project_delivery_frequencies',
        'mode_of_deliveries',
        'input_output_formats',
        'intake_statuses',
        'intake_query_types',
        'intake_lease_types',
        'intake_work_types',
        'intake_languages',
        'main_tasks',
        'sub_tasks',
        'companies',
        'banks',
        'po_numbers',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                // Add soft deletes only if not already present
                if (!Schema::hasColumn($table, 'deleted_at')) {
                    $t->softDeletes()->after('updated_at');
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (Schema::hasColumn($table, 'deleted_at')) {
                    $t->dropSoftDeletes();
                }
            });
        }
    }
};

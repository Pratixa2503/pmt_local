<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('service_offerings', 'department_id')) {
            Schema::table('service_offerings', function (Blueprint $table) {
                // Make it nullable first to avoid failing on existing rows
                $table->unsignedBigInteger('department_id')->nullable()->after('id');

                // Foreign key to departments(id)
                $table->foreign('department_id', 'so_department_id_fk')
                      ->references('id')
                      ->on('departments')
                      ->nullOnDelete()     // set to NULL if a department is deleted
                      ->cascadeOnUpdate(); // keep FK in sync on id updates
            });
        }
    }

    public function down(): void
    {
        Schema::table('service_offerings', function (Blueprint $table) {
            if (Schema::hasColumn('service_offerings', 'department_id')) {
                // Drop FK first, then column
                $table->dropForeign('so_department_id_fk');
                $table->dropColumn('department_id');
            }
        });
    }
};

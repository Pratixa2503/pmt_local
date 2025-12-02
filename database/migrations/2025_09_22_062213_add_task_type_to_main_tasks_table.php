<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('main_tasks', function (Blueprint $table) {
            // 1 = Productive, 2 = General
            $table->unsignedTinyInteger('task_type')
                ->default(1)
                ->comment('1=Productive, 2=General')
                ->after('id'); // adjust position if you like, or remove ->after(...)
        });

        // Optional: add a CHECK constraint when supported (e.g., MySQL 8+, PostgreSQL)
        try {
            DB::statement("ALTER TABLE main_tasks ADD CONSTRAINT chk_main_tasks_task_type CHECK (task_type IN (1,2))");
        } catch (\Throwable $e) {
            // DB might not support CHECK or name already exists — ignore
        }
    }

    public function down(): void
    {
        // Drop the CHECK constraint if your DB requires explicit drop (best-effort)
        try {
            DB::statement("ALTER TABLE main_tasks DROP CONSTRAINT chk_main_tasks_task_type");
        } catch (\Throwable $e) {
            // Some engines (e.g. MySQL with parsed check) won’t need this
        }

        Schema::table('main_tasks', function (Blueprint $table) {
            $table->dropColumn('task_type');
        });
    }
};

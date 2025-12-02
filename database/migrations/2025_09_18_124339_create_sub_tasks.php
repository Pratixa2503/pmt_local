<?php
// database/migrations/2025_09_18_000001_create_sub_tasks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\TaskSeeder;

return new class extends Migration {
    public function up(): void {
        Schema::create('sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_task_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('task_type')->default(1)
                  ->comment('1 = Production, 2 = Non-Production');
            $table->time('benchmarked_time')->nullable(); // HH:MM:SS
            $table->unsignedTinyInteger('status')->default(1)->comment('1 = Active, 0 = Inactive');
            $table->timestamps();
        });
        $status_seeder = new TaskSeeder();
        $status_seeder->run();
    }

    public function down(): void {
        Schema::dropIfExists('sub_tasks');
    }
};


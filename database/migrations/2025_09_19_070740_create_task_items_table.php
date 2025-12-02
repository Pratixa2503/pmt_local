<?php
// database/migrations/2025_09_19_000100_create_task_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('task_items', function (Blueprint $table) {
            $table->id();

            // Scope of work
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Link to your masters
            $table->foreignId('main_task_id')->constrained('main_tasks')->cascadeOnDelete();
            $table->foreignId('sub_task_id')->constrained('sub_tasks')->cascadeOnDelete();

            // State machine
            $table->unsignedTinyInteger('status')->default(0)->comment('0=pending,1=in_progress,2=paused,3=completed,4=cancelled');

            // Timing (rolled-up)
            $table->unsignedBigInteger('total_seconds')->default(0)->comment('Denormalized sum of work_sessions for quick reads');

            // Milestones (wall-clock duration can be computed via completed_at - started_at)
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Optional notes
            $table->text('notes')->nullable();

            $table->timestamps();

            // Helpful indexes
            $table->index(['project_id', 'user_id']);
            $table->index(['user_id', 'status']);
            $table->index(['project_id', 'status']);
            $table->index(['main_task_id', 'sub_task_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('task_items');
    }
};

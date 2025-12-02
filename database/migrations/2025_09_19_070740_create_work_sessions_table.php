<?php
// database/migrations/2025_09_19_000200_create_work_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('work_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_item_id')->constrained('task_items')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable(); // null = currently running

            $table->timestamps();

            $table->index(['user_id', 'ended_at']);
            $table->index(['task_item_id', 'started_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('work_sessions');
    }
};

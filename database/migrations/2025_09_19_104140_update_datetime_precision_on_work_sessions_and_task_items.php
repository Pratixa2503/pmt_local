<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_sessions', function (Blueprint $table) {
            $table->dateTime('started_at', 6)->change();
            $table->dateTime('ended_at', 6)->nullable()->change();
        });

        Schema::table('task_items', function (Blueprint $table) {
            $table->dateTime('started_at', 6)->nullable()->change();
            $table->dateTime('completed_at', 6)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('work_sessions', function (Blueprint $table) {
            $table->dateTime('started_at')->change();
            $table->dateTime('ended_at')->nullable()->change();
        });

        Schema::table('task_items', function (Blueprint $table) {
            $table->dateTime('started_at')->nullable()->change();
            $table->dateTime('completed_at')->nullable()->change();
        });
    }
};

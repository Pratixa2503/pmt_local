<?php
// database/migrations/2025_01_01_000000_create_intake_queries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('intake_queries', function (Blueprint $table) {
            $table->id();

            // FK to your intake ROW record.
            $table->unsignedBigInteger('intake_id')->index();
            // Master IDs (no hard FK so it stays flexible with your existing masters)
            $table->unsignedBigInteger('type_of_queries_id')->nullable()->index();
            $table->unsignedBigInteger('query_status_id')->nullable()->index();

            // Content
            $table->text('sb_queries')->nullable();
            $table->text('client_response')->nullable();

            // Dates
            $table->date('query_raised_date')->nullable()->index();
            $table->date('query_resolved_date')->nullable()->index();

            $table->timestamps();

            // Useful compound index
            $table->index(['intake_id', 'query_status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_queries');
    }
};

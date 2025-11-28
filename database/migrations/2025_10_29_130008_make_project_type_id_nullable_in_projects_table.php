<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Make project_type_id nullable
            $table->unsignedBigInteger('project_type_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Revert back to not nullable if needed
            $table->unsignedBigInteger('project_type_id')->nullable(false)->change();
        });
    }
};

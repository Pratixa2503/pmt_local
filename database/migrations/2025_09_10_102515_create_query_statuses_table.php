<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\QueryStatusSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('query_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        $status_seeder = new QueryStatusSeeder();
        $status_seeder->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('query_statuses');
    }
};

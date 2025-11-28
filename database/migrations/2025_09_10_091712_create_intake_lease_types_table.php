<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\IntakeLeaseTypeSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_lease_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        $status_seeder = new IntakeLeaseTypeSeeder();
        $status_seeder->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_lease_types');
    }
};

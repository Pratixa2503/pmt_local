<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // Company Name
            $table->string('address')->nullable();  // Address
            $table->string('location')->nullable(); // Location
            $table->string('contact_no')->nullable(); // Company contact no.
            $table->string('website')->nullable();  // Website (URL)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\InvoiceFormatSeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_formats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        $status_seeder = new InvoiceFormatSeeder();
        $status_seeder->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_formats');
    }
};

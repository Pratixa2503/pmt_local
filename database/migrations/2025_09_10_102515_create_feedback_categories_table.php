<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\FeedbackCategorySeeder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        $status_seeder = new FeedbackCategorySeeder();
        $status_seeder->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_categories');
    }
};

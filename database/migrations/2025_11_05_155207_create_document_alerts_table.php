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
        Schema::create('document_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('alert_days')->comment('Number of days before contract start to send alert (5, 10, 20, 30)');
            $table->string('alert_file')->nullable()->comment('File path for alert-specific attachment');
            $table->timestamp('sent_at')->nullable()->comment('Timestamp when alert was sent (prevents duplicates)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_alerts');
    }
};

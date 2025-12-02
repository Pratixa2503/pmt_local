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
        Schema::create('document_alert_sent_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->constrained('document_alerts')->cascadeOnDelete();
            $table->foreignId('contract_id')->constrained('document_contracts')->cascadeOnDelete();
            $table->integer('alert_days')->comment('The alert_days value for this specific send');
            $table->date('sent_date')->comment('The date when the email was sent');
            $table->timestamp('sent_at')->comment('Exact timestamp when email was sent');
            $table->string('recipient_email')->comment('Email address of the recipient');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate sends for same alert/contract/alert_days/date
            $table->unique(['alert_id', 'contract_id', 'alert_days', 'sent_date'], 'unique_alert_send');
            
            // Index for faster lookups
            $table->index(['alert_id', 'contract_id', 'sent_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_alert_sent_logs');
    }
};

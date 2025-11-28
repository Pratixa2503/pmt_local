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
        Schema::table('document_alerts', function (Blueprint $table) {
            $table->foreignId('contract_id')->nullable()->after('document_id')->constrained('document_contracts')->cascadeOnDelete()->cascadeOnUpdate();
        });
        
        // Change alert_days from integer to JSON to support multiple days
        Schema::table('document_alerts', function (Blueprint $table) {
            $table->json('alert_days')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_alerts', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropColumn('contract_id');
            $table->integer('alert_days')->nullable()->change();
        });
    }
};

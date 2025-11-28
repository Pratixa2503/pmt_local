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
        Schema::table('invoices', function (Blueprint $table) {
            // Bank reference (nullable, because sometimes invoice may not be tied to bank immediately)
            $table->unsignedBigInteger('bank_id')->nullable()->after('customer_id');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');

            // Payment completed flag
            $table->boolean('payment_completed')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn(['bank_id', 'payment_completed']);
        });
    }
};

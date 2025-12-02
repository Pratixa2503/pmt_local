<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to add 'payment_completed' status
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft', 'submitted', 'finance_approved', 'sent', 'rejected', 'payment_completed') DEFAULT 'submitted'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'payment_completed' from the enum
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('draft', 'submitted', 'finance_approved', 'sent', 'rejected') DEFAULT 'submitted'");
    }
};

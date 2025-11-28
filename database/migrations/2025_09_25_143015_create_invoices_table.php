<?php
// database/migrations/2025_09_25_100000_create_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('invoices', function (Blueprint $t) {
      $t->bigIncrements('id');

      // Context
      $t->unsignedBigInteger('project_id')->index();
      $t->unsignedBigInteger('customer_id')->nullable()->index();
      $t->string('billing_month', 7)->index(); // "YYYY-MM"

      // Header
      $t->string('po_number')->nullable();
      $t->string('invoice_no')->unique();
      $t->date('invoice_date')->nullable();
      $t->date('due_date')->nullable();

      // Workflow/status
      $t->enum('status', [
        'draft',              // optional
        'submitted',          // created by PM
        'finance_approved',   // finance approved
        'sent',               // emailed to client
        'rejected'            // rejected back to PM
      ])->default('submitted')->index();

      $t->unsignedBigInteger('created_by')->nullable()->index();
      $t->unsignedBigInteger('updated_by')->nullable()->index();
      $t->unsignedBigInteger('assigned_to')->nullable()->index(); // finance user etc

      // Currency snapshot
      $t->unsignedBigInteger('currency_id')->nullable()->index();
      $t->string('currency_name', 64)->nullable();
      $t->string('currency_symbol', 8)->nullable();

      // Totals (snapshotted)
      $t->decimal('gross_total', 14, 2)->default(0);
      $t->decimal('discount_total', 14, 2)->default(0);
      $t->decimal('tax_total', 14, 2)->default(0);
      $t->decimal('net_total', 14, 2)->default(0);

      // Company snapshot (from your config/company.php)
      $t->string('company_name')->nullable();
      $t->text('company_address')->nullable();
      $t->string('company_pan', 20)->nullable();
      $t->string('company_gstin', 30)->nullable();
      $t->string('company_lut_no', 50)->nullable();
      $t->string('company_iec', 30)->nullable();
      $t->string('company_reference_no', 50)->nullable();
      $t->string('company_signatory')->nullable();

      // Customer snapshot
      $t->string('customer_name')->nullable();
      $t->text('customer_address')->nullable();
      $t->string('customer_type', 20)->nullable(); // "IND" / "US" etc (if you use)
      $t->text('description')->nullable(); // free text note for invoice

      $t->timestamps();
      $t->softDeletes();
    });
  }

  public function down(): void {
    Schema::dropIfExists('invoices');
  }
};

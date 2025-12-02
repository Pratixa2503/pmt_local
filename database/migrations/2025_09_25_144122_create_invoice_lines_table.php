<?php
// database/migrations/2025_09_25_100100_create_invoice_lines_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('invoice_lines', function (Blueprint $t) {
      $t->bigIncrements('id');

      $t->unsignedBigInteger('invoice_id')->index();
      $t->unsignedBigInteger('project_id')->index();
      $t->string('billing_month', 7)->index(); // "YYYY-MM" (copy from invoice)

      // Rendered line
      $t->unsignedInteger('sno')->default(1);
      $t->text('description')->nullable();
      $t->string('sac', 30)->nullable();
      $t->decimal('qty', 14, 2)->default(1);
      $t->decimal('rate', 14, 2)->default(0);
      $t->decimal('value', 14, 2)->default(0);

      // For Category 2: the linked intake
      $t->unsignedBigInteger('source_intake_id')->nullable()->index();

      $t->timestamps();
      $t->softDeletes();

      // Composite unique => one intake per project per month
      $t->unique(
        ['project_id', 'source_intake_id', 'billing_month'],
        'uniq_inv_lines_project_intake_month'
      );
    });
  }

  public function down(): void {
    Schema::dropIfExists('invoice_lines');
  }
};

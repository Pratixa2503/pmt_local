<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_pdf_path_to_invoices_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('invoices', function (Blueprint $table) {
      if (!Schema::hasColumn('invoices', 'pdf_path')) {
        $table->string('pdf_path')->nullable()->after('description');
      }
      if (!Schema::hasColumn('invoices', 'pdf_generated_at')) {
        $table->timestamp('pdf_generated_at')->nullable()->after('pdf_path');
      }
    });
  }
  public function down(): void {
    Schema::table('invoices', function (Blueprint $table) {
      if (Schema::hasColumn('invoices', 'pdf_generated_at')) $table->dropColumn('pdf_generated_at');
      if (Schema::hasColumn('invoices', 'pdf_path')) $table->dropColumn('pdf_path');
    });
  }
};

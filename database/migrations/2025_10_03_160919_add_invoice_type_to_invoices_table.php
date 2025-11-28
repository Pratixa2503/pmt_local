<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedTinyInteger('invoice_type')
                  ->default(null) // 1 = India, 2 = US
                  ->comment('1 = India, 2 = US')
                  ->after('customer_type'); // adjust position if needed
        });

        // Backfill from companies.invoice_type (fallback to companies.company_type, else 1)
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_type');
        });
    }
};

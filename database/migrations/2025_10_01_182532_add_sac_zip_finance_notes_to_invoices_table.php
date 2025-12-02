<?php
// database/migrations/2025_10_01_000300_add_sac_zip_finance_notes_to_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // place near other customer fields
            if (!Schema::hasColumn('invoices', 'customer_zipcode')) {
                $table->string('customer_zipcode', 20)
                      ->nullable()
                      ->after('customer_address')
                      ->comment('Snapshot of customer postal/ZIP at invoice time');
                $table->index('customer_zipcode', 'invoices_customer_zipcode_idx');
            }

            // place near description
            if (!Schema::hasColumn('invoices', 'sac_number')) {
                $table->string('sac_number', 30)
                      ->nullable()
                      ->after('description')
                      ->comment('SAC (Services Accounting Code) for this invoice');
            }

            // place near totals
            if (!Schema::hasColumn('invoices', 'finance_notes')) {
                $table->text('finance_notes')
                      ->nullable()
                      ->after('net_total')
                      ->comment('Internal finance notes for this invoice');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'customer_zipcode')) {
                $table->dropIndex('invoices_customer_zipcode_idx');
                $table->dropColumn('customer_zipcode');
            }
            if (Schema::hasColumn('invoices', 'sac_number')) {
                $table->dropColumn('sac_number');
            }
            if (Schema::hasColumn('invoices', 'finance_notes')) {
                $table->dropColumn('finance_notes');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add new columns
        Schema::table('pricing_masters', function (Blueprint $table) {
            // New fields
            $table->enum('custom_pricing_type', ['fixed', 'variable'])
                  ->nullable()
                  ->after('pricing_type');

            $table->foreignId('customer_id')
                  ->nullable()
                  ->constrained('companies')
                  ->nullOnDelete()
                  ->after('custom_pricing_type');
        });

        // Make existing fields nullable
        // NOTE: requires doctrine/dbal for ->change()
        Schema::table('pricing_masters', function (Blueprint $table) {
            $table->foreignId('industry_vertical_id')->nullable()->change();
            $table->foreignId('department_id')->nullable()->change();
            $table->foreignId('service_offering_id')->nullable()->change();
            $table->foreignId('unit_of_measurement_id')->nullable()->change();
            $table->foreignId('description_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Revert "nullable" on existing fields (ensure no NULLs exist before rolling back)
        Schema::table('pricing_masters', function (Blueprint $table) {
            $table->foreignId('industry_vertical_id')->nullable(false)->change();
            $table->foreignId('department_id')->nullable(false)->change();
            $table->foreignId('service_offering_id')->nullable(false)->change();
            $table->foreignId('unit_of_measurement_id')->nullable(false)->change();
            $table->foreignId('description_id')->nullable(false)->change();
        });

        // Drop new columns
        Schema::table('pricing_masters', function (Blueprint $table) {
            $table->dropConstrainedForeignId('customer_id'); // drops FK & column
            $table->dropColumn('custom_pricing_type');
        });
    }
};

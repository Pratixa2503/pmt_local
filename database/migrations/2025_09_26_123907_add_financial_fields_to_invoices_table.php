<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // $table->string('billing_month', 7)->after('project_id');
            // $table->string('status', 50)->default('draft')->after('billing_month');

            // $table->string('currency_name')->nullable()->after('status');
            // $table->string('currency_symbol', 10)->nullable()->after('currency_name');

             $table->decimal('subtotal', 14, 2)->default(0)->after('currency_symbol');
             $table->decimal('discount', 14, 2)->default(0)->after('subtotal');
             $table->decimal('total', 14, 2)->default(0)->after('discount');

            // $table->unsignedBigInteger('created_by')->nullable()->after('total');
            // $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            // $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                // 'billing_month',
                // 'status',
                // 'currency_name',
                // 'currency_symbol',
                'subtotal',
                'discount',
                'total',
                // 'created_by',
                // 'updated_by',
            ]);
        });
    }
};

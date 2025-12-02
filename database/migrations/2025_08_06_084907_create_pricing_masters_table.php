<?php

// database/migrations/2025_08_08_000000_create_pricing_masters_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pricing_masters', function (Blueprint $table) {
            $table->id();

            $table->enum('pricing_type', ['static', 'custom'])->default('static');

            // Foreign keys as unsignedBigInteger (index them; add FK if those tables exist)
            $table->unsignedBigInteger('industry_vertical_id')->index();
            $table->unsignedBigInteger('department_id')->index();
            $table->unsignedBigInteger('service_offering_id')->index();
            $table->unsignedBigInteger('unit_of_measurement_id')->index();
            $table->unsignedBigInteger('description_id')->index();

            $table->unsignedBigInteger('currency_id')->index();

            $table->decimal('rate', 12, 2);

            // Custom fields (nullable for 'static')
            $table->decimal('project_management_cost', 12, 2)->nullable();
            $table->decimal('vendor_cost', 12, 2)->nullable();
            $table->decimal('infrastructure_cost', 12, 2)->nullable();
            $table->decimal('overhead_percentage', 5, 2)->nullable(); // 0-100
            $table->decimal('margin_percentage', 5, 2)->nullable();   // 0-100
            $table->decimal('volume', 12, 2)->nullable();
            $table->decimal('volume_based_discount', 12, 2)->nullable(); // +/- allowed
            $table->decimal('conversion_rate', 12, 4)->nullable();

            $table->string('name');
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pricing_masters');
    }
};

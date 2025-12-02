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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Basics
            $table->string('project_name');
            $table->text('description');

            // Timeline & Recurrence
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_type', ['weekly', 'monthly', 'yearly'])->nullable();
            $table->date('start_date');
            $table->date('end_date');

            // Foreigns
           $table->foreignId('customer_id')
      ->constrained('companies')   // <-- point to the real table
      ->cascadeOnUpdate()
      ->restrictOnDelete();

            // Configuration (masters)
            $table->foreignId('project_type_id')->constrained('project_types')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('pricing_id')->constrained('pricing_masters')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('input_format_id')->constrained('input_output_formats')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('output_format_id')->constrained('input_output_formats')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('mode_of_delivery_id')->constrained('mode_of_deliveries')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('frequency_of_delivery_id')->constrained('project_delivery_frequencies')->cascadeOnUpdate()->restrictOnDelete();

            // Status & Priority
            $table->foreignId('project_priority_id')->constrained('project_priorities')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('project_status_id')->constrained('project_statuses')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

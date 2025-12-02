<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('po_numbers', function (Blueprint $table) {
            $table->id();

            // Customer (companies)
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('companies')->cascadeOnUpdate()->restrictOnDelete();

            // Project (projects)
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnUpdate()->restrictOnDelete();

            // Sub Project (projects) - optional
            $table->unsignedBigInteger('sub_project_id')->nullable();
            $table->foreign('sub_project_id')->references('id')->on('projects')->cascadeOnUpdate()->nullOnDelete();

            // Dates
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // PO Number
            $table->string('po_number', 191);

            // Status (1=Active, 0=Inactive)
            $table->tinyInteger('status')->default(1);

            // Auditing
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Prevent duplicates per (project, sub_project, po_number)
            $table->unique(['project_id', 'sub_project_id', 'po_number'], 'uniq_project_sub_po');

            // Helpful indexes
            $table->index(['customer_id']);
            $table->index(['project_id']);
            $table->index(['sub_project_id']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('po_numbers');
    }
};

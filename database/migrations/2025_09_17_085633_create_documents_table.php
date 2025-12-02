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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
             $table->foreignId('customer_id')->nullable()
            ->constrained('companies')   // <-- point to the real table
            ->cascadeOnUpdate()
            ->restrictOnDelete();
            $table->string('contact_no')->nullable();
            $table->text('description')->nullable();
            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('industry_vertical_id')->nullable()->constrained('industry_verticals')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->cascadeOnUpdate()->restrictOnDelete();
            $table->tinyInteger('status')
                  ->default(1)
                  ->comment('1 = Active, 0 = Inactive');
            $table->string('file_path')->nullable(); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

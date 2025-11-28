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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_master_id')
                  ->constrained('pricing_masters')
                  ->onDelete('cascade');

            $table->tinyInteger('note_type')->comment('1 = Approve, 2 = Reject,3=Action Required');
            $table->decimal('price', 15, 2)->nullable();
            $table->text('description')->nullable();

            $table->foreignId('approve_rejected_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->foreignId('create_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();      

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};

<?php
// database/migrations/2025_08_08_000001_create_pricing_master_skill_lines_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pricing_master_skill_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pricing_master_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->integer('average_handling_time'); // minutes

            $table->timestamps();

            $table->unique(['pricing_master_id','skill_id']);
            $table->foreign('pricing_master_id')->references('id')->on('pricing_masters')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pricing_master_skill_lines');
    }
};

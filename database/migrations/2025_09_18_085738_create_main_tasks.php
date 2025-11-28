<?php
// database/migrations/2025_09_18_000000_create_main_tasks_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('main_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('status')->default(1); // 1 = active
            $table->timestamps();
        });
        
    }

    public function down(): void {
        Schema::dropIfExists('main_tasks');
    }
};

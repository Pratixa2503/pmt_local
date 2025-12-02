<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('service_offering_id')->nullable()->after('id');
            
            // Optional: if you want to define a foreign key relationship
            $table->foreign('service_offering_id')
                  ->references('id')
                  ->on('service_offerings')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['service_offering_id']);
            $table->dropColumn('service_offering_id');
        });
    }
};

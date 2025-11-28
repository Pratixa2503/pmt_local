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
         Schema::table('skill_masters', function (Blueprint $table) {
            $table->dropColumn('average_handling_time');
            $table->unsignedTinyInteger('status')->default(1)->after('ctc')->comment('1=Active, 0=Inactive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skill_masters', function (Blueprint $table) {
            $table->string('average_handling_time')->nullable();
            $table->dropColumn('status');
        });
    }
};

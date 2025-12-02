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
        Schema::table('project_member_assignments', function (Blueprint $table) {
            $table->date('startdate')->nullable()->after('id'); // add after id or any column you prefer
            $table->date('enddate')->nullable()->after('startdate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_member_assignments', function (Blueprint $table) {
            $table->dropColumn(['startdate', 'enddate']);
        });
    }
};

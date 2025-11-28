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
        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('timeline_alert')->default(false)->after('file_path');
            $table->integer('alert_days')->nullable()->after('timeline_alert');
            $table->string('timeline_alert_file')->nullable()->after('alert_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['timeline_alert', 'alert_days', 'timeline_alert_file']);
        });
    }
};

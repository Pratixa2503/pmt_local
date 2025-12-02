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
        Schema::table('pricing_masters', function (Blueprint $table) {
            $table->text('modification_notes')->nullable()->after('approval_note');
            $table->text('modification_parameter')->nullable()->after('modification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_masters', function (Blueprint $table) {
            $table->dropColumn(['modification_notes', 'modification_parameter']);
        });
    }
};

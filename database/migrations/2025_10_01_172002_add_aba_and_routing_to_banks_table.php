<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->string('aba_number', 20)->nullable()->after('ifsc_code')
                  ->comment('US ABA routing (9 digits)');
            $table->string('routing_number', 20)->nullable()->after('aba_number')
                  ->comment('US routing number (usually same as ABA)');
            $table->index('aba_number');
            $table->index('routing_number');
        });
    }

    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropIndex(['aba_number']);
            $table->dropIndex(['routing_number']);
            $table->dropColumn(['aba_number', 'routing_number']);
        });
    }
};

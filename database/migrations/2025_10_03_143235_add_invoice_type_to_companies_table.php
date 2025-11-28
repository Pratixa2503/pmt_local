<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // adjust ->after('...') to a real column in your table if you want ordering
            $table->unsignedTinyInteger('invoice_type')
                  ->default(1)
                  ->comment('1 = India, 2 = US')
                  ->after('company_type'); 
        });

        // Backfill existing rows safely
        DB::table('companies')->whereNull('invoice_type')->update(['invoice_type' => 1]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('invoice_type');
        });
    }
};

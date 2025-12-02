<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice_formats', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_formats', 'status')) {
                $table->tinyInteger('status')->default(1)->after('name');
            }
            if (!Schema::hasColumn('invoice_formats', 'deleted_at')) {
                $table->softDeletes(); // adds nullable deleted_at TIMESTAMP
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoice_formats', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_formats', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('invoice_formats', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};

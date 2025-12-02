<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // adjust `after()` to a sensible column in your schema (e.g., 'customer_id')
            $table->unsignedBigInteger('industry_vertical_id')->nullable()->after('customer_id');

            // index + FK (assumes table `industry_verticals` with PK `id`)
            $table->index('industry_vertical_id', 'projects_industry_vertical_id_idx');
            $table->foreign('industry_vertical_id', 'projects_industry_vertical_id_fk')
                  ->references('id')->on('industry_verticals')
                  ->onDelete('set null'); // keeps projects if a vertical is deleted
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // drop FK and index before dropping the column
            $table->dropForeign('projects_industry_vertical_id_fk');
            $table->dropIndex('projects_industry_vertical_id_idx');
            $table->dropColumn('industry_vertical_id');
        });
    }
};

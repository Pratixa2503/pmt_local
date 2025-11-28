<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Add nullable parent_id
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');

            // Add self-referencing foreign key
            $table->foreign('parent_id')
                ->references('id')
                ->on('projects')
                ->onDelete('set null'); // if parent deleted, child.parent_id becomes NULL
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};

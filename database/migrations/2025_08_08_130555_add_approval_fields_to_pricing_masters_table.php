<?php

// database/migrations/2025_08_08_120000_add_approval_fields_to_pricing_masters_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pricing_masters', function (Blueprint $table) {
            // $table->unsignedBigInteger('created_by')->nullable()->after('status');
            // $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            //$table->unsignedBigInteger('approved_by')->nullable()->after('updated_by');

            // $table->enum('approval_status', ['draft','pending','approved','rejected'])
            //     ->default('draft')->after('approved_by');
            // $table->timestamp('submitted_at')->nullable()->after('approval_status');
            // $table->timestamp('approved_at')->nullable()->after('submitted_at');
            // $table->text('approval_note')->nullable()->after('approved_at');

            // // Short index name to avoid MySQL limit
            // $table->index(
            //     ['created_by','updated_by','approved_by','approval_status'],
            //     'pricing_masters_approval_idx'
            // );

            // $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
           // $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('pricing_masters', function (Blueprint $table) {
            // drop FKs first if added
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['approved_by']);

            $table->dropIndex(['created_by','updated_by','approved_by','approval_status']);

            $table->dropColumn([
                'created_by','updated_by','approved_by',
                'approval_status','submitted_at','approved_at','approval_note'
            ]);
        });
    }
};

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
        Schema::table('project_intakes', function (Blueprint $table) {
            Schema::table('project_intakes', function (Blueprint $table) {
                // new text fields
                if (!Schema::hasColumn('project_intakes', 'client_name'))
                    $table->string('client_name')->nullable()->after('parent_id');

                if (!Schema::hasColumn('project_intakes', 'fb_customer_name'))
                    $table->string('fb_customer_name')->nullable()->after('fb_date_received');

                // dates
                if (!Schema::hasColumn('project_intakes', 'delivered_date'))
                    $table->date('delivered_date')->nullable()->after('request_received_date');

                if (!Schema::hasColumn('project_intakes', 'query_raised_date'))
                    $table->date('query_raised_date')->nullable()->after('query_status_id');

                if (!Schema::hasColumn('project_intakes', 'query_resolved_date'))
                    $table->date('query_resolved_date')->nullable()->after('query_raised_date');

                if (!Schema::hasColumn('project_intakes', 'fb_feedback_completion_date'))
                    $table->date('fb_feedback_completion_date')->nullable()->after('fb_sb_response');

                if (!Schema::hasColumn('project_intakes', 'fb_date_received'))
                    $table->date('fb_date_received')->nullable()->after('invoice_format_id');

                // feedback
                if (!Schema::hasColumn('project_intakes', 'fb_category_id'))
                    $table->unsignedBigInteger('fb_category_id')->nullable()->after('fb_customer_name');

                if (!Schema::hasColumn('project_intakes', 'fb_customer_comments'))
                    $table->text('fb_customer_comments')->nullable()->after('fb_category_id');

                if (!Schema::hasColumn('project_intakes', 'fb_sb_response'))
                    $table->text('fb_sb_response')->nullable()->after('fb_customer_comments');

                // misc already in controller but may be missing
                if (!Schema::hasColumn('project_intakes', 'billing_month'))
                    $table->string('billing_month', 7)->nullable()->after('feedback_completion_date'); // YYYY-MM

                if (!Schema::hasColumn('project_intakes', 'non_english_pages'))
                    $table->unsignedInteger('non_english_pages')->default(0)->after('billing_month');

                if (!Schema::hasColumn('project_intakes', 'invoice_format_id'))
                    $table->unsignedBigInteger('invoice_format_id')->nullable()->after('non_english_pages');

                if (!Schema::hasColumn('project_intakes', 'cost_usd'))
                    $table->decimal('cost_usd', 12, 2)->default(0)->after('billing_month');

                if (!Schema::hasColumn('project_intakes', 'type_of_lease_id'))
                    $table->unsignedBigInteger('type_of_lease_id')->nullable()->after('cost_usd');

                if (!Schema::hasColumn('project_intakes', 'type_of_work_id'))
                    $table->unsignedBigInteger('type_of_work_id')->nullable()->after('type_of_lease_id');

                if (!Schema::hasColumn('project_intakes', 'language_code'))
                    $table->string('language_code', 8)->nullable()->after('type_of_work_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_intakes', function (Blueprint $table) {
            $table->dropColumn([
                'client_name',
                'delivered_date',
                'query_raised_date',
                'query_resolved_date',
                'fb_feedback_completion_date',
                'billing_month',
                'non_english_pages',
                'invoice_format_id',
                'fb_date_received',
                'fb_customer_name',
                'fb_category_id',
                'fb_customer_comments',
                'fb_sb_response',
                'cost_usd',
                'type_of_lease_id',
                'type_of_work_id',
                'language_code',
            ]);
        });
    }
};

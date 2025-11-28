<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_intakes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parent_id')->nullable()->index();

            $table->string('project_name');
            $table->unsignedBigInteger('property_manager_id')->nullable()->index(); // changed to unsignedBigInteger
            $table->date('request_received_date')->nullable();

            $table->unsignedBigInteger('priority_id')->nullable()->index();
            $table->unsignedBigInteger('status_master_id')->nullable()->index();

            $table->string('property_id')->nullable();
            $table->string('property_name')->nullable();
            $table->string('tenant_name')->nullable();
            $table->string('tenant_or_lease_id')->nullable();
            $table->text('premises_address')->nullable();

            $table->unsignedInteger('no_of_documents')->default(0);
            $table->string('pdf_names')->nullable();

            $table->text('sb_queries')->nullable();
            $table->string('type_of_queries')->nullable();
            $table->text('client_response')->nullable();

            $table->unsignedBigInteger('query_status_id')->nullable()->index();
            $table->unsignedBigInteger('abstractor_id')->nullable()->index();
            $table->date('abstraction_start_date')->nullable();
            $table->date('abstract_completion_date')->nullable();

            $table->unsignedBigInteger('reviewer_id')->nullable()->index();
            $table->date('review_completion_date')->nullable();

            $table->unsignedBigInteger('sense_check_ddr_id')->nullable()->index();
            $table->date('sense_check_completion_date')->nullable();

            $table->date('proposed_delivery_date')->nullable();
            $table->date('actual_delivered_date')->nullable();

            $table->date('feedback_received_date')->nullable();
            $table->date('feedback_completion_date')->nullable();
            $table->date('fb_date_received')->nullable();
            $table->string('fb_customer_name')->nullable();
            $table->unsignedBigInteger('fb_category_id')->nullable()->index();
            $table->text('fb_customer_comments')->nullable();
            $table->text('fb_sb_response')->nullable();
            $table->string('fb_feedback')->nullable();

            $table->string('billing_month', 7)->nullable(); // YYYY-MM
            $table->decimal('cost_usd', 12, 2)->default(0);

            $table->unsignedBigInteger('type_of_lease_id')->nullable()->index();
            $table->unsignedBigInteger('type_of_work_id')->nullable()->index();

            $table->string('language_code', 10)->nullable()->index();
            $table->unsignedInteger('non_english_pages')->default(0);

            $table->unsignedBigInteger('invoice_format_id')->nullable()->index();

            $table->timestamps();

            // Optional FKs (uncomment when ready)
            // $table->foreign('property_manager_id')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('priority_id')->references('id')->on('priorities')->nullOnDelete();
            // $table->foreign('status_master_id')->references('id')->on('statuses')->nullOnDelete();
            // $table->foreign('query_status_id')->references('id')->on('query_statuses')->nullOnDelete();
            // $table->foreign('abstractor_id')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('reviewer_id')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('sense_check_ddr_id')->references('id')->on('sense_ddrs')->nullOnDelete();
            // $table->foreign('fb_category_id')->references('id')->on('feedback_categories')->nullOnDelete();
            // $table->foreign('type_of_lease_id')->references('id')->on('lease_types')->nullOnDelete();
            // $table->foreign('type_of_work_id')->references('id')->on('work_types')->nullOnDelete();
            // $table->foreign('invoice_format_id')->references('id')->on('invoice_formats')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_intakes');
    }
};

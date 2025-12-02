<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProjectIntake extends Model
{
   
    protected $table = 'project_intakes';
   
    protected $fillable = [
        'project_manager',
        'parent_id',
        'project_name',
        'property_manager_id',
        'request_received_date',
        'priority_id',
        'status_master_id',
        'property_id',
        'property_name',
        'tenant_name',
        'tenant_or_lease_id',
        'premises_address',
        'no_of_documents',
        'pdf_names',
        'sb_queries',
        'type_of_queries',
        'client_response',
        'query_status_id',
        'abstractor_id',
        'abstraction_start_date',
        'abstract_completion_date',
        'reviewer_id',
        'review_completion_date',
        'sense_check_ddr_id',
        'sense_check_completion_date',
        'proposed_delivery_date',
        'actual_delivered_date',
        'feedback_received_date',
        'feedback_completion_date',
        'fb_date_received',
        'fb_customer_name',
        'fb_category_id',
        'fb_customer_comments',
        'fb_sb_response',
        'fb_feedback',
        'billing_month',
        'cost_usd',
        'type_of_lease_id',
        'type_of_work_id',
        'language_code',
        'non_english_pages',
        'invoice_format_id',
        'suite_id','review_start_date', 'sense_check_start_date','abstract_notified_on','review_notified_on','sense_notified_on'
    ];

    public function queries()
    {
        return $this->hasMany(IntakeQuery::class, 'intake_id');
    }
}

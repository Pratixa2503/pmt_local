<?php
// app/Http/Requests/StoreProjectIntakeRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectIntakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Array rules for multi-row submission
        return [
            'parent_id'              => ['nullable', 'integer'],

            // 'project_name'           => ['required', 'array'],
            // 'project_name.*'         => ['nullable', 'string', 'max:255'],

            'property_manager_id'    => ['array'],
            'property_manager_id.*'  => ['nullable', 'string', 'max:255'],

            'request_received_date'  => ['array'],
            'request_received_date.*'=> ['nullable', 'date_format:Y-m-d'],

            'priority_id'            => ['array'],
            //'priority_id.*'          => ['nullable', 'integer', 'exists:priorities,id'],

            'status_master'          => ['array'],
          //  'status_master.*'        => ['nullable', 'integer', 'exists:statuses,id'],

            'property_id'            => ['array'],
           // 'property_id.*'          => ['nullable', 'string', 'max:255'],

            'property_name'          => ['array'],
           // 'property_name.*'        => ['nullable', 'string', 'max:255'],

            'tenant_name'            => ['array'],
          //  'tenant_name.*'          => ['nullable', 'string', 'max:255'],

            'tenant_or_lease_id'     => ['array'],
           // 'tenant_or_lease_id.*'   => ['nullable', 'string', 'max:255'],

            'premises_address'       => ['array'],
            //'premises_address.*'     => ['nullable', 'string'],

            'no_of_documents'        => ['array'],
           // 'no_of_documents.*'      => ['nullable', 'integer', 'min:0'],

            'pdf_names'              => ['array'],
            //'pdf_names.*'            => ['nullable', 'string', 'max:255'],

            'sb_queries'             => ['array'],
            //'sb_queries.*'           => ['nullable', 'string'],

            'type_of_queries'        => ['array'],
            //'type_of_queries.*'      => ['nullable', 'string', 'max:255'],

            'client_response'        => ['array'],
            //'client_response.*'      => ['nullable', 'string'],

            'query_status'           => ['array'],
            //'query_status.*'         => ['nullable', 'integer', 'exists:query_statuses,id'],

            'abstractor'             => ['array'],
            //'abstractor.*'           => ['nullable', 'integer', 'exists:users,id'],

            'abstraction_start_date'   => ['array'],
           // 'abstraction_start_date.*' => ['nullable', 'date_format:Y-m-d'],

            'abstract_completion_date'   => ['array'],
           // 'abstract_completion_date.*' => ['nullable', 'date_format:Y-m-d'],

            'reviewer'               => ['array'],
          //  'reviewer.*'             => ['nullable', 'integer', 'exists:users,id'],

            'review_completion_date'   => ['array'],
           // 'review_completion_date.*' => ['nullable', 'date_format:Y-m-d'],

            'sense_check_ddr'        => ['array'],
          //  'sense_check_ddr.*'      => ['nullable', 'integer', 'exists:sense_ddrs,id'],

            'sense_check_completion_date'   => ['array'],
          //  'sense_check_completion_date.*' => ['nullable', 'date_format:Y-m-d'],

            'proposed_delivery_date'   => ['array'],
          //  'proposed_delivery_date.*' => ['nullable', 'date_format:Y-m-d'],

            'actual_delivered_date'   => ['array'],
           // 'actual_delivered_date.*' => ['nullable', 'date_format:Y-m-d'],

            'feedback_received_date'   => ['array'],
          //  'feedback_received_date.*' => ['nullable', 'date_format:Y-m-d'],

            'feedback_completion_date'   => ['array'],
           // 'feedback_completion_date.*' => ['nullable', 'date_format:Y-m-d'],

            'fb_date_received'        => ['array'],
           // 'fb_date_received.*'      => ['nullable', 'date_format:Y-m-d'],

            'fb_customer_name'        => ['array'],
           // 'fb_customer_name.*'      => ['nullable', 'string', 'max:255'],

            'fb_category_id'          => ['array'],
           // 'fb_category_id.*'        => ['nullable', 'integer', 'exists:feedback_categories,id'],

            'fb_customer_comments'    => ['array'],
           // 'fb_customer_comments.*'  => ['nullable', 'string'],

            'fb_sb_response'          => ['array'],
           // 'fb_sb_response.*'        => ['nullable', 'string'],

            'fb_feedback'             => ['array'],
          //  'fb_feedback.*'           => ['nullable', 'string', 'max:255'],

            'billing_month'           => ['array'],
          //  'billing_month.*'         => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],

            'cost_usd'                => ['array'],
          //  'cost_usd.*'              => ['nullable', 'numeric', 'min:0'],

            'type_of_lease'           => ['array'],
          //  'type_of_lease.*'         => ['nullable', 'integer', 'exists:lease_types,id'],

            'type_of_work'            => ['array'],
          //  'type_of_work.*'          => ['nullable', 'integer', 'exists:work_types,id'],

            'language'                => ['array'],
          //  'language.*'              => ['nullable', 'string', 'max:10'],

            'non_english_pages'       => ['array'],
         //   'non_english_pages.*'     => ['nullable', 'integer', 'min:0'],

            'invoice_format'          => ['array'],
          //  'invoice_format.*'        => ['nullable', 'integer', 'exists:invoice_formats,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'billing_month.*.regex' => 'Billing Month must be in YYYY-MM format.',
            'request_received_date.*.date_format' => 'Dates must be in YYYY-MM-DD format.',
            // Add more user-friendly messages as you like
        ];
    }
}

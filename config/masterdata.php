<?php

return [
    'project_types' => [
        'model'     => \App\Models\ProjectType::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'departments' => [
        'model'     => \App\Models\Department::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'project_priorities' => [
        'model'     => \App\Models\ProjectPriority::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'project_statuses' => [
        'model'     => \App\Models\ProjectStatus::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'frequencies_of_delivery' => [
        'model'     => \App\Models\ProjectDeliveryFrequency::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'modes_of_delivery' => [
        'model'     => \App\Models\ModeOfDelivery::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'input_output_formats' => [
        'model'     => \App\Models\InputOutputFormat::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'customers' => [ // Company
        'model'     => \App\Models\Company::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],

    // Intake masters used by project_intakes
    'status' => [ // IntakeStatus
        'model'     => \App\Models\IntakeStatus::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'intake_query' => [ // IntakeQueryType
        'model'     => \App\Models\IntakeQueryType::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'lease_types' => [
        'model'     => \App\Models\IntakeLeaseType::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'work_types' => [
        'model'     => \App\Models\IntakeWorkType::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'languages' => [
        'model'     => \App\Models\IntakeLanguage::class,
        // prefer code if present; fall back to name
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'query_status' => [
        'model'     => \App\Models\QueryStatus::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'feedback_categories' => [
        'model'     => \App\Models\FeedbackCategory::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
    'invoice_formats' => [
        'model'     => \App\Models\InvoiceFormat::class,
        'unique_by' => ['name'],
        'fill'      => ['name' => null, 'status' => 1],
    ],
];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company Information (used for invoices & billing)
    |--------------------------------------------------------------------------
    |
    | Store all static values related to invoices. You can update them
    | in one place instead of hardcoding them in controllers or views.
    |
    */

    'name'        => env('COMPANY_NAME', 'Springbord Systems Private Limited'),
    'address'     => env(
        'COMPANY_ADDRESS',
        "12th Floor, Phase - II, TICEL BIO PARK,\nModel No.: 1203 No 5, CSIR Road, Taramani,\nChennai - 600 013 Tamil Nadu, India. Tel: +91-044-2225-9700\nGSTIN: 33AAWCS8726L1ZH"
    ),
    'signatory'   => env('COMPANY_SIGNATORY', 'Ranjith Kumar R'),
    'logo_url' => env('COMPANY_LOGO_URL', 'storage/img/logo.png'),
    'brand' => [
        'primary' => env('COMPANY_BRAND_PRIMARY', '#0d6efd'),
        'accent'  => env('COMPANY_BRAND_ACCENT',  '#f1f5ff'),
        'text'    => env('COMPANY_BRAND_TEXT',    '#333333'),
        'muted'   => env('COMPANY_BRAND_MUTED',   '#6c757d'),
        'border'  => env('COMPANY_BRAND_BORDER',  '#e5e7eb'),
    ],
    'pan'         => env('COMPANY_PAN', 'AAWCS8726L'),
    'gst_no'      => env('COMPANY_GST_NO', '33AAWCS8726L1ZH'),
    'reference_no'=> env('COMPANY_REFERENCE_NO', '82712804'),
    'lut_no'      => env('COMPANY_LUT_NO', 'AD330425013610P - SUPPLY MEANT FOR EXPORT UNDER LETTER OF UNDERTAKING (LUT) WITHOUT PAYMENT OF INTEGRATED TAX
'),
    'iec_code'    => env('COMPANY_IEC_CODE', '0416903703'),
    'description' => env('COMPANY_DESCRIPTION', 'Invoice for professional services'),
    'sac_code'    => env('COMPANY_SAC_CODE', '9983'),
    'gst_percent' => env('COMPANY_GST_PERCENT', 18),

];

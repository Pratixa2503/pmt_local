<?php

namespace App\Imports;

use App\Models\ProjectIntake;
use App\Models\IntakeLanguage;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\ProjectStatus; 
use App\Models\IntakeLeaseType; // at the top with other uses
use App\Models\IntakeWorkType;
use App\Models\IntakeQueryType;
use App\Models\IntakeStatus;
use App\Models\QueryStatus;
use App\Models\InvoiceFormat;
use App\Models\FeedbackCategory;
use App\Models\ServiceOffering;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Mail;
use App\Mail\AssignmentSummaryMail;
use App\Models\Project;

class ProjectIntakesImport implements ToCollection, WithHeadingRow
{
    protected bool $skipFirstRow;
    protected ?int $parentProjectId;
    // simple runtime caches
    protected array $langCodeByName = [];   // "English" => "en"
    protected array $userIdByEmail  = [];   // "john@acme.com" => 42
    protected array $projectStatusIdByName = [];
    protected array $leaseTypeIdByName = []; // "Gross Lease" => 5
    protected array $workTypeIdByName = [];
    protected array $intakeQueryNameCache = [];
    protected array $queryStatusIdByName = [];
    protected array $invoiceFormatIdByName = [];
    protected array $fbCategoryIdByName = [];

    public function __construct(?int $parentProjectId = null,bool $skipFirstRow = false)
    {
         $this->parentProjectId = $parentProjectId;
        $this->skipFirstRow = $skipFirstRow;
    }

    // public function collection(Collection $rows)
    // {
    //     $rows = $rows->filter(fn($r) => collect($r)->filter()->isNotEmpty())->values();
       
    //     if ($this->skipFirstRow) $rows = $rows->skip(1)->values();

    //     DB::transaction(function () use ($rows) {
    //         foreach ($rows as $row) {
              
    //             $data = array_map(fn($v) => is_string($v) ? trim($v) : $v, $row->toArray());
               
    //             // --- map language by NAME only (CSV has only names) ---
    //             $languageName = $this->firstValue($data, ['language', 'language_name', 'lang']); // try multiple keys safely
    //             $languageCode = $this->resolveLanguageCodeByName($languageName); // returns code (creates row if missing)

    //             // --- users by email (abstractor/reviewer/sense check DDR) ---
    //             $abstractorEmail = $this->firstValue($data, ['abstractor', 'abstractor_email']);
    //             $reviewerEmail   = $this->firstValue($data, ['reviewer', 'reviewer_email']);
    //             $senseCheckEmail = $this->firstValue($data, ['sense_check_ddr', 'sense_check_ddr_email', 'ddr_email', 'sense_check_email']);
               

    //             $abstractorId = $this->resolveUserIdByEmail($abstractorEmail);
    //             $reviewerId   = $this->resolveUserIdByEmail($reviewerEmail);
    //             $senseCheckId = $this->resolveUserIdByEmail($senseCheckEmail);
    //             $pmEmail = $this->firstValue($data, ['property_manager_email','pm_email','property_manager']); // if "property_manager" column holds the email
    //             $propertyManagerId = $this->resolveUserIdByEmail($pmEmail);
    //             // --- dates ---
    //             $requestReceived    = $this->toDbDate($data['request_received_date'] ?? null);
    //             $deliveredDate      = $this->toDbDate($data['delivered_date'] ?? null);
    //             $queryRaised        = $this->toDbDate($data['query_raised_date'] ?? null);
    //             $queryResolved      = $this->toDbDate($data['query_resolved_date'] ?? null);
    //             $absStart           = $this->toDbDate($data['abstraction_start_date'] ?? null);
    //             $absComplete        = $this->toDbDate($data['abstract_completion_date'] ?? null);
    //             $reviewStart   = $this->toDbDate($data['review_start_date'] ?? null);
    //             $reviewComplete     = $this->toDbDate($data['review_completion_date'] ?? null);
    //             $senseStart      = $this->toDbDate($data['sense_check_ddr_start_date'] ?? null);
    //             $senseComplete      = $this->toDbDate($data['sense_check_completion_date'] ?? null);
    //             $proposedDelivery   = $this->toDbDate($data['proposed_delivery_date'] ?? null);
    //             $actualDelivered    = $this->toDbDate($data['actual_delivered_date'] ?? null);
    //             $fbReceived         = $this->toDbDate($data['feedback_received_date'] ?? null);
    //             $fbCompletion       = $this->toDbDate($data['feedback_completion_date'] ?? null);
    //             $fbDateReceived     = $this->toDbDate($data['fb_date_received'] ?? null);
    //             $fbFbCompletionDate = $this->toDbDate($data['fb_feedback_completion_date'] ?? null);

    //             $billingMonth = $this->toBillingMonth($data['billing_month'] ?? null);
    //             $statusName      = $this->firstValue($data, ['status','project_status','status_name']);
    //             $statusMasterId  = $this->resolveProjectStatusIdByName($statusName);
    //             $leaseTypeName = $this->firstValue($data, ['type_of_lease','lease_type','lease']); // try multiple headers
    //             $typeOfLeaseId = $this->resolveLeaseTypeIdByName($leaseTypeName);
    //             $workTypeName = $this->firstValue($data, ['type_of_work','work_type','work']); // try multiple headers
    //             $typeOfWorkId = $this->resolveWorkTypeIdByName($workTypeName);
                
    //             $typeOfQueriesRaw  = $this->firstValue($data, ['type_of_queries','query_type','intake_query']);
    //             $typeOfQueriesName = $this->resolveIntakeQueryName($typeOfQueriesRaw);
    //             $queryStatusName = $this->firstValue($data, ['query_status','query_status_name']);
    //             $queryStatusId = $this->resolveQueryStatusIdByName($queryStatusName);
              
    //             $invoiceFormatName = $this->firstValue($data, ['invoice_format','invoice_method']);
    //             $invoiceFormatId = is_numeric($data['invoice_format_id'] ?? null) ? (int)$data['invoice_format_id'] : $this->resolveInvoiceFormatIdByName($invoiceFormatName);

    //             $fbCategoryName = $this->firstValue($data, ['fb_category', 'feedback_category', 'fb_category_name', 'category_of_fb']);
    //             $fbCategoryId = $this->resolveFeedbackCategoryIdByName($fbCategoryName);
               
    //             ProjectIntake::create([
    //                 'parent_id'                    => $this->parentProjectId,
    //                 'property_manager_id'          => $propertyManagerId,
    //                 'request_received_date'        => $requestReceived,
    //                 'delivered_date'               => $deliveredDate,
    //                 'priority_id'                  => $data['priority'] ?? null,
    //                 'status_master_id'             => $statusMasterId ?? null,
    //                 'property_id'                  => $data['property_id'] ?? null,
    //                 'property_name'                => $data['property_name'] ?? null,
    //                 'tenant_name'                  => $data['tenant_name'] ?? null,
    //                 'tenant_or_lease_id'           => $data['tenant_id_lease_id'] ?? null,
    //                 'premises_address'             => $data['premises_address'] ?? null,
    //                 'no_of_documents'              => (int)($data['no_of_documents'] ?? 0),
    //                 'pdf_names'                    => $data['pdf_names'] ?? null,
    //                 'sb_queries'                   => $data['sb_queries'] ?? null,
    //                 'type_of_queries'              => $typeOfQueriesName, // keep text unless you map to FK
    //                 'client_response'              => $data['client_response'] ?? null,
    //                 'query_status_id'                 => $queryStatusId,

    //                 'query_raised_date'            => $queryRaised,
    //                 'query_resolved_date'          => $queryResolved,

    //                 'abstractor_id'                => $abstractorId,
    //                 'abstraction_start_date'       => $absStart,
    //                 'abstract_completion_date'     => $absComplete,

    //                 'reviewer_id'                  => $reviewerId,
    //                 'review_start_date'            => $reviewStart,
    //                 'review_completion_date'       => $reviewComplete,

    //                 'sense_check_ddr_id'           => $senseCheckId,
    //                 'sense_check_start_date'       => $senseStart,
    //                 'sense_check_completion_date'  => $senseComplete,

    //                 'proposed_delivery_date'       => $proposedDelivery,
    //                 'actual_delivered_date'        => $actualDelivered,

    //                 'feedback_received_date'       => $fbReceived,
    //                 'feedback_completion_date'     => $fbCompletion,
    //                 'fb_date_received'             => $fbDateReceived,
    //                 'fb_customer_name'             => $data['fb_customer_name'] ?? null,
    //                 'fb_category_id'               => $fbCategoryId,
    //                 'fb_customer_comments'         => $data['fb_customer_comments'] ?? null,
    //                 'fb_sb_response'               => $data['fb_sb_response'] ?? null,
    //                 'fb_feedback_completion_date'  => $fbFbCompletionDate ?? null,
    //                 'fb_feedback'                  => $data['fb_feedback'] ?? null,

    //                 'billing_month'                => $billingMonth,
    //                 'cost_usd'                     => (float)($data['cost_usd'] ?? 0),

    //                 'type_of_lease_id'             => $typeOfLeaseId ?? null,
    //                 'type_of_work_id'                 => $typeOfWorkId,

    //                 'language_code'                => $languageCode, // store the resolved CODE
    //                 'non_english_pages'            => (int)($data['non_english_pages'] ?? 0),

    //                 'invoice_format_id'            => $invoiceFormatId,
    //                 'suite_id'                     => $data['suite_id'] ?? null
    //             ]);
    //         }
    //     });
    // }
    public function collection(Collection $rows)
    {
        $rows = $rows->filter(fn($r) => collect($r)->filter()->isNotEmpty())->values();
        if ($this->skipFirstRow) $rows = $rows->skip(1)->values();

        // Buckets to count assignments per user for each role
        $assignments = [
            'abstractor' => [],   // user_id => ['count' => int, 'items' => [...]]
            'reviewer'   => [],
            'sense'      => [],
        ];
       
        DB::transaction(function () use ($rows, &$assignments) {
            foreach ($rows as $row) {
                $data = array_map(fn($v) => is_string($v) ? trim($v) : $v, $row->toArray());

                // --- language ---
                $languageName = $this->firstValue($data, ['language', 'language_name', 'lang']);
                $languageCode = $this->resolveLanguageCodeByName($languageName);

                // --- users by email ---
                $abstractorEmail = $this->firstValue($data, ['abstractor', 'abstractor_email']);
                $reviewerEmail   = $this->firstValue($data, ['reviewer', 'reviewer_email']);
                $senseCheckEmail = $this->firstValue($data, ['sense_check_ddr', 'sense_check_ddr_email', 'ddr_email', 'sense_check_email']);
                $pmEmail         = $this->firstValue($data, ['property_manager_email','pm_email','property_manager']);

                $abstractorId = $this->resolveUserIdByEmail($abstractorEmail);
                $reviewerId   = $this->resolveUserIdByEmail($reviewerEmail);
                $senseCheckId = $this->resolveUserIdByEmail($senseCheckEmail);
                $propertyManagerId = $this->resolveUserIdByEmail($pmEmail);

                // --- dates ---
                $requestReceived    = $this->toDbDate($data['request_received_date'] ?? null);
                $deliveredDate      = $this->toDbDate($data['delivered_date'] ?? null);
                $queryRaised        = $this->toDbDate($data['query_raised_date'] ?? null);
                $queryResolved      = $this->toDbDate($data['query_resolved_date'] ?? null);
                $absStart           = $this->toDbDate($data['abstraction_start_date'] ?? null);
                $absComplete        = $this->toDbDate($data['abstract_completion_date'] ?? null);
                $reviewStart        = $this->toDbDate($data['review_start_date'] ?? null);
                $reviewComplete     = $this->toDbDate($data['review_completion_date'] ?? null);
                $senseStart         = $this->toDbDate($data['sense_check_ddr_start_date'] ?? null);
                $senseComplete      = $this->toDbDate($data['sense_check_completion_date'] ?? null);
                $proposedDelivery   = $this->toDbDate($data['proposed_delivery_date'] ?? null);
                $actualDelivered    = $this->toDbDate($data['actual_delivered_date'] ?? null);
                $fbReceived         = $this->toDbDate($data['feedback_received_date'] ?? null);
                $fbCompletion       = $this->toDbDate($data['feedback_completion_date'] ?? null);
                $fbDateReceived     = $this->toDbDate($data['fb_date_received'] ?? null);
                $fbFbCompletionDate = $this->toDbDate($data['fb_feedback_completion_date'] ?? null);

                $billingMonth       = $this->toBillingMonth($data['billing_month'] ?? null);
                $statusName         = $this->firstValue($data, ['status','project_status','status_name']);
                $statusMasterId     = $this->resolveProjectStatusIdByName($statusName);
                $leaseTypeName      = $this->firstValue($data, ['type_of_lease','lease_type','lease']);
                $typeOfLeaseId      = $this->resolveLeaseTypeIdByName($leaseTypeName);
                $workTypeName       = $this->firstValue($data, ['type_of_work','work_type','work']);
                $typeOfWorkId       = $this->resolveWorkTypeIdByName($workTypeName);

                $typeOfQueriesRaw   = $this->firstValue($data, ['type_of_queries','query_type','intake_query']);
                $typeOfQueriesName  = $this->resolveIntakeQueryName($typeOfQueriesRaw);
                $queryStatusName    = $this->firstValue($data, ['query_status','query_status_name']);
                $queryStatusId      = $this->resolveQueryStatusIdByName($queryStatusName);

                $invoiceFormatName  = $this->firstValue($data, ['invoice_format','invoice_method']);
                $invoiceFormatId    = is_numeric($data['invoice_format_id'] ?? null)
                                    ? (int) $data['invoice_format_id']
                                    : $this->resolveInvoiceFormatIdByName($invoiceFormatName);

                $fbCategoryName     = $this->firstValue($data, ['fb_category', 'feedback_category', 'fb_category_name', 'category_of_fb']);
                $fbCategoryId       = $this->resolveFeedbackCategoryIdByName($fbCategoryName);

                // --- create intake ---
                $intake = ProjectIntake::create([
                    'parent_id'                    => $this->parentProjectId,
                    'property_manager_id'          => $propertyManagerId,

                    'request_received_date'        => $requestReceived,
                    'delivered_date'               => $deliveredDate,
                    'priority_id'                  => $data['priority'] ?? null,
                    'status_master_id'             => $statusMasterId ?? null,
                    'property_id'                  => $data['property_id'] ?? null,
                    'property_name'                => $data['property_name'] ?? null,
                    'tenant_name'                  => $data['tenant_name'] ?? null,
                    'tenant_or_lease_id'           => $data['tenant_id_lease_id'] ?? null,
                    'premises_address'             => $data['premises_address'] ?? null,
                    'no_of_documents'              => (int)($data['no_of_documents'] ?? 0),
                    'pdf_names'                    => $data['pdf_names'] ?? null,
                    'sb_queries'                   => $data['sb_queries'] ?? null,
                    'type_of_queries'              => $typeOfQueriesName,
                    'client_response'              => $data['client_response'] ?? null,
                    'query_status_id'              => $queryStatusId,

                    'query_raised_date'            => $queryRaised,
                    'query_resolved_date'          => $queryResolved,

                    'abstractor_id'                => $abstractorId,
                    'abstraction_start_date'       => $absStart,
                    'abstract_completion_date'     => $absComplete,

                    'reviewer_id'                  => $reviewerId,
                    'review_start_date'            => $reviewStart,
                    'review_completion_date'       => $reviewComplete,

                    'sense_check_ddr_id'           => $senseCheckId,
                    'sense_check_start_date'       => $senseStart,
                    'sense_check_completion_date'  => $senseComplete,

                    'proposed_delivery_date'       => $proposedDelivery,
                    'actual_delivered_date'        => $actualDelivered,

                    'feedback_received_date'       => $fbReceived,
                    'feedback_completion_date'     => $fbCompletion,
                    'fb_date_received'             => $fbDateReceived,
                    'fb_customer_name'             => $data['fb_customer_name'] ?? null,
                    'fb_category_id'               => $fbCategoryId,
                    'fb_customer_comments'         => $data['fb_customer_comments'] ?? null,
                    'fb_sb_response'               => $data['fb_sb_response'] ?? null,
                    'fb_feedback_completion_date'  => $fbFbCompletionDate ?? null,
                    'fb_feedback'                  => $data['fb_feedback'] ?? null,

                    'billing_month'                => $billingMonth,
                    'cost_usd'                     => (float)($data['cost_usd'] ?? 0),

                    'type_of_lease_id'             => $typeOfLeaseId ?? null,
                    'type_of_work_id'              => $typeOfWorkId,

                    'language_code'                => $languageCode,
                    'non_english_pages'            => (int)($data['non_english_pages'] ?? 0),

                    'invoice_format_id'            => $invoiceFormatId,
                    'suite_id'                     => $data['suite_id'] ?? null
                ]);
            $projectName = optional(Project::find($this->parentProjectId))->project_name;
            $emailItem = [
                'project_name' => $projectName ?? '(Project)',
                'tenant_id'    => $intake->tenant_or_lease_id,   // << use as Tenant Id
                'property'     => $intake->property_name,
                'tenant'       => $intake->tenant_name,
            ];

            if ($abstractorId) {
                    $assignments['abstractor'][$abstractorId]['count'] =
                        ($assignments['abstractor'][$abstractorId]['count'] ?? 0) + 1;
                    $assignments['abstractor'][$abstractorId]['items'][] = $emailItem;
                }
                if ($reviewerId) {
                    $assignments['reviewer'][$reviewerId]['count'] =
                        ($assignments['reviewer'][$reviewerId]['count'] ?? 0) + 1;
                    $assignments['reviewer'][$reviewerId]['items'][] = $emailItem;
                }
                if ($senseCheckId) {
                    $assignments['sense'][$senseCheckId]['count'] =
                        ($assignments['sense'][$senseCheckId]['count'] ?? 0) + 1;
                    $assignments['sense'][$senseCheckId]['items'][] = $emailItem;
                }
            }
           
            // Ensure emails/notifications only happen if transaction COMMITs:
            DB::afterCommit(function () use ($assignments) {
                $this->dispatchAssignmentEmails($assignments);
            });
        });
    }

    /**
     * Send one email per user per role with the count and list.
     */
    protected function dispatchAssignmentEmails(array $assignments): void
    {
        foreach (['abstractor' => 'Abstractor', 'reviewer' => 'Reviewer', 'sense' => 'Sense Check'] as $key => $roleLabel) {
            foreach ($assignments[$key] as $userId => $payload) {
                $user = User::find($userId);
                //dd($user);
                if (! $user || ! $user->email) continue;
                
                // Queue is recommended; use ->send() if you don't have queues set up.
                Mail::to($user->email)->queue(new AssignmentSummaryMail(
                   
                    role: $roleLabel,
                    count: $payload['count'] ?? 0,
                    items: $payload['items'] ?? [],
                    brandName: 'Springbord',
                    logoUrl:'https://www.springbord.com/_homeassets/logo-primary.DEMX77NU_ZoGfdz.svg',
                    dashboardUrl: route('projects.index') 
                ));
            }
        }
    }
    /** language NAME -> language CODE (creates if missing, status=1) */
    private function resolveLanguageCodeByName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = ucwords(Str::lower(trim($name)));
        $key  = $norm;

        // positive/negative cache
        if (array_key_exists($key, $this->langCodeByName ?? [])) {
            return $this->langCodeByName[$key];
        }

        $id = IntakeLanguage::whereRaw('LOWER(name) = ?', [Str::lower($norm)])->value('id');
        return $this->langCodeByName[$key] = $id ? (int)$id : null;
    }

    /** email -> users.id (case-insensitive); returns null if not found/invalid */
    private function resolveUserIdByEmail(?string $email): ?int
    {
        if (!$email) return null;
        $email = Str::lower(trim($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return null;

        if (isset($this->userIdByEmail[$email])) return $this->userIdByEmail[$email];

        $id = User::whereRaw('LOWER(email) = ?', [$email])->value('id');
        return $this->userIdByEmail[$email] = $id ? (int)$id : null;
    }

    /** get first non-null of multiple possible CSV headings */
    private function firstValue(array $data, array $keys): ?string
    {
        foreach ($keys as $k) if (array_key_exists($k, $data) && $data[$k] !== null && $data[$k] !== '') return (string)$data[$k];
        return null;
    }

    /** robust date parser → 'Y-m-d' or null */
    private function toDbDate($value): ?string
    {
        if ($value === null) return null;
        if (is_string($value)) {
            $t = trim($value);
            if ($t === '' || $t === '?' || $t === '-') return null;
            // keep only leading date part if commentary follows: "05.23.2025: ..."
            $value = preg_replace('/(^[^A-Za-z0-9]*)(.*?)([:;,].*)$/', '$2', $t);
        }

        if ($value instanceof \DateTimeInterface) return $value->format('Y-m-d');

        if (is_numeric($value)) {
            try { return ExcelDate::excelToDateTimeObject((float)$value)->format('Y-m-d'); } catch (\Throwable) {}
        }

        if (!is_string($value)) return null;

        $s = str_replace(['.', '\\', '_'], '/', trim($value));
        $fmts = ['m/d/Y','n/j/Y','m/d/y','n/j/y','Y-m-d','Y/m/d','d/m/Y','j/n/Y','d/m/y','j/n/y'];
        foreach ($fmts as $f) { try { return Carbon::createFromFormat($f, $s)->format('Y-m-d'); } catch (\Throwable) {} }

        try { return Carbon::parse($s)->format('d-m-Y'); } catch (\Throwable) { return null; }
    }

    /** "January'25", "Jan-2025", "01/2025" -> "YYYY-MM" */
    private function toBillingMonth($value): ?string
    {
        if ($value === null) return null;
        $t = trim((string)$value);
        if ($t === '' || $t === '?' || $t === '-') return null;
        if (preg_match('/^\d{4}-\d{2}$/', $t)) return $t;

        $t = str_replace(["'", '’'], ' ', $t);
        try { return Carbon::parse($t . ' 01')->format('Y-m'); } catch (\Throwable) {}

        $tries = [
            ['M y d',   "{$t} 01"], ['M Y d', "{$t} 01"],
            ['F y d',   "{$t} 01"], ['F Y d', "{$t} 01"],
            ['m/Y d',   "{$t} 01"], ['m-y d', "{$t} 01"],
            ['m/Y',      $t],
        ];
        foreach ($tries as [$fmt, $str]) { try { return Carbon::createFromFormat($fmt, $str)->format('Y-m'); } catch (\Throwable) {} }
        return null;
    }

    private function resolveProjectStatusIdByName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = trim($name);
        if ($norm === '') return null;

        $key = mb_strtolower($norm);
        if (array_key_exists($key, $this->projectStatusIdByName ?? [])) {
            return $this->projectStatusIdByName[$key];
        }

        $id = IntakeStatus::whereRaw('LOWER(name) = ?', [$key])->value('id');
        return $this->projectStatusIdByName[$key] = $id ? (int)$id : null;
    }

    /** Lease type name -> intake_lease_types.id; null if not found */
    private function resolveLeaseTypeIdByName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = trim($name);
        if ($norm === '') return null;

        $key = mb_strtolower($norm);
        if (array_key_exists($key, $this->leaseTypeIdByName ?? [])) {
            return $this->leaseTypeIdByName[$key];
        }

        $id = IntakeLeaseType::whereRaw('LOWER(name) = ?', [$key])->value('id');
        return $this->leaseTypeIdByName[$key] = $id ? (int)$id : null;
    }

    /** Work type name -> service_offerings.id; null if not found */
    private function resolveWorkTypeIdByName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = trim($name);
        if ($norm === '') return null;

        $key = mb_strtolower($norm);
        if (array_key_exists($key, $this->workTypeIdByName ?? [])) {
            return $this->workTypeIdByName[$key];
        }

        $id = ServiceOffering::whereRaw('LOWER(name) = ?', [$key])->value('id');
        return $this->workTypeIdByName[$key] = $id ? (int)$id : null;
    }

    /** Intake query type name -> intake_query_types.id; null if not found */
    private function resolveIntakeQueryName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = trim($name);
        if ($norm === '') return null;

        $key = mb_strtolower($norm);
        if (array_key_exists($key, $this->intakeQueryNameCache ?? [])) {
            return $this->intakeQueryNameCache[$key];
        }

        $id = IntakeQueryType::whereRaw('LOWER(name) = ?', [$key])->value('id');
        return $this->intakeQueryNameCache[$key] = $id ? (int)$id : null;
    }

    /** Query status name -> query_statuses.id; null if not found */
    private function resolveQueryStatusIdByName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = trim($name);
        if ($norm === '') return null;

        $key = mb_strtolower($norm);
        if (array_key_exists($key, $this->queryStatusIdByName ?? [])) {
            return $this->queryStatusIdByName[$key];
        }

        $id = QueryStatus::whereRaw('LOWER(name) = ?', [$key])->value('id');
        return $this->queryStatusIdByName[$key] = $id ? (int)$id : null;
    }

    /** Invoice format name -> invoice_formats.id; null if not found */
    private function resolveInvoiceFormatIdByName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = trim($name);
        if ($norm === '') return null;

        $key = mb_strtolower($norm);
        if (array_key_exists($key, $this->invoiceFormatIdByName ?? [])) {
            return $this->invoiceFormatIdByName[$key];
        }

        $id = InvoiceFormat::whereRaw('LOWER(name) = ?', [$key])->value('id');
        return $this->invoiceFormatIdByName[$key] = $id ? (int)$id : null;
    }

    /** Feedback category name -> feedback_categories.id; null if not found */
    private function resolveFeedbackCategoryIdByName(?string $name): ?int
    {
        if (!$name) return null;
        $norm = trim($name);
        if ($norm === '') return null;

        $key = mb_strtolower($norm);
        if (array_key_exists($key, $this->fbCategoryIdByName ?? [])) {
            return $this->fbCategoryIdByName[$key];
        }

        $id = FeedbackCategory::whereRaw('LOWER(name) = ?', [$key])->value('id');
        return $this->fbCategoryIdByName[$key] = $id ? (int)$id : null;
    }

}

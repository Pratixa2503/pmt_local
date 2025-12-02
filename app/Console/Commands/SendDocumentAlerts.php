<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use App\Models\DocumentAlert;
use App\Models\DocumentContract;
use App\Models\DocumentAlertSentLog;
use App\Models\User;
use App\Mail\DocumentAlertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendDocumentAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:send-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email alerts to customers when document contract start date is approaching';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for documents with contract alerts...');

        // Get today's date
        $today = Carbon::today()->startOfDay();

        // Find all document alerts that need to be checked
        // Conditions:
        // 1. file_alert is enabled
        // 2. Document status is active
        // 3. Contract is_active is true
        $alerts = DocumentAlert::where('file_alert', true)
            ->with(['document.contracts' => function($query) {
                $query->where('is_active', true);
            }, 'contract', 'document.customer', 'document.projectManager'])
            ->whereHas('document', function ($query) {
                $query->where('status', 1); // Active status
            })
            ->whereHas('contract', function ($query) {
                $query->where('is_active', true); // Only active contracts
            })
            ->get();

        $sentCount = 0;
        $errorCount = 0;
        $processedContracts = []; // Track processed contract-alert combinations

        foreach ($alerts as $alert) {
            try {
                $document = $alert->document;
                
                if (!$document) {
                    $this->warn("Alert #{$alert->id} has no document associated.");
                    continue;
                }

                // Get the contract for this alert
                $contract = $alert->contract;
                
                if (!$contract || !$contract->is_active) {
                    continue;
                }

                $contractStartDate = Carbon::parse($contract->contract_start_date)->startOfDay();
                $contractEndDate = Carbon::parse($contract->contract_end_date)->startOfDay();
                
                // Get alert days array (can be multiple days)
                $alertDaysArray = is_array($alert->alert_days) ? $alert->alert_days : ($alert->alert_days ? [$alert->alert_days] : []);
                
                if (empty($alertDaysArray)) {
                    continue;
                }

                // Calculate contract duration in days
                $contractDuration = $contractStartDate->diffInDays($contractEndDate);
                
                // For each alert day, check if we should send email
                foreach ($alertDaysArray as $alertDays) {
                    // Create unique key for this contract-alert-day combination (for in-memory tracking)
                    $uniqueKey = "alert_{$alert->id}_contract_{$contract->id}_day_{$alertDays}_date_{$today->format('Y-m-d')}";

                    // Check if we already processed this combination today (in-memory)
                    if (isset($processedContracts[$uniqueKey])) {
                        continue;
                    }

                    // Check if email was already sent today for this alert/contract/alert_days combination
                    $alreadySent = DocumentAlertSentLog::where('alert_id', $alert->id)
                        ->where('contract_id', $contract->id)
                        ->where('alert_days', $alertDays)
                        ->whereDate('sent_date', $today->format('Y-m-d'))
                        ->exists();

                    if ($alreadySent) {
                        $this->info("Skipping: Email already sent today for Alert #{$alert->id}, Contract #{$contract->id}, Alert Days: {$alertDays}");
                        continue;
                    }

                    $shouldSendEmail = false;
                    $daysUntilStart = 0;
                    
                    // Check if contract start date has passed
                    if ($contractStartDate->lt($today)) {
                        // Start date has passed - check end date condition
                        // Calculate days remaining until end date
                        $daysUntilEnd = $today->diffInDays($contractEndDate, false);
                        
                        // Send email if:
                        // 1. End date is in the future (contract hasn't ended)
                        // 2. End date is within alert_days from today
                        // 3. Contract duration is reasonable (not too long compared to alert_days)
                        if ($daysUntilEnd >= 0 && $daysUntilEnd <= $alertDays) {
                            // Additional check: Don't send if contract duration is much longer than alert_days
                            if ($contractDuration <= ($alertDays * 3)) {
                                $shouldSendEmail = true;
                                $daysUntilStart = $daysUntilEnd; // Days until end date
                            }
                        }
                    } else {
                        // Start date is in the future - check if today is the alert date
                        // Calculate the date when alert should be sent (contract_start_date - alert_days)
                        $alertDate = $contractStartDate->copy()->subDays($alertDays);
                        
                        // Check if today is exactly the alert date
                        if ($today->format('Y-m-d') === $alertDate->format('Y-m-d')) {
                            // Additional validation: Contract should be valid
                            // Don't send if contract duration is unreasonably long
                            if ($contractDuration <= ($alertDays * 10)) {
                                $shouldSendEmail = true;
                                $daysUntilStart = $today->diffInDays($contractStartDate, false);
                            }
                        }
                    }

                    if ($shouldSendEmail) {
                        // Get project manager ID from document
                        $projectManagerId = $document->project_manager_id;
                        
                        if (!$projectManagerId) {
                            $this->warn("Document #{$document->id} has no project manager assigned.");
                            continue;
                        }
                        
                        // Fetch project manager user from database to get email
                        $projectManager = User::where('id', $projectManagerId)
                            ->whereNotNull('email')
                            ->first(['id', 'email', 'first_name', 'last_name']);

                        if (!$projectManager || !$projectManager->email) {
                            $this->warn("Document #{$document->id} - Project Manager (ID: {$projectManagerId}) not found or has no email.");
                            continue;
                        }

                        // Get CC emails from environment
                        $adminEmail = env('ADMIN_EMAIL');
                        $financeEmail = env('FINANCE_EMAIL');
                        
                        // Build CC array (only include valid emails)
                        $ccEmails = [];
                        if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                            $ccEmails[] = $adminEmail;
                        }
                        if ($financeEmail && filter_var($financeEmail, FILTER_VALIDATE_EMAIL)) {
                            $ccEmails[] = $financeEmail;
                        }

                        try {
                            // Calculate days until start for email (can be negative if start date passed)
                            $daysForEmail = $contractStartDate->lt($today) 
                                ? $daysUntilStart // This is days until end when start passed
                                : $daysUntilStart; // Days until start when start is in future
                            
                            // Send email to project manager with CC
                            $mail = Mail::to($projectManager->email);
                            
                            if (!empty($ccEmails)) {
                                $mail->cc($ccEmails);
                            }
                            
                            $mail->send(new DocumentAlertMail($document, $alert, $contract, $daysForEmail, $alertDays));
                            
                            // Record the sent email in database to prevent duplicates
                            DocumentAlertSentLog::create([
                                'alert_id' => $alert->id,
                                'contract_id' => $contract->id,
                                'alert_days' => $alertDays,
                                'sent_date' => $today->format('Y-m-d'),
                                'sent_at' => Carbon::now(),
                                'recipient_email' => $projectManager->email,
                            ]);
                            
                            $customer = $document->customer;
                            $customerName = $customer ? $customer->name : 'Unknown';
                            
                            $statusMessage = $contractStartDate->lt($today) 
                                ? "Contract ending in {$daysUntilStart} days"
                                : "Contract starts in {$daysUntilStart} days";
                            
                            $ccInfo = !empty($ccEmails) ? ' (CC: ' . implode(', ', $ccEmails) . ')' : '';
                            $this->info("✓ Alert sent to {$projectManager->email}{$ccInfo} for Document #{$document->id} (Contract: {$contractStartDate->format('Y-m-d')} to {$contractEndDate->format('Y-m-d')}, {$statusMessage}, Customer: {$customerName})");
                            $sentCount++;
                            
                            // Mark this combination as processed to prevent duplicates (in-memory)
                            $processedContracts[$uniqueKey] = true;
                        } catch (\Exception $e) {
                            Log::error("Failed to send document alert email", [
                                'alert_id' => $alert->id,
                                'document_id' => $document->id,
                                'contract_id' => $contract->id,
                                'contract_start_date' => $contractStartDate->format('Y-m-d'),
                                'contract_end_date' => $contractEndDate->format('Y-m-d'),
                                'alert_days' => $alertDays,
                                'pm_email' => $projectManager->email,
                                'cc_emails' => $ccEmails,
                                'error' => $e->getMessage()
                            ]);
                            $this->error("✗ Failed to send alert to {$projectManager->email} for Document #{$document->id}: {$e->getMessage()}");
                            $errorCount++;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error processing document alert", [
                    'alert_id' => $alert->id ?? null,
                    'document_id' => $alert->document->id ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $this->error("✗ Error processing Alert #{$alert->id}: {$e->getMessage()}");
                $errorCount++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Alerts sent: {$sentCount}");
        if ($errorCount > 0) {
            $this->warn("Errors: {$errorCount}");
        }

        return Command::SUCCESS;
    }
}

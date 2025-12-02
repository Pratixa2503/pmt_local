<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MainTask;
use App\Models\SubTask;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'name' => 'Email Review & Response',
                'subtasks' => [
                    ['name' => 'Email Checking', 'task_type' => 1, 'benchmarked_time' => null],
                    ['name' => 'Email Response', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                ]
            ],
            [
                'name' => 'Accounts Payables',
                'subtasks' => [
                    ['name' => 'Invoice Processing', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Vendor Creation', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Vendor COI Update', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Vendor COI followup', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Reviewing Invoice', 'task_type' => 2, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Vendor Follow-up Request', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Credit Card Transaction Upload', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Job Creation', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Budget Setup', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Contract & Change Orders', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Budget Revisions', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Amex - Procore update', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Labor Uploads - Procore', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Mortgage statements Process', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Payment Update in Procore', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Procore Validation', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Posted Payables in Procore', 'task_type' => 1, 'benchmarked_time' => '00:01:00'],
                ]
            ],
            [
                'name' => 'Accounts Receivables',
                'subtasks' => [
                    ['name' => 'Receipts Processing', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Prepay Application', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Move-out Accounting', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Security Deposit Refund Request', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Charges Creation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Tenant Billbacks', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'AR Aging Review', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Commercial Billing', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => "Statements send to PM's review", 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Statements send to tenants', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Receipts Reviewing', 'task_type' => 2, 'benchmarked_time' => '00:01:00'],
                    ['name' => 'Banks Transactions', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'NSF/ Reversal / Adjustments Entry Posting', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Tenant COI Update', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Contacts Update', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'CAM Impound Setup', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                ]
            ],
            [
                'name' => 'Bank Reconciliation',
                'subtasks' => [
                    ['name' => 'Previous day reports download', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Monthly Statements Download', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Daily Reconciliation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Monthly Reconciliation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'BRS Report', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Cash flow activity', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                ]
            ],
            [
                'name' => 'General Ledger',
                'subtasks' => [
                    ['name' => 'Journal Entry Preparation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Journal Entry Posting', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'GL Account Reconciliation', 'task_type' => 2, 'benchmarked_time' => '00:05:00'],
                ]
            ],
            [
                'name' => 'Month-End Activities',
                'subtasks' => [
                    ['name' => 'Accruals Preparation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Balance Sheet Reconciliation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'GL Review Notes', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Budget Comparison Notes', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Prepaid/Deferred Schedules', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Management Fee Calculation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Monthly Reports', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                ]
            ],
            [
                'name' => 'Financial Packages',
                'subtasks' => [
                    ['name' => 'Financial Reports Creation', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Quarterly Reports', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Financial package Review', 'task_type' => 2, 'benchmarked_time' => '00:05:00'],
                ]
            ],
            [
                'name' => 'Adhoc Request',
                'subtasks' => [
                    ['name' => 'Lender Drawings', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                    ['name' => 'Special Projects', 'task_type' => 1, 'benchmarked_time' => '00:05:00'],
                ]
            ],
            [
                'name' => 'Break Time',
                'subtasks' => [
                    ['name' => 'Tea Break', 'task_type' => 2, 'benchmarked_time' => '00:15:00'],
                    ['name' => 'Bio-Break', 'task_type' => 2, 'benchmarked_time' => '00:15:00'],
                    ['name' => 'Lunch Break', 'task_type' => 2, 'benchmarked_time' => '00:30:00'],
                ]
            ],
            [
                'name' => 'Meeting',
                'subtasks' => [
                    ['name' => 'Meeting - Internal', 'task_type' => 2, 'benchmarked_time' => '00:30:00'],
                    ['name' => 'Client Meeting', 'task_type' => 1, 'benchmarked_time' => '00:30:00'],
                ]
            ],
            [
                'name' => 'Trainings & System Issue',
                'subtasks' => [
                    ['name' => 'Process Training', 'task_type' => 2, 'benchmarked_time' => '00:30:00'],
                    ['name' => 'Doubt Clarification', 'task_type' => 2, 'benchmarked_time' => '00:30:00'],
                    ['name' => 'System Issue', 'task_type' => 2, 'benchmarked_time' => '00:30:00'],
                ]
            ],
        ];

        foreach ($tasks as $taskData) {
            $mainTask = MainTask::create([
                'name' => $taskData['name'],
                'status' => 1,
            ]);

            foreach ($taskData['subtasks'] ?? [] as $sub) {
                SubTask::create([
                    'main_task_id' => $mainTask->id,
                    'name' => $sub['name'],
                    'task_type' => $sub['task_type'],
                    'benchmarked_time' => $sub['benchmarked_time'],
                    'status' => 1,
                ]);
            }
        }
    }
}

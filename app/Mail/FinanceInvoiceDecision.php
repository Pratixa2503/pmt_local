<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class FinanceInvoiceDecision extends Mailable 
{
    use Queueable, SerializesModels;

    public function __construct(
        public int    $invoiceId,
        public string $invoiceNo,
        public string $projectName,
        public string $billingMonth,
        public string $totalFormatted,
        public string $customerName,
        public string $action, // 'approved' or 'rejected'
        public ?string $financeNotes = null,
        public string $reviewUrl = '',
        public ?string $brandName = null,
        public ?string $logoUrl   = null,
    ) {}

    public function build()
    {
        $subject = $this->action === 'approved' 
            ? "Invoice Approved: {$this->invoiceNo}"
            : "Invoice Rejected: {$this->invoiceNo}";

        return $this->subject($subject)
            ->view('emails.finance_invoice_decision')
            ->with([
                'invoiceId'      => $this->invoiceId,
                'invoiceNo'      => $this->invoiceNo,
                'projectName'    => $this->projectName,
                'billingMonth'   => $this->billingMonth,
                'totalFormatted' => $this->totalFormatted,
                'customerName'   => $this->customerName,
                'action'         => $this->action,
                'financeNotes'  => $this->financeNotes,
                'reviewUrl'      => $this->reviewUrl,
                'brandName'      => $this->brandName,
                'logoUrl'        => $this->logoUrl,
            ]);
    }
}


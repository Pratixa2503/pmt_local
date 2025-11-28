<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class FinanceInvoiceSubmitted extends Mailable 
{
    use Queueable, SerializesModels;

    public function __construct(
        public int    $invoiceId,
        public string $invoiceNo,
        public string $projectName,
        public string $billingMonth,
        public string $totalFormatted,
        public string $customerName,
        public string $poNumber,
        public string $invoiceDate,
        public string $reviewUrl,
        // NEW
        public ?string $brandName = null,
        public ?string $logoUrl   = null,
    ) {}

    public function build()
    {
        return $this->subject("Invoice Submitted: {$this->invoiceNo}")
            ->view('emails.finance_invoice_submitted')
            ->with([
                'invoiceId'      => $this->invoiceId,
                'invoiceNo'      => $this->invoiceNo,
                'projectName'    => $this->projectName,
                'billingMonth'   => $this->billingMonth,
                'totalFormatted' => $this->totalFormatted,
                'customerName'   => $this->customerName,
                'poNumber'       => $this->poNumber,
                'invoiceDate'    => $this->invoiceDate,
                'reviewUrl'      => $this->reviewUrl,
                // NEW for your styled template
                'brandName'      => $this->brandName,
                'logoUrl'        => $this->logoUrl,
            ]);
    }
}

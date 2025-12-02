<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Document;
use App\Models\DocumentAlert;
use App\Models\DocumentContract;
use Illuminate\Support\Facades\Storage;

class DocumentAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $document;
    public $alert;
    public $contract;
    public $daysUntilStart;
    public $alertDays;

    /**
     * Create a new message instance.
     */
    public function __construct(Document $document, DocumentAlert $alert, DocumentContract $contract, int $daysUntilStart, int $alertDays = null)
    {
        $this->document = $document;
        $this->alert = $alert;
        $this->contract = $contract;
        $this->daysUntilStart = $daysUntilStart;
        $this->alertDays = $alertDays;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $customerName = $this->document->customer->name ?? 'Customer';
        return new Envelope(
            subject: "Contract Expiry Notification - {$customerName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.document_alert',
            with: [
                'document' => $this->document,
                'alert' => $this->alert,
                'contract' => $this->contract,
                'daysUntilStart' => $this->daysUntilStart,
                'alertDays' => $this->alertDays,
                'customer' => $this->document->customer,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Attach alert-specific file if exists
        if ($this->alert->alert_file && Storage::disk('public')->exists($this->alert->alert_file)) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->alert->alert_file)
                ->as('document_alert_attachment.' . pathinfo($this->alert->alert_file, PATHINFO_EXTENSION));
        }

        // Attach main document file if exists
        if ($this->document->file_path && Storage::disk('public')->exists($this->document->file_path)) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->document->file_path)
                ->as('document.' . pathinfo($this->document->file_path, PATHINFO_EXTENSION));
        }

        return $attachments;
    }
}


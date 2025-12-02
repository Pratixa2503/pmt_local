<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignmentSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $role,
        public int $count,
        public array $items = [],
        // optional presentation props:
        public ?string $brandName = null,
        public ?string $logoUrl = null,
        public ?string $dashboardUrl = null,
    ) {}

    public function build()
    {
        return $this->subject("{$this->role} Assignments ({$this->count})")
            ->view('emails.assignment-summary')
            ->with([
                'role'         => $this->role,
                'count'        => $this->count,
                'items'        => $this->items,
                'brandName'    => $this->brandName,   // optional
                'logoUrl'      => $this->logoUrl,     // optional
                'dashboardUrl' => $this->dashboardUrl // optional
            ]);
    }
}

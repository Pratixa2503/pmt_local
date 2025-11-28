<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbstractionCompletedMail extends Mailable 
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $role,
        public int $count,
        public array $items = [],
        public ?string $brandName = null,
        public ?string $logoUrl = null,
        public ?string $dashboardUrl = null,
    ) {}

    public function build()
    {
        return $this->subject("Abstraction Completed — {$this->count} item(s)")
            ->view('emails.abstraction_completed')
            ->with([
                'role'         => $this->role,
                'count'        => $this->count,
                'items'        => $this->items,
                'brandName'    => $this->brandName,
                'logoUrl'      => $this->logoUrl,
                'dashboardUrl' => $this->dashboardUrl,
            ]);
    }
}

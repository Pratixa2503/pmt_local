<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StageNotificationMail extends Mailable 
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $title,       // e.g. "Abstraction Completed"
        public string $subtitle,    // e.g. "Items ready for Review"
        public int $count,
        public array $items = [],   // each: project_name, tenant_id, property, tenant, completed_at, stage, next_action
        public string $ctaText = 'Open Dashboard',
        public ?string $ctaUrl = null,
        public ?string $brandName = 'Springbord',
        public ?string $logoUrl = null,
    ) {}

    public function build()
    {
        //dd("Hello");
        return $this->subject("{$this->title} ({$this->count})")
            ->view('emails.stage_notification')
            ->with([
                'title'     => $this->title,
                'subtitle'  => $this->subtitle,
                'count'     => $this->count,
                'items'     => $this->items,
                'ctaText'   => $this->ctaText,
                'ctaUrl'    => $this->ctaUrl ?? url('/'),
                'brandName' => $this->brandName,
                'logoUrl'   => $this->logoUrl ?? asset('assets/img/logo.svg'),
            ]);
    }
}

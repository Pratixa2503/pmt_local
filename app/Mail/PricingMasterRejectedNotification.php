<?php

namespace App\Mail;

use App\Models\PricingMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PricingMasterRejectedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public PricingMaster $pricing;
    public string $showUrl;

    public function __construct(PricingMaster $pricing, string $encryptedId)
    {
        $this->pricing = $pricing;
        // points to existing resource show route with encrypted param
        $this->showUrl = route('pricing-master.show', ['pricing_master' => $encryptedId]);
    }

    public function build()
    {
        return $this->subject('Your Pricing Master was Rejected (#'.$this->pricing->id.')')
            ->view('emails.pricing_master_rejected', [
                'pricing'   => $this->pricing,
                'showUrl'   => $this->showUrl,
                'brandName' => 'Springbord',
                'logoUrl'   => 'https://www.springbord.com/_homeassets/logo-primary.DEMX77NU_ZoGfdz.svg',
            ]);
    }
}

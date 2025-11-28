<?php

namespace App\Mail;

use App\Models\PricingMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PricingMasterApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public PricingMaster $pricing;
    public string $showUrl;

    public function __construct(PricingMaster $pricing, string $encryptedId)
    {
        $this->pricing = $pricing;
        // Link to the existing resource show route using encrypted id
        $this->showUrl = route('pricing-master.show', ['pricing_master' => $encryptedId]);
    }

    public function build()
    {
        return $this->subject('Your Pricing Master was Approved (#'.$this->pricing->id.')')
            ->view('emails.pricing_master_approved', [
                'pricing'   => $this->pricing,
                'showUrl'   => $this->showUrl,
                'brandName' => 'Springbord',
                'logoUrl'   => 'https://www.springbord.com/_homeassets/logo-primary.DEMX77NU_ZoGfdz.svg',
            ]);
    }
}

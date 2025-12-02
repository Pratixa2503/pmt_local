<?php

namespace App\Mail;

use App\Models\PricingMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PricingMasterModificationRequest extends Mailable
{
    use Queueable, SerializesModels;

    public PricingMaster $pricing;
    public string $editUrl;
    public string $modificationNotes;
    public string $modificationParameter;

    public function __construct(
        PricingMaster $pricing,
        string $encryptedId,
        string $modificationNotes,
        string $modificationParameter
    ) {
        $this->pricing = $pricing;
        $this->modificationNotes = $modificationNotes;
        $this->modificationParameter = $modificationParameter;
        $this->editUrl = route('pricing-master.edit', ['pricing_master' => $encryptedId]);
    }

    public function build()
    {
        return $this->subject('Pricing Master Modification Required (#'.$this->pricing->id.')')
            ->view('emails.pricing_master_modification', [
                'pricing'               => $this->pricing,
                'editUrl'               => $this->editUrl,
                'modificationNotes'     => $this->modificationNotes,
                'modificationParameter' => $this->modificationParameter,
                'brandName'             => 'Springbord',
                'logoUrl'               => 'https://www.springbord.com/_homeassets/logo-primary.DEMX77NU_ZoGfdz.svg',
            ]);
    }
}

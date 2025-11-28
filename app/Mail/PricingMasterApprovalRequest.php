<?php

namespace App\Mail;

use App\Models\PricingMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PricingMasterApprovalRequest extends Mailable
{
    use Queueable, SerializesModels;

    public PricingMaster $pricing;
    public string $token;
    /** @var string[] */
    public array $serviceOfferingNames;
    public string $currencyLabel;

    /**
     * @param string[] $serviceOfferingNames  Array of service offering names
     */
    public function __construct(
        PricingMaster $pricing,
        string $token,
        array $serviceOfferingNames,
        string $currencyLabel
    ) {
        $this->pricing              = $pricing;
        $this->token                = $token;
        $this->serviceOfferingNames = $serviceOfferingNames;
        $this->currencyLabel        = $currencyLabel;
    }

    public function build()
    {
        // Route that will decrypt the token and be resolved by Route::bind() to PricingMaster
        $reviewUrl = route('pricing-master.show', ['pricing_master' => $this->token]);

        return $this->subject('Approval Needed: Pricing Master #'.$this->pricing->id)
            ->view('emails.pricing_master_approval', [
                'pricing'              => $this->pricing,
                'reviewUrl'            => $reviewUrl,
                'logoUrl'              => 'https://www.springbord.com/_homeassets/logo-primary.DEMX77NU_ZoGfdz.svg',
                'brandName'            => 'Springbord',
                'serviceOfferingNames' => $this->serviceOfferingNames, // <-- use $this->
                'currencyLabel'        => $this->currencyLabel,       // <-- use $this->
            ]);
    }
}

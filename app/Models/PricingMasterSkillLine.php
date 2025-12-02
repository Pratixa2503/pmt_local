<?php

// app/Models/PricingMasterSkillLine.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class PricingMasterSkillLine extends LoggableModel
{
    protected $fillable = [
        'pricing_master_id',
        'skill_id',
        'average_handling_time',
    ];

    public function pricingMaster(): BelongsTo
    {
        return $this->belongsTo(PricingMaster::class);
    }
}

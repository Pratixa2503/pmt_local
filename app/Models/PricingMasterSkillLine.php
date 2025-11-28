<?php

// app/Models/PricingMasterSkillLine.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD

class PricingMasterSkillLine extends Model
=======
use Illuminate\Database\Eloquent\SoftDeletes;
class PricingMasterSkillLine extends LoggableModel
>>>>>>> 9d9ed85b (for cleaner setup)
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

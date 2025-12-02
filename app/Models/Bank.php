<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Bank extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'entity',
        'currency_id',
        'account_name',
        'account_number',
        'bank_name',
        'branch_location',
        'ifsc_code',
        'swift_code',
        'micr',
        'bsr_code',
        'branch_address',
        'status',
        'aba_number',
        'routing_number'
    ];

    public function currency()
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }
}

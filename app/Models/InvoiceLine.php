<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceLine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_id','project_id','billing_month',
        'sno','description','sac','qty','rate','value',
        'source_intake_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}

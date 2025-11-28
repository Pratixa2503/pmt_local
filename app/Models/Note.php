<?php

namespace App\Models;
<<<<<<< HEAD

class Note extends LoggableModel
{
=======
use Illuminate\Database\Eloquent\SoftDeletes;
class Note extends LoggableModel
{
   
>>>>>>> 9d9ed85b (for cleaner setup)
    protected $fillable = [
        'pricing_master_id',
        'note_type',
        'price',
        'description',
        'approve_rejected_by',
        'create_by'
    ];

    public function pricingMaster()
    {
        return $this->belongsTo(PricingMaster::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approve_rejected_by');
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->note_type) {
            1 => 'Approve',
            2 => 'Reject',
            default => 'Unknown',
        };
    }
}


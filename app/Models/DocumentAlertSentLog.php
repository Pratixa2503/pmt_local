<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAlertSentLog extends Model
{
    use HasFactory;

    protected $table = 'document_alert_sent_logs';

    protected $fillable = [
        'alert_id',
        'contract_id',
        'alert_days',
        'sent_date',
        'sent_at',
        'recipient_email',
    ];

    protected $casts = [
        'sent_date' => 'date',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the alert that owns this log.
     */
    public function alert()
    {
        return $this->belongsTo(DocumentAlert::class);
    }

    /**
     * Get the contract that owns this log.
     */
    public function contract()
    {
        return $this->belongsTo(DocumentContract::class);
    }
}

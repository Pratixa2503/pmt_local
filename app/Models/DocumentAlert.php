<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAlert extends Model
{
    use HasFactory;

    protected $table = 'document_alerts';

    protected $fillable = [
        'document_id',
        'contract_id',
        'file_alert',
        'alert_days',
        'alert_file',
        'sent_at',
    ];

    protected $casts = [
        'file_alert' => 'boolean',
        'sent_at' => 'datetime',
        'alert_days' => 'array',
    ];

    /**
     * Get the document that owns this alert.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the contract that owns this alert.
     */
    public function contract()
    {
        return $this->belongsTo(DocumentContract::class, 'contract_id');
    }
}

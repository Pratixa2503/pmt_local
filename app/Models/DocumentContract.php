<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentContract extends Model
{
    use HasFactory;

    protected $table = 'document_contracts';

    protected $fillable = [
        'document_id',
        'contract_start_date',
        'contract_end_date',
        'is_active',
    ];

    protected $casts = [
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the document that owns this contract.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get all alerts for this contract.
     */
    public function alerts()
    {
        return $this->hasMany(DocumentAlert::class, 'contract_id');
    }
}

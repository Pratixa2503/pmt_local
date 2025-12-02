<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends LoggableModel
{
    use HasFactory, SoftDeletes;
    protected $table = 'documents';

    protected $fillable = [
        'customer_id',
        'contact_no',
        'description',
        'project_manager_id',
        'industry_vertical_id',
        'department_id',
        'status',
        'file_path',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
    ];

    // Relations
    //public function customer()  { return $this->belongsTo(User::class); }
    public function pms()       { return $this->belongsToMany(User::class,'project_user')->withTimestamps(); }
    public function industryVertical()
    {
        return $this->belongsTo(IndustryVertical::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function customer()
    {
        return $this->belongsTo(Company::class);
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    /**
     * Get all alerts for this document.
     */
    public function alerts()
    {
        return $this->hasMany(DocumentAlert::class);
    }

    /**
     * Get all contracts for this document.
     */
    public function contracts()
    {
        return $this->hasMany(DocumentContract::class);
    }
}

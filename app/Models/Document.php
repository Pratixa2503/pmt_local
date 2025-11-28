<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

<<<<<<< HEAD
class Document extends Model
=======
class Document extends LoggableModel
>>>>>>> 9d9ed85b (for cleaner setup)
{
    use HasFactory, SoftDeletes;
    protected $table = 'documents';

    protected $fillable = [
        'customer_id',
        'contact_no',
        'description',
<<<<<<< HEAD
        'contract_start_date',
        'contract_end_date',
=======
>>>>>>> 9d9ed85b (for cleaner setup)
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
<<<<<<< HEAD
        'contract_start_date' => 'date',
        'contract_end_date'   => 'date',
=======
>>>>>>> 9d9ed85b (for cleaner setup)
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

<<<<<<< HEAD

=======
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
>>>>>>> 9d9ed85b (for cleaner setup)
}

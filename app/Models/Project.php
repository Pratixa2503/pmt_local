<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_name',
        'description',
        'is_recurring',
        'recurring_type',
        'start_date',
        'end_date',
        'customer_id',
        'project_type_id',
        'department_id',
        'pricing_id',
        'input_format_id',
        'output_format_id',
        'mode_of_delivery_id',
        'frequency_of_delivery_id',
        'project_priority_id',
        'project_status_id',
        'parent_id',
        'project_category',
        'suite_id',
        'industry_vertical_id',
        'service_offering_id',
        'pricing_type'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // Relations
    //public function customer()  { return $this->belongsTo(User::class); }
    public function pms()       { return $this->belongsToMany(User::class,'project_user')->withTimestamps(); }
    public function pocs()      {  return $this->belongsToMany(User::class, 'contact_project', 'project_id', 'contact_id')
                ->withTimestamps(); }

    public function projectType()         { return $this->belongsTo(ProjectType::class, 'project_type_id'); }
    public function department()          { return $this->belongsTo(Department::class); }
    public function pricing()             { return $this->belongsTo(PricingMaster::class); }
    public function inputFormat()         { return $this->belongsTo(InputOutputFormat::class, 'input_format_id'); }
    public function outputFormat()        { return $this->belongsTo(InputOutputFormat::class, 'output_format_id'); }
    public function modeOfDelivery()      { return $this->belongsTo(ModeOfDelivery::class, 'mode_of_delivery_id'); }
    public function frequencyOfDelivery() { return $this->belongsTo(ProjectDeliveryFrequency::class, 'frequency_of_delivery_id'); }
    public function priority()            { return $this->belongsTo(ProjectPriority::class, 'project_priority_id'); }
    public function status()              { return $this->belongsTo(ProjectStatus::class, 'project_status_id'); }
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category');
    }     
    // Helpers
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $this->mmddyyyyToDate($value);
    }
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $this->mmddyyyyToDate($value);
    }
    protected function mmddyyyyToDate($value)
    {
        if ($value instanceof Carbon) return $value;
        if (is_string($value) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            return Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
        }
        return $value;
    }

    public function parent()
    {
        return $this->belongsTo(Project::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Project::class, 'parent_id');
    }

    public function memberAssignments()
    {
        return $this->hasMany(ProjectMemberAssignment::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_member_assignments', 'project_id', 'member_id')
            ->withPivot('pm_id')
            ->withTimestamps();
    }

    public function mainTasks()
    {
        return $this->belongsToMany(MainTask::class, 'project_main_tasks', 'project_id', 'main_task_id')
            ->withTimestamps();
    }
}

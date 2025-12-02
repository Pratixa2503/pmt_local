<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProjectMemberAssignment extends LoggableModel
{
    
    protected $fillable = ['project_id','pm_id','member_id','startdate','enddate'];

    public function project() { return $this->belongsTo(Project::class); }
    public function pm() { return $this->belongsTo(User::class, 'pm_id'); }
    public function member() { return $this->belongsTo(User::class, 'member_id'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class SkillMaster extends LoggableModel
{
   use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'skill_expertise_level',
        'ctc',
        'status',
        'created_by',
        'updated_by',
    ];
}

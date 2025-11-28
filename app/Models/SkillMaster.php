<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD

class SkillMaster extends LoggableModel
{
   use HasFactory;

=======
use Illuminate\Database\Eloquent\SoftDeletes;
class SkillMaster extends LoggableModel
{
   use HasFactory;
    use SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)
    protected $fillable = [
        'name',
        'skill_expertise_level',
        'ctc',
        'status',
        'created_by',
        'updated_by',
    ];
}

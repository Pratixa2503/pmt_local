<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends LoggableModel
{
    use HasFactory;
    protected $fillable = ['name', 'status','created_by','updated_by'];
    use SoftDeletes;
    protected $fillable = ['name', 'industry_verticals_id','status','created_by','updated_by'];
}

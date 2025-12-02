<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InputOutputFormat extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDeliveryFrequency extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

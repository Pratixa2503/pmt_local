<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
class ServiceOffering extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name','department_id','status','created_by','updated_by'];
}

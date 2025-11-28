<?php

namespace App\Models;

class ProjectDeliveryFrequency extends LoggableModel
{
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDeliveryFrequency extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

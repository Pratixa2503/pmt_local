<?php

namespace App\Models;

class ProjectStatus extends LoggableModel
{
use Illuminate\Database\Eloquent\SoftDeletes;
class ProjectStatus extends LoggableModel
{
      use SoftDeletes;
      protected $fillable = ['name', 'status','created_by','updated_by'];
}

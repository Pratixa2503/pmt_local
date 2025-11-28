<?php

namespace App\Models;

class ProjectType extends LoggableModel
{
use Illuminate\Database\Eloquent\SoftDeletes;
class ProjectType extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

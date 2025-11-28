<?php

namespace App\Models;


class ProjectPriority extends LoggableModel
{
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectPriority extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

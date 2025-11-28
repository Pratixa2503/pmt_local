<?php

namespace App\Models;

class Description extends LoggableModel
{
use Illuminate\Database\Eloquent\SoftDeletes;

class Description extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
}

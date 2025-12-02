<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
class Currency extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
}

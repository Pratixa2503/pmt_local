<?php

namespace App\Models;

class Currency extends LoggableModel
{
use Illuminate\Database\Eloquent\SoftDeletes;
class Currency extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
}

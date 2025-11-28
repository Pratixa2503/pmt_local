<?php

namespace App\Models;

class ServiceOffering extends LoggableModel
{
    protected $fillable = ['name', 'status','created_by','updated_by'];
use Illuminate\Database\Eloquent\SoftDeletes;
class ServiceOffering extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name','department_id','status','created_by','updated_by'];
}

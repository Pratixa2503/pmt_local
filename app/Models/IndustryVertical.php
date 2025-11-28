<?php

namespace App\Models;

class IndustryVertical extends LoggableModel 
{
  
    protected $fillable = ['name', 'status','created_by','updated_by'];
use Illuminate\Database\Eloquent\SoftDeletes;
class IndustryVertical extends LoggableModel 
{
    use SoftDeletes;
  
    protected $fillable = ['name','status','created_by','updated_by'];
    
}

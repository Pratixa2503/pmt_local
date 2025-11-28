<?php

namespace App\Models;

class CustomerApprovalStatus extends LoggableModel
{
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerApprovalStatus extends LoggableModel
{
    use SoftDeletes;
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

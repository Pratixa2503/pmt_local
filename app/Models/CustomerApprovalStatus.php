<?php

namespace App\Models;
<<<<<<< HEAD

class CustomerApprovalStatus extends LoggableModel
{
=======
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerApprovalStatus extends LoggableModel
{
    use SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

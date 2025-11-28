<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)

class Department extends LoggableModel
{
    use HasFactory;
<<<<<<< HEAD
    protected $fillable = ['name', 'status','created_by','updated_by'];
=======
    use SoftDeletes;
    protected $fillable = ['name', 'industry_verticals_id','status','created_by','updated_by'];
>>>>>>> 9d9ed85b (for cleaner setup)
}

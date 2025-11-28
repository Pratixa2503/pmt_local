<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)

class InputOutputFormat extends LoggableModel
{
    use HasFactory;
<<<<<<< HEAD

=======
    use SoftDeletes;
    
>>>>>>> 9d9ed85b (for cleaner setup)
    protected $fillable = ['name', 'status','created_by','updated_by'];
}

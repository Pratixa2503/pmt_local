<?php

namespace App\Models;
<<<<<<< HEAD

class Description extends LoggableModel
{
=======
use Illuminate\Database\Eloquent\SoftDeletes;

class Description extends LoggableModel
{
    use SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
}

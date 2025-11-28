<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class QueryStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
=======
use Illuminate\Database\Eloquent\SoftDeletes;

class QueryStatus extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name','status'];
>>>>>>> 9d9ed85b (for cleaner setup)
}

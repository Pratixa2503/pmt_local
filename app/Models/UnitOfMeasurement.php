<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD

class UnitOfMeasurement extends LoggableModel
{
=======
use Illuminate\Database\Eloquent\SoftDeletes;
class UnitOfMeasurement extends LoggableModel
{
    use SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)
    use HasFactory;
    protected $fillable = ['name', 'status', 'created_by', 'updated_by'];
}

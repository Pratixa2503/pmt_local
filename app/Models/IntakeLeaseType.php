<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class IntakeLeaseType extends Model
{
    use HasFactory;
=======
use Illuminate\Database\Eloquent\SoftDeletes;

class IntakeLeaseType extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;
>>>>>>> 9d9ed85b (for cleaner setup)

    protected $fillable = ['name'];
}

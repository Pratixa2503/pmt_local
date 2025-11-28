<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntakeWorkType extends Model
{
    use HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;

class IntakeWorkType extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];
}

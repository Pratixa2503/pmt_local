<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;

class InvoiceFormat extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
=======
use Illuminate\Database\Eloquent\SoftDeletes;
class InvoiceFormat extends LoggableModel
{
    use HasFactory;
    use SoftDeletes;

 
    protected $fillable = ['name','status'];
>>>>>>> 9d9ed85b (for cleaner setup)
}

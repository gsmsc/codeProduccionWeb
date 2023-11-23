<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lineas extends Model
{
    protected $table = "CAT_lineas";
    
    protected $fillable = [
        'id',
        'descripcion'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estilos extends Model
{
    protected $table = "CAT_estilos";

    protected $fillable = [
        'id',
        'descripcion'
    ];
}

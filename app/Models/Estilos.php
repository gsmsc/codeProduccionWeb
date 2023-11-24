<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estilos extends Model
{
    protected $table = "CAT_estilos";

    protected $fillable = [
        'id',
        'codigo',
        'idCliente',
        'idDivision',
        'idSubcategoria',
        'referencia1',
        'referencia2',
        'descripcion'
    ];
}

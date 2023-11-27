<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $table = "tblProduccion";

    protected $fillable = [
        'id',
        'idUsuario',
        'fecha',
        'idLinea',
        'idEstilo',
        'operariosNormal',
        'operariosRefuerzos',
        'uProducidas',
        'uIrregulares',
        'uRegulares',
        'metaNormal',
        'totalHorasOrdinarias',
        'totalHorasExtras',
        'totalHorasTrabajadas',
        'horasNoProducidas',
        'horasProducidas',
        'metaAjustada',
        'eficiencia',
        'bonos',
        'maquinaMala',
        'noTrabajo',
        'entrenamiento',
        'cambioEstilo',
        'observaciones'
    ];
}

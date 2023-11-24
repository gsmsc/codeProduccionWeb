<?php

namespace App\Http\Controllers;

use App\Models\Estilos;
use App\Models\Lineas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduccionController extends Controller
{
    public function index()
    {
        $produccion = DB::table('tblProduccion as PRD')
            ->select(
                'PRD.id',
                'PRD.fecha',
                'CLI.descripcion as descripcionLinea',
                'CES.descripcion as descripcionEstilo',
            )
            ->leftJoin('CAT_lineas as CLI', 'PRD.idLinea', '=', 'CLI.id')
            ->leftJoin('CAT_estilos as CES', 'PRD.idEstilo', '=', 'CES.id')
            ->get();
        return view('produccion.index', compact('produccion'));
    }

    public function create()
    {
        $lineas = Lineas::all();
        $estilos = Estilos::all();
        return view('produccion.create', compact('lineas', 'estilos'));
    }

    public function store(Request $request)
    {
        dd($request);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

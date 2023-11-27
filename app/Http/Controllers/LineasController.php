<?php

namespace App\Http\Controllers;

use App\Models\Lineas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineasController extends Controller
{
    public function index()
    {
        $lineas = Lineas::all();
        return view('lineas.index', compact('lineas'));
    }

    public function store(Request $request)
    {
        $validacionesInputs = [
            'descripcion' => 'required|max:100',
        ];

        $respuestaValidaciones = [
            'descripcion.required' => 'La descripción es un valor requerido.',
            'descripcion.max' => 'La descripción no debe exceder de 100 caracteres.'
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);

        $input = $request->all();

        $arrayCreate = [
            'descripcion' => strtoupper($input['descripcion']),
        ];

        Lineas::create($arrayCreate)->id;

        return redirect()->route('lineas.index')->with('success', 'Línea grabada con éxito');
    }

    public function getDataLinea($idLinea)
    {
        $editLinea = DB::table('CAT_lineas')
            ->select('descripcion')
            ->where('id', '=', $idLinea)
            ->first();

        return $editLinea;
    }

    public function update(Request $request)
    {
        $validacionesInputs = [
            'descripcion' => 'required|max:100',
        ];

        $respuestaValidaciones = [
            'descripcion.required' => 'La descripción es un valor requerido.',
            'descripcion.max' => 'La descripción no debe exceder de 100 caracteres.'
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);
        $input = $request->all();

        $arrayUpdate = [
            'descripcion' => strtoupper($input['descripcion']),
        ];

        $linea = Lineas::find($request->linea_id);
        $linea->update($arrayUpdate);

        return redirect()->route('lineas.index')->with('success', 'Línea editada con éxito');
    }

    public function destroy($id)
    {
        $registros = DB::table('tblProduccion')->where('idLinea', '=', $id)->count();
        if ($registros == 0) {
            $linea = Lineas::find($id);
            $linea->delete();
        } else {
            return redirect()->route('lineas.index')->with('info', 'Esta línea está siendo usada por uno o más registros de producción, imposible eliminar.');;
        }
        return redirect()->route('lineas.index')->with('info', 'Línea eliminada con éxito');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Estilos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstilosController extends Controller
{
    public function index()
    {
        $estilos = Estilos::all();
        return view('estilos.index', compact('estilos'));
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

        Estilos::create($arrayCreate)->id;

        return redirect()->route('estilos.index')->with('success', 'Estilo grabado con éxito');
    }

    public function getDataEstilo($idEstilo)
    {
        $editEstilo = DB::table('CAT_estilos')
            ->select('descripcion')
            ->where('id', '=', $idEstilo)
            ->first();

        return $editEstilo;
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

        $estilo = Estilos::find($request->estilo_id);
        $estilo->update($arrayUpdate);

        return redirect()->route('estilos.index')->with('success', 'Estilo editado con éxito');
    }

    public function destroy($id)
    {
        $estilos = Estilos::find($id);
        $estilos->delete();

        return redirect()->route('estilos.index')->with('info', 'Estilo eliminado con éxito');
    }
}

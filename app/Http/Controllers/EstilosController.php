<?php

namespace App\Http\Controllers;

use App\Models\Estilos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstilosController extends Controller
{
    public function index()
    {
        $clientes = DB::table('CAT_clientes')->get();
        $division = DB::table('CAT_division')->get();
        $subcategoria = DB::table('CAT_subcategoria')->get();
        $estilos = DB::table('CAT_estilos as CEST')
            ->select(
                'CEST.id',
                'CEST.codigo',
                'CLE.nombre as nombreCliente',
                'DIV.nombre as nombreDivision',
                'CAT.nombre as nombreSubcategoria'
            )
            ->leftJoin('CAT_clientes as CLE', 'CEST.idCliente', '=', 'CLE.id')
            ->leftJoin('CAT_division as DIV', 'CEST.idDivision', '=', 'DIV.id')
            ->leftJoin('CAT_subcategoria as CAT', 'CEST.idSubcategoria', '=', 'CAT.id')
            ->get();

        return view('estilos.index', compact('estilos', 'clientes', 'subcategoria', 'division'));
    }

    public function store(Request $request)
    {
        $validacionesInputs = [
            'codigo' => 'required|max:50',
            'idCliente' => 'required',
            'idDivision' => 'required',
            'idSubcategoria' => 'required',
            'referencia1' => 'nullable|max:150',
            'referencia2' => 'nullable|max:150',
            'descripcion' => 'required|max:100',
        ];

        $respuestaValidaciones = [
            'codigo.required' => 'El codigo es un valor requerido.',
            'codigo.max' => 'El codigo no debe exceder de 50 caracteres.',
            'idCliente.required' => 'El cliente es un valor requerido.',
            'idDivision.required' => 'La division es un valor requerido.',
            'idSubcategoria.required' => 'La subcategoria es un valor requerido.',
            'referencia1.max' => 'La referencia #1 no debe exceder de 150 caracteres.',
            'referencia2.max' => 'La referencia #2 no debe exceder de 150 caracteres.',
            'descripcion.required' => 'La descripción es un valor requerido.',
            'descripcion.max' => 'La descripción no debe exceder de 100 caracteres.'
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);

        $input = $request->all();

        $arrayCreate = [
            'codigo' => empty($input['codigo']) ? null : strtoupper($input['codigo']),
            'idCliente' => empty($input['idCliente']) ? null : $input['idCliente'],
            'idDivision' => empty($input['idDivision']) ? null : $input['idDivision'],
            'idSubcategoria' => empty($input['idSubcategoria']) ? null : $input['idSubcategoria'],
            'referencia1' => empty($input['referencia1']) ? null : strtoupper($input['referencia1']),
            'referencia2' => empty($input['referencia2']) ? null : strtoupper($input['referencia2']),
            'descripcion' => empty($input['descripcion']) ? null : strtoupper($input['descripcion'])
        ];

        Estilos::create($arrayCreate)->id;

        return redirect()->route('estilos.index')->with('success', 'Estilo grabado con éxito');
    }

    public function edit(Request $request, $id)
    {
        $clientes = DB::table('CAT_clientes')->get();
        $division = DB::table('CAT_division')->get();
        $subcategoria = DB::table('CAT_subcategoria')->get();
        $estilosEdit = Estilos::find($id);
        return view('estilos.edit', compact('estilosEdit', 'clientes', 'division', 'subcategoria'));
    }

    public function update(Request $request, $id)
    {
        $validacionesInputs = [
            'codigo' => 'nullable',
            'idCliente' => 'required',
            'idDivision' => 'required',
            'idSubcategoria' => 'required',
            'referencia1' => 'nullable|max:150',
            'referencia2' => 'nullable|max:150',
            'descripcion' => 'required|max:100'
        ];

        $respuestaValidaciones = [
            'idCliente.required' => 'El cliente es un valor requerido.',
            'idDivision.required' => 'La division es un valor requerido.',
            'idSubcategoria.required' => 'La subcategoria es un valor requerido.',
            'referencia1.max' => 'La referencia #1 no debe exceder de 150 caracteres.',
            'referencia2.max' => 'La referencia #2 no debe exceder de 150 caracteres.',
            'descripcion.required' => 'La descripción es un valor requerido.',
            'descripcion.max' => 'La descripción no debe exceder de 100 caracteres.'
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);
        $input = $request->all();

        $arrayUpdate = [
            'idCliente' => empty($input['idCliente']) ? null : $input['idCliente'],
            'idDivision' => empty($input['idDivision']) ? null : $input['idDivision'],
            'idSubcategoria' => empty($input['idSubcategoria']) ? null : $input['idSubcategoria'],
            'referencia1' => empty($input['referencia1']) ? null : strtoupper($input['referencia1']),
            'referencia2' => empty($input['referencia2']) ? null : strtoupper($input['referencia2']),
            'descripcion' => empty($input['descripcion']) ? null : strtoupper($input['descripcion'])
        ];

        $estilo = Estilos::find($id);
        $estilo->update($arrayUpdate);

        return redirect()->route('estilos.index')->with('success', 'Estilo editado con éxito');
    }

    public function destroy($id)
    {
        $registros = DB::table('tblProduccion')->where('idEstilo', '=', $id)->count();
        if ($registros == 0) {
            $estilos = Estilos::find($id);
            $estilos->delete();
        } else {
            return redirect()->route('estilos.index')->with('info', 'Este estilo está siendo usada por uno o más registros de producción, imposible eliminar.');;
        }
        return redirect()->route('estilos.index')->with('info', 'Estilo eliminado con éxito');
    }
}

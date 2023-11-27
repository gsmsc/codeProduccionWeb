<?php

namespace App\Http\Controllers;

use App\Models\Estilos;
use App\Models\Lineas;
use App\Models\Produccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduccionController extends Controller
{
    public function index()
    {
        $produccion = DB::table('tblProduccion as PRD')
            ->select(
                'PRD.id',
                'PRD.fecha',
                'CES.codigo',
                'CLI.descripcion as descripcionLinea',
                'CES.descripcion as descripcionEstilo',
            )
            ->leftJoin('CAT_lineas as CLI', 'PRD.idLinea', '=', 'CLI.id')
            ->leftJoin('CAT_estilos as CES', 'PRD.idEstilo', '=', 'CES.id')
            ->where('PRD.idUsuario', '=', Auth::user()->id)
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
        $validacionesInputs = [
            'fecha' => 'required',
            'idLinea' => 'required',
            'idEstilo' => 'required',
            'operariosNormal' => 'nullable|max:10',
            'operariosRefuerzos' => 'nullable|max:10',
            'uProducidas' => 'nullable|max:10',
            'uIrregulares' => 'nullable|max:10',
            'uRegulares' => 'nullable|max:10',
            'metaNormal' => 'nullable|max:5',
            'totalHorasOrdinarias' => 'nullable|max:7',
            'totalHorasExtras' => 'nullable|max:7',
            'totalHorasTrabajadas' => 'nullable|max:7',
            'horasNoProducidas' => 'nullable|max:7',
            'horasProducidas' => 'nullable|max:7',
            'metaAjustada' => 'nullable|max:10',
            'eficiencia' => 'nullable|max:10',
            'bonos' => 'nullable|max:10',
            'maquinaMala' => 'nullable|max:10',
            'noTrabajo' => 'nullable|max:10',
            'entrenamiento' => 'nullable|max:10',
            'cambioEstilo' => 'nullable|max:10',
            'observaciones' => 'nullable|max:250'
        ];

        $respuestaValidaciones = [
            'fecha.required' => 'La fecha es un valor requerido.',
            'idLinea.required' => 'La línea es un valor requerido.',
            'idEstilo.required' => 'El estilo es un valor requerido.',
            'operariosNormal.max' => 'El operario normal no debe exceder de 10 digitos.',
            'operariosRefuerzos.max' => 'El operario de refuerzos no debe exceder de 10 digitos.',
            'uProducidas.max' => 'La unidades producidas no debe exceder de 10 digitos.',
            'uIrregulares.max' => 'La unidades irregulares no debe exceder de 10 digitos.',
            'uRegulares.max' => 'La unidades regulares no debe exceder de 10 digitos.',
            'metaNormal.max' => 'La meta normal no debe exceder de 10 digitos.',
            'totalHorasOrdinarias.max' => 'La horas ordinarias no debe exceder de 7 caracteres.',
            'totalHorasExtras.max' => 'La horas extras no debe exceder de 7 caracteres.',
            'totalHorasTrabajadas.max' => 'La horas trabajadas no debe exceder de 7 caracteres.',
            'horasNoProducidas.max' => 'La horas no producidas no debe exceder de 7 caracteres.',
            'horasProducidas.max' => 'La horas producidas no debe exceder de 7 caracteres.',
            'metaAjustada.max' => 'La meta ajustado no debe exceder de 10 caracteres.',
            'eficiencia.max' => 'La eficiencia no debe exceder de 10 caracteres.',
            'bonos.max' => 'El bono no debe exceder de 10 caracteres.',
            'maquinaMala.max' => 'La hora de maquina mala no debe exceder de 10 caracteres.',
            'noTrabajo.max' => 'La hora de no trabajo no debe exceder de 10 caracteres.',
            'entrenamiento.max' => 'La hora de entrenamiento no debe exceder de 10 caracteres.',
            'cambioEstilo.max' => 'La hora de cambio de estilo no debe exceder de 10 caracteres.',
            'observaciones.max' => 'Las observaciones no debe exceder de 250 caracteres.'
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);

        $input = $request->all();
        $arrayCreate = [
            'idUsuario' => Auth::user()->id,
            'fecha' => $input['fecha'],
            'idLinea' => $input['idLinea'],
            'idEstilo' => $input['idEstilo'],
            'operariosNormal' => empty($input['operariosNormal']) ? null : $input['operariosNormal'],
            'operariosRefuerzos' => empty($input['operariosRefuerzos']) ? null : $input['operariosRefuerzos'],
            'uProducidas' => empty($input['uProducidas']) ? null : $input['uProducidas'],
            'uIrregulares' => empty($input['uIrregulares']) ? null : $input['uIrregulares'],
            'uRegulares' => empty($input['uRegulares']) ? null : $input['uRegulares'],
            'metaNormal' => empty($input['metaNormal']) ? null : $input['metaNormal'],
            'totalHorasOrdinarias' => empty($input['totalHorasOrdinarias']) ? null : $input['totalHorasOrdinarias'],
            'totalHorasExtras' => empty($input['totalHorasExtras']) ? null : $input['totalHorasExtras'],
            'totalHorasTrabajadas' => empty($input['totalHorasTrabajadas']) ? null : $input['totalHorasTrabajadas'],
            'horasNoProducidas' => empty($input['horasNoProducidas']) ? null : $input['horasNoProducidas'],
            'horasProducidas' => empty($input['horasProducidas']) ? null : $input['horasProducidas'],
            'metaAjustada' => empty($input['metaAjustada']) ? null : $input['metaAjustada'],
            'eficiencia' => empty($input['eficiencia']) ? null : $input['eficiencia'],
            'bonos' => empty($input['bonos']) ? null : $input['bonos'],
            'maquinaMala' => empty($input['maquinaMala']) ? null : $input['maquinaMala'],
            'noTrabajo' => empty($input['noTrabajo']) ? null : $input['noTrabajo'],
            'entrenamiento' => empty($input['entrenamiento']) ? null : $input['entrenamiento'],
            'cambioEstilo' => empty($input['cambioEstilo']) ? null : $input['cambioEstilo'],
            'observaciones' => $input['observaciones']
        ];

        Produccion::create($arrayCreate);

        return redirect()->route('produccion.index')->with('success', 'Producción grabada con éxito');
    }

    public function edit($id)
    {
        $lineas = Lineas::all();
        $estilos = Estilos::all();
        $produccionEdit = Produccion::find($id);
        return view('produccion.edit', compact('lineas', 'estilos', 'produccionEdit'));
    }

    public function update(Request $request, $id)
    {
        $validacionesInputs = [
            'fecha' => 'required',
            'idLinea' => 'required',
            'idEstilo' => 'required',
            'operariosNormal' => 'nullable|max:10',
            'operariosRefuerzos' => 'nullable|max:10',
            'uProducidas' => 'nullable|max:10',
            'uIrregulares' => 'nullable|max:10',
            'uRegulares' => 'nullable|max:10',
            'metaNormal' => 'nullable|max:5',
            'totalHorasOrdinarias' => 'nullable|max:7',
            'totalHorasExtras' => 'nullable|max:7',
            'totalHorasTrabajadas' => 'nullable|max:7',
            'horasNoProducidas' => 'nullable|max:7',
            'horasProducidas' => 'nullable|max:7',
            'metaAjustada' => 'nullable|max:10',
            'eficiencia' => 'nullable|max:10',
            'bonos' => 'nullable|max:10',
            'maquinaMala' => 'nullable|max:10',
            'noTrabajo' => 'nullable|max:10',
            'entrenamiento' => 'nullable|max:10',
            'cambioEstilo' => 'nullable|max:10',
            'observaciones' => 'nullable|max:250'
        ];

        $respuestaValidaciones = [
            'fecha.required' => 'La fecha es un valor requerido.',
            'idLinea.required' => 'La línea es un valor requerido.',
            'idEstilo.required' => 'El estilo es un valor requerido.',
            'operariosNormal.max' => 'El operario normal no debe exceder de 10 digitos.',
            'operariosRefuerzos.max' => 'El operario de refuerzos no debe exceder de 10 digitos.',
            'uProducidas.max' => 'La unidades producidas no debe exceder de 10 digitos.',
            'uIrregulares.max' => 'La unidades irregulares no debe exceder de 10 digitos.',
            'uRegulares.max' => 'La unidades regulares no debe exceder de 10 digitos.',
            'metaNormal.max' => 'La meta normal no debe exceder de 10 digitos.',
            'totalHorasOrdinarias.max' => 'La horas ordinarias no debe exceder de 7 caracteres.',
            'totalHorasExtras.max' => 'La horas extras no debe exceder de 7 caracteres.',
            'totalHorasTrabajadas.max' => 'La horas trabajadas no debe exceder de 7 caracteres.',
            'horasNoProducidas.max' => 'La horas no producidas no debe exceder de 7 caracteres.',
            'horasProducidas.max' => 'La horas producidas no debe exceder de 7 caracteres.',
            'metaAjustada.max' => 'La meta ajustado no debe exceder de 10 caracteres.',
            'eficiencia.max' => 'La eficiencia no debe exceder de 10 caracteres.',
            'bonos.max' => 'El bono no debe exceder de 10 caracteres.',
            'maquinaMala.max' => 'La hora de maquina mala no debe exceder de 10 caracteres.',
            'noTrabajo.max' => 'La hora de no trabajo no debe exceder de 10 caracteres.',
            'entrenamiento.max' => 'La hora de entrenamiento no debe exceder de 10 caracteres.',
            'cambioEstilo.max' => 'La hora de cambio de estilo no debe exceder de 10 caracteres.',
            'observaciones.max' => 'Las observaciones no debe exceder de 250 caracteres.'
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);

        $input = $request->all();
        $arrayUpdate = [
            'fecha' => $input['fecha'],
            'idLinea' => $input['idLinea'],
            'idEstilo' => $input['idEstilo'],
            'operariosNormal' => empty($input['operariosNormal']) ? null : $input['operariosNormal'],
            'operariosRefuerzos' => empty($input['operariosRefuerzos']) ? null : $input['operariosRefuerzos'],
            'uProducidas' => empty($input['uProducidas']) ? null : $input['uProducidas'],
            'uIrregulares' => empty($input['uIrregulares']) ? null : $input['uIrregulares'],
            'uRegulares' => empty($input['uRegulares']) ? null : $input['uRegulares'],
            'metaNormal' => empty($input['metaNormal']) ? null : $input['metaNormal'],
            'totalHorasOrdinarias' => empty($input['totalHorasOrdinarias']) ? null : $input['totalHorasOrdinarias'],
            'totalHorasExtras' => empty($input['totalHorasExtras']) ? null : $input['totalHorasExtras'],
            'totalHorasTrabajadas' => empty($input['totalHorasTrabajadas']) ? null : $input['totalHorasTrabajadas'],
            'horasNoProducidas' => empty($input['horasNoProducidas']) ? null : $input['horasNoProducidas'],
            'horasProducidas' => empty($input['horasProducidas']) ? null : $input['horasProducidas'],
            'metaAjustada' => empty($input['metaAjustada']) ? null : $input['metaAjustada'],
            'eficiencia' => empty($input['eficiencia']) ? null : $input['eficiencia'],
            'bonos' => empty($input['bonos']) ? null : $input['bonos'],
            'maquinaMala' => empty($input['maquinaMala']) ? null : $input['maquinaMala'],
            'noTrabajo' => empty($input['noTrabajo']) ? null : $input['noTrabajo'],
            'entrenamiento' => empty($input['entrenamiento']) ? null : $input['entrenamiento'],
            'cambioEstilo' => empty($input['cambioEstilo']) ? null : $input['cambioEstilo'],
            'observaciones' => $input['observaciones']
        ];

        $updateData = Produccion::find($id);
        $updateData->update($arrayUpdate);

        return redirect()->route('produccion.index')->with('success', 'Producción editada con éxito');
    }

    public function destroy($id)
    {
        $registro = Produccion::find($id);
        $registro->delete();

        return redirect()->route('produccion.index')->with('success', 'Producción eliminada con éxito');
    }
}

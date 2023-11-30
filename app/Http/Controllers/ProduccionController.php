<?php

namespace App\Http\Controllers;

use App\Models\Estilos;
use App\Models\Lineas;
use App\Models\Produccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use Illuminate\Support\Facades\DB;
use TCPDF;

class ProduccionController extends Controller
{
    function __construct()
    {
        $this->middleware('can:Produccion.Index', ['only' => ['index']]);
        $this->middleware('can:Produccion.Crear', ['only' => ['create', 'store']]);
        $this->middleware('can:Produccion.Editar', ['only' => ['edit', 'update']]);
        $this->middleware('can:Produccion.Eliminar', ['only' => ['destroy']]);
        $this->middleware('can:Produccion.ReporteExcel', ['only' => ['xlsxPorSupervisor']]);
        $this->middleware('can:Produccion.ReportePDF', ['only' => ['pdfProduccion']]);
    }
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

    public function listProductionUsers(Request $request)
    {
        $supervisores = DB::table('users as USR')
            ->select(
                'USR.id',
                'USR.name',
                'USR.email',
                'RL.name as nombreRol'
            )
            ->leftJoin('roles as RL', 'USR.idRol', '=', 'RL.id')
            ->get();
        return view('produccion.listProductionUsers', compact('supervisores'));
    }

    public function getProductionUsers($idUsuario)
    {
        $dataSupervisor = DB::table('tblProduccion as PROD')
            ->select(
                'PROD.id',
                'PROD.idUsuario',
                'USR.name',
                'PROD.fecha',
                'EST.codigo',
                'EST.descripcion as descripcionEstilo',
                'LIN.descripcion as descripcionLinea'
            )
            ->leftJoin('users as USR', 'PROD.idUsuario', '=', 'USR.id')
            ->leftJoin('CAT_lineas as LIN', 'PROD.idLinea', '=', 'LIN.id')
            ->leftJoin('CAT_estilos as EST', 'PROD.idEstilo', '=', 'EST.id')
            ->where('PROD.idUsuario', '=', $idUsuario)
            ->get();

        $usuario = User::find($idUsuario);
        return view('produccion.getProductionUsers', compact('dataSupervisor', 'usuario', 'idUsuario'));
    }

    public function xlsxPorSupervisor($idUsuario)
    {
        $registros = Produccion::all()->where('idUsuario', '=', $idUsuario);
        if ($registros->isEmpty()) {
            return redirect()->back()->with('info', 'No existen datos que exportar.');
        }

        $data = DB::table('tblProduccion as PROD')
            ->select(
                'PROD.id',
                'USR.name',
                'PROD.fecha',
                'EST.descripcion as descripcionEstilo',
                'LIN.descripcion as descripcionLinea',
                'PROD.operariosNormal',
                'PROD.operariosRefuerzos',
                'PROD.uProducidas',
                'PROD.uIrregulares',
                'PROD.uRegulares',
                'PROD.metaNormal',
                'PROD.totalHorasOrdinarias',
                'PROD.totalHorasExtras',
                'PROD.totalHorasTrabajadas',
                'PROD.horasNoProducidas',
                'PROD.horasProducidas',
                'PROD.metaAjustada',
                'PROD.eficiencia',
                'PROD.bonos',
                'PROD.maquinaMala',
                'PROD.noTrabajo',
                'PROD.entrenamiento',
                'PROD.cambioEstilo',
                'PROD.observaciones'
            )
            ->leftJoin('users as USR', 'PROD.idUsuario', '=', 'USR.id')
            ->leftJoin('CAT_lineas as LIN', 'PROD.idLinea', '=', 'LIN.id')
            ->leftJoin('CAT_estilos as EST', 'PROD.idEstilo', '=', 'EST.id')
            ->where('PROD.idUsuario', '=', $idUsuario)
            ->get();
        $usuario = User::find($idUsuario);

        $row = 1;
        $hora = Carbon::now()->format('d-m-Y - H:i:s A');
        $fileName = "Reporte de producción de " . $usuario->name . " $hora.xlsx";
        $tituloReporte = "WELLS APPAREL Nicaragua, S.A.";
        $subtituloFecha = "Fecha y hora de exportación: " . $hora;

        $styleArrayHead = [
            'font' => [
                'bold' => true,
                'size'  => 22,
                'name'  => 'Arial',
                'color' => array('rgb' => '023554'),
            ]
        ];

        $styleArrayTitle = [
            'font' => [
                'bold' => true,
                'size'  => 20,
                'name'  => 'Arial'
            ]
        ];

        $arrayCentered = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle("Reporte");
        $spreadsheet->getActiveSheet()->setTitle('Listado');

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('LogoRB');
        $drawing->setDescription('LogoWELLS');
        $drawing->setPath('assets/LogoWELLS.jpeg');
        $drawing->setCoordinates('A' . $row);
        $drawing->setWidthAndHeight(128, 101);
        $drawing->setOffsetX(65);
        $drawing->setOffsetY(22);
        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $spreadsheet->getActiveSheet()->mergeCells('D2:U2');
        $spreadsheet->getActiveSheet()->mergeCells('D3:U3');

        $spreadsheet->getActiveSheet()->getStyle('D2:U2')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('D2:U2')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getStyle('D3:U3')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('D3:U3')->getAlignment()->setVertical('center');

        $spreadsheet->getActiveSheet()->getStyle('D2:U2')->applyFromArray($styleArrayHead);
        $spreadsheet->getActiveSheet()->getStyle('D3:U3')->applyFromArray($styleArrayTitle);

        $spreadsheet->getActiveSheet()->setCellValue('D2', $tituloReporte);
        $spreadsheet->getActiveSheet()->setCellValue('D3', $subtituloFecha);

        $spreadsheet->getActiveSheet()->setCellValue('B5', 'Fecha');
        $spreadsheet->getActiveSheet()->setCellValue('C5', 'Línea');
        $spreadsheet->getActiveSheet()->setCellValue('D5', 'Estilo');
        $spreadsheet->getActiveSheet()->setCellValue('E5', 'Operario normal');
        $spreadsheet->getActiveSheet()->setCellValue('F5', 'Operario refuerzo');
        $spreadsheet->getActiveSheet()->setCellValue('G5', 'U. Producidas');
        $spreadsheet->getActiveSheet()->setCellValue('H5', 'U. Irregulares');
        $spreadsheet->getActiveSheet()->setCellValue('I5', 'Meta normal');
        $spreadsheet->getActiveSheet()->setCellValue('J5', 'Total hrs. ordinarias');
        $spreadsheet->getActiveSheet()->setCellValue('K5', 'Total hrs. extras');
        $spreadsheet->getActiveSheet()->setCellValue('L5', 'Total hrs. trabajadas');
        $spreadsheet->getActiveSheet()->setCellValue('M5', 'Hrs. no producidas');
        $spreadsheet->getActiveSheet()->setCellValue('N5', 'Hrs. producidas');
        $spreadsheet->getActiveSheet()->setCellValue('O5', 'Meta ajustada');
        $spreadsheet->getActiveSheet()->setCellValue('P5', 'Eficiencia');
        $spreadsheet->getActiveSheet()->setCellValue('Q5', 'Bonos');
        $spreadsheet->getActiveSheet()->setCellValue('R5', 'Maquina mala');
        $spreadsheet->getActiveSheet()->setCellValue('S5', 'No trabajo');
        $spreadsheet->getActiveSheet()->setCellValue('T5', 'Entrenamiento');
        $spreadsheet->getActiveSheet()->setCellValue('U5', 'Cambio estilo');
        $spreadsheet->getActiveSheet()->setCellValue('V5', 'Observaciones');

        $spreadsheet->getActiveSheet()->getStyle('B5:V5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5B7786');
        $spreadsheet->getActiveSheet()->getStyle('B5:V5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(22);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(19);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(19);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(24);
        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(13);
        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(19);
        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(19);
        $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(70);

        $filaRow = 6;
        foreach ($data as $item) {
            $spreadsheet->getActiveSheet()->setCellValue('B' . $filaRow, $item->fecha)->getStyle('B')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('C' . $filaRow, $item->descripcionLinea);
            $spreadsheet->getActiveSheet()->setCellValue('D' . $filaRow, $item->descripcionEstilo);
            $spreadsheet->getActiveSheet()->setCellValue('E' . $filaRow, empty($item->operariosNormal) ? 'N/A' : $item->operariosNormal)->getStyle('E')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('F' . $filaRow, empty($item->operariosRefuerzos) ? 'N/A' : $item->operariosRefuerzos)->getStyle('F')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('G' . $filaRow, empty($item->uProducidas) ? 'N/A' : $item->uProducidas)->getStyle('G')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('H' . $filaRow, empty($item->uIrregulares) ? 'N/A' : $item->uIrregulares)->getStyle('H')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('I' . $filaRow, empty($item->uRegulares) ? 'N/A' : $item->uRegulares)->getStyle('I')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('J' . $filaRow, empty($item->metaNormal) ? 'N/A' : $item->metaNormal)->getStyle('J')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('K' . $filaRow, empty($item->totalHorasOrdinarias) ? 'N/A' : $item->totalHorasOrdinarias)->getStyle('K')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('L' . $filaRow, empty($item->totalHorasExtras) ? 'N/A' : $item->totalHorasExtras)->getStyle('L')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('M' . $filaRow, empty($item->totalHorasTrabajadas) ? 'N/A' : $item->totalHorasTrabajadas)->getStyle('M')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('N' . $filaRow, empty($item->horasNoProducidas) ? 'N/A' : $item->horasNoProducidas)->getStyle('N')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('O' . $filaRow, empty($item->horasProducidas) ? 'N/A' : $item->horasProducidas)->getStyle('O')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('P' . $filaRow, empty($item->metaAjustada) ? 'N/A' : $item->metaAjustada)->getStyle('P')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('Q' . $filaRow, empty($item->eficiencia) ? 'N/A' : $item->eficiencia)->getStyle('Q')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('R' . $filaRow, empty($item->bonos) ? 'N/A' : $item->bonos)->getStyle('R')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('S' . $filaRow, empty($item->maquinaMala) ? 'N/A' : $item->maquinaMala)->getStyle('S')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('T' . $filaRow, empty($item->noTrabajo) ? 'N/A' : $item->noTrabajo)->getStyle('T')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('U' . $filaRow, empty($item->entrenamiento) ? 'N/A' : $item->entrenamiento)->getStyle('U')->applyFromArray($arrayCentered);
            $spreadsheet->getActiveSheet()->setCellValue('V' . $filaRow, strtoupper($item->observaciones));
            $filaRow = $filaRow + 1;
        }

        $filaRow = $filaRow - 1;

        $table = new Table('B5:V' . $filaRow, '');
        $tableStyle = new TableStyle();
        $tableStyle->setTheme(TableStyle::TABLE_STYLE_LIGHT1);
        $tableStyle->setShowRowStripes(true);
        $tableStyle->setShowFirstColumn(true);
        $table->setStyle($tableStyle);
        $spreadsheet->getActiveSheet()->addTable($table);

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');


        $writer = new XLsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save("php://output");
    }

    public function pdfProduccion($idProduccion)
    {
        $pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $hora = Carbon::now()->format('d-m-Y - H:i:s A');
        $pdf->setPrintHeader(false);
        $registro = DB::table('tblProduccion as PROD')
            ->select(
                'PROD.id',
                'PROD.idUsuario',
                'USR.name',
                'PROD.fecha',
                'EST.codigo',
                'EST.descripcion as descripcionEstilo',
                'LIN.descripcion as descripcionLinea',
                'PROD.operariosNormal',
                'PROD.operariosRefuerzos',
                'PROD.uProducidas',
                'PROD.uIrregulares',
                'PROD.uRegulares',
                'PROD.metaNormal',
                'PROD.totalHorasOrdinarias',
                'PROD.totalHorasExtras',
                'PROD.totalHorasTrabajadas',
                'PROD.horasNoProducidas',
                'PROD.horasProducidas',
                'PROD.metaAjustada',
                'PROD.eficiencia',
                'PROD.bonos',
                'PROD.maquinaMala',
                'PROD.noTrabajo',
                'PROD.entrenamiento',
                'PROD.cambioEstilo',
                'PROD.observaciones'
            )
            ->leftJoin('users as USR', 'PROD.idUsuario', '=', 'USR.id')
            ->leftJoin('CAT_lineas as LIN', 'PROD.idLinea', '=', 'LIN.id')
            ->leftJoin('CAT_estilos as EST', 'PROD.idEstilo', '=', 'EST.id')
            ->where('PROD.id', '=', $idProduccion)
            ->get();

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('WELLS APPAREL Nicaragua S.A.');
        $pdf->SetTitle('Produccion #' . $idProduccion . '  ' . $hora);

        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetMargins(PDF_MARGIN_LEFT, false, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(false);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetFont('helvetica', '', 210);

        $pdf->AddPage();
        $logo1 = $pdf->Image('assets/LogoWELLS.jpeg', 14.8, 11, 50, 25, 'JPEG', '', 'C', false, 300, '', false, false, 1, false, false, false);
        $pdf->headerPDF($logo1);
        $dataProduccion = $pdf->LoadData($registro);
        $pdf->section1($dataProduccion);

        $pdf->Output('Produccion #' . $idProduccion . ' - ' . $hora . '.pdf', 'I');
    }

    public function xlsxPorFecha(Request $request)
    {
        if ($request->formato == 'EXCEL') {

            $data = DB::table('tblProduccion as PROD')
                ->select(
                    'PROD.id',
                    'USR.name',
                    'PROD.fecha',
                    'EST.descripcion as descripcionEstilo',
                    'LIN.descripcion as descripcionLinea',
                    'PROD.operariosNormal',
                    'PROD.operariosRefuerzos',
                    'PROD.uProducidas',
                    'PROD.uIrregulares',
                    'PROD.uRegulares',
                    'PROD.metaNormal',
                    'PROD.totalHorasOrdinarias',
                    'PROD.totalHorasExtras',
                    'PROD.totalHorasTrabajadas',
                    'PROD.horasNoProducidas',
                    'PROD.horasProducidas',
                    'PROD.metaAjustada',
                    'PROD.eficiencia',
                    'PROD.bonos',
                    'PROD.maquinaMala',
                    'PROD.noTrabajo',
                    'PROD.entrenamiento',
                    'PROD.cambioEstilo',
                    'PROD.observaciones'

                )
                ->leftJoin('users as USR', 'PROD.idUsuario', '=', 'USR.id')
                ->leftJoin('CAT_lineas as LIN', 'PROD.idLinea', '=', 'LIN.id')
                ->leftJoin('CAT_estilos as EST', 'PROD.idEstilo', '=', 'EST.id')
                ->whereBetween('PROD.created_at', [$request->dtInicio, $request->dtFinal])
                ->where('PROD.idUsuario', '=', $request->idUsuario)
                ->get();

            if (count($data) == 0) {
                return redirect()->back()->with('info', 'No existen registros en este rango de fechas');
            }

            $usuario = User::find($request->idUsuario);

            $row = 1;
            $hora = Carbon::now()->format('d-m-Y - H:i:s A');
            $fileName = "Reporte de producción de " . $usuario->name . " $hora.xlsx";
            $tituloReporte = "WELLS APPAREL Nicaragua, S.A.";
            $subtituloFecha = "Reporte de producción del " . date("d-m-Y", strtotime($request->dtInicio)) . ' al ' . date("d-m-Y", strtotime($request->dtFinal)) . ' del supervisor ' . $usuario->name;

            $styleArrayHead = [
                'font' => [
                    'bold' => true,
                    'size'  => 22,
                    'name'  => 'Arial',
                    'color' => array('rgb' => '023554'),
                ]
            ];

            $styleArrayTitle = [
                'font' => [
                    'bold' => true,
                    'size'  => 20,
                    'name'  => 'Arial'
                ]
            ];

            $arrayCentered = [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ];

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setTitle("Reporte");
            $spreadsheet->getActiveSheet()->setTitle('Listado');

            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('LogoRB');
            $drawing->setDescription('LogoWELLS');
            $drawing->setPath('assets/LogoWELLS.jpeg');
            $drawing->setCoordinates('A' . $row);
            $drawing->setWidthAndHeight(128, 101);
            $drawing->setOffsetX(65);
            $drawing->setOffsetY(22);
            $drawing->getShadow()->setVisible(true);
            $drawing->getShadow()->setDirection(45);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());

            $spreadsheet->getActiveSheet()->mergeCells('D2:U2');
            $spreadsheet->getActiveSheet()->mergeCells('D3:U3');

            $spreadsheet->getActiveSheet()->getStyle('D2:U2')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle('D2:U2')->getAlignment()->setVertical('center');
            $spreadsheet->getActiveSheet()->getStyle('D3:U3')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getStyle('D3:U3')->getAlignment()->setVertical('center');

            $spreadsheet->getActiveSheet()->getStyle('D2:U2')->applyFromArray($styleArrayHead);
            $spreadsheet->getActiveSheet()->getStyle('D3:U3')->applyFromArray($styleArrayTitle);

            $spreadsheet->getActiveSheet()->setCellValue('D2', $tituloReporte);
            $spreadsheet->getActiveSheet()->setCellValue('D3', $subtituloFecha);

            $spreadsheet->getActiveSheet()->setCellValue('B5', 'Fecha');
            $spreadsheet->getActiveSheet()->setCellValue('C5', 'Línea');
            $spreadsheet->getActiveSheet()->setCellValue('D5', 'Estilo');
            $spreadsheet->getActiveSheet()->setCellValue('E5', 'Operario normal');
            $spreadsheet->getActiveSheet()->setCellValue('F5', 'Operario refuerzo');
            $spreadsheet->getActiveSheet()->setCellValue('G5', 'U. Producidas');
            $spreadsheet->getActiveSheet()->setCellValue('H5', 'U. Irregulares');
            $spreadsheet->getActiveSheet()->setCellValue('I5', 'Meta normal');
            $spreadsheet->getActiveSheet()->setCellValue('J5', 'Total hrs. ordinarias');
            $spreadsheet->getActiveSheet()->setCellValue('K5', 'Total hrs. extras');
            $spreadsheet->getActiveSheet()->setCellValue('L5', 'Total hrs. trabajadas');
            $spreadsheet->getActiveSheet()->setCellValue('M5', 'Hrs. no producidas');
            $spreadsheet->getActiveSheet()->setCellValue('N5', 'Hrs. producidas');
            $spreadsheet->getActiveSheet()->setCellValue('O5', 'Meta ajustada');
            $spreadsheet->getActiveSheet()->setCellValue('P5', 'Eficiencia');
            $spreadsheet->getActiveSheet()->setCellValue('Q5', 'Bonos');
            $spreadsheet->getActiveSheet()->setCellValue('R5', 'Maquina mala');
            $spreadsheet->getActiveSheet()->setCellValue('S5', 'No trabajo');
            $spreadsheet->getActiveSheet()->setCellValue('T5', 'Entrenamiento');
            $spreadsheet->getActiveSheet()->setCellValue('U5', 'Cambio estilo');
            $spreadsheet->getActiveSheet()->setCellValue('V5', 'Observaciones');

            $spreadsheet->getActiveSheet()->getStyle('B5:V5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5B7786');
            $spreadsheet->getActiveSheet()->getStyle('B5:V5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(22);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(19);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(19);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(23);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(24);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('R')->setWidth(19);
            $spreadsheet->getActiveSheet()->getColumnDimension('S')->setWidth(16);
            $spreadsheet->getActiveSheet()->getColumnDimension('T')->setWidth(19);
            $spreadsheet->getActiveSheet()->getColumnDimension('U')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('V')->setWidth(70);

            $filaRow = 6;
            foreach ($data as $item) {
                $spreadsheet->getActiveSheet()->setCellValue('B' . $filaRow, $item->fecha)->getStyle('B')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $filaRow, $item->descripcionLinea);
                $spreadsheet->getActiveSheet()->setCellValue('D' . $filaRow, $item->descripcionEstilo);
                $spreadsheet->getActiveSheet()->setCellValue('E' . $filaRow, empty($item->operariosNormal) ? 'N/A' : $item->operariosNormal)->getStyle('E')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('F' . $filaRow, empty($item->operariosRefuerzos) ? 'N/A' : $item->operariosRefuerzos)->getStyle('F')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('G' . $filaRow, empty($item->uProducidas) ? 'N/A' : $item->uProducidas)->getStyle('G')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('H' . $filaRow, empty($item->uIrregulares) ? 'N/A' : $item->uIrregulares)->getStyle('H')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('I' . $filaRow, empty($item->uRegulares) ? 'N/A' : $item->uRegulares)->getStyle('I')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('J' . $filaRow, empty($item->metaNormal) ? 'N/A' : $item->metaNormal)->getStyle('J')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('K' . $filaRow, empty($item->totalHorasOrdinarias) ? 'N/A' : $item->totalHorasOrdinarias)->getStyle('K')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('L' . $filaRow, empty($item->totalHorasExtras) ? 'N/A' : $item->totalHorasExtras)->getStyle('L')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('M' . $filaRow, empty($item->totalHorasTrabajadas) ? 'N/A' : $item->totalHorasTrabajadas)->getStyle('M')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('N' . $filaRow, empty($item->horasNoProducidas) ? 'N/A' : $item->horasNoProducidas)->getStyle('N')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('O' . $filaRow, empty($item->horasProducidas) ? 'N/A' : $item->horasProducidas)->getStyle('O')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('P' . $filaRow, empty($item->metaAjustada) ? 'N/A' : $item->metaAjustada)->getStyle('P')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('Q' . $filaRow, empty($item->eficiencia) ? 'N/A' : $item->eficiencia)->getStyle('Q')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('R' . $filaRow, empty($item->bonos) ? 'N/A' : $item->bonos)->getStyle('R')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('S' . $filaRow, empty($item->maquinaMala) ? 'N/A' : $item->maquinaMala)->getStyle('S')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('T' . $filaRow, empty($item->noTrabajo) ? 'N/A' : $item->noTrabajo)->getStyle('T')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('U' . $filaRow, empty($item->entrenamiento) ? 'N/A' : $item->entrenamiento)->getStyle('U')->applyFromArray($arrayCentered);
                $spreadsheet->getActiveSheet()->setCellValue('V' . $filaRow, strtoupper($item->observaciones));
                $filaRow = $filaRow + 1;
            }

            $filaRow = $filaRow - 1;

            $table = new Table('B5:V' . $filaRow, '');
            $tableStyle = new TableStyle();
            $tableStyle->setTheme(TableStyle::TABLE_STYLE_LIGHT1);
            $tableStyle->setShowRowStripes(true);
            $tableStyle->setShowFirstColumn(true);
            $table->setStyle($tableStyle);
            $spreadsheet->getActiveSheet()->addTable($table);

            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header('Content-Disposition: attachment; filename="' . $fileName . '"');

            $writer = new XLsx($spreadsheet);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save("php://output");
        }
    }
}

class MYPDF extends TCPDF
{
    public function headerPDF($logo1)
    {
        if (!empty($logo1)) {
            $img = file_get_contents(public_path($logo1));
            $this->Image($img, 0, $this->GetY(), 0, 0,  'JPEG', null,  '',  false,  300,  'C',  false, false, 1);
            $this->SetY(22);
        }
        $this->Ln(13);
        $this->SetTextColor(27, 27, 27);
        $this->SetFont('Helvetica', 'B', 22);
        $this->Cell(50, 8, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(130, 8, 'Wells Apparel Nicaragua, S.A.', 0, 0, 'C', 0, '', 0);
        $this->Ln(10);
        $this->SetTextColor(105, 105, 105);
        $this->SetFont('Helvetica', '', 14);
        $this->Cell(50, 8, '', 0, 0, 'C', 0, '', 0);
        $this->Cell(130, 8, 'Reporte de producción diaria (Base de datos)', 0, 0, 'C', 0, '', 0);

        $this->setX(45);
        $this->SetFont('Helvetica', '', 7.5);
        $celdas = $this->MultiCell(120, 0, '', 0, 'C', 0, 0);

        $y = $this->getY();
        $this->setY($y + (4 * $celdas));
    }

    public function LoadData($datos)
    {
        $lines = $datos;
        $data = array();
        foreach ($lines as $line) {
            $data[] = $line;
        }
        return $data;
    }

    public function section1($dataProduccion)
    {
        $this->Ln(13);
        $this->SetFont('times', 'B');
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('times', '', 12, 'B', true);
        $this->Cell(75, 8, 'FECHA ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8,  date("d/m/Y", strtotime($dataProduccion[0]->fecha)), 1, 0, 'C', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'LÍNEA/DEPARTAMENTO ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->descripcionLinea, 1, 0, 'C', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'ESTILO ', 1, 0, 'R', 1, '', 0);
        $this->Cell(90, 8, $dataProduccion[0]->descripcionEstilo, 1, 0, 'C', 1, '', 0);
        $this->Cell(15, 8, '', 1, 0, 'C', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'OPERARIOS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(35, 8, 'NORMAL: ' . $dataProduccion[0]->operariosNormal, 1, 0, 'L', 1, '', 0);
        $this->Cell(70, 8, 'REFUERZOS: ' . $dataProduccion[0]->operariosRefuerzos, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'UNIDADES PRODUCIDAS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->uProducidas, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'MENOS: UNIDADES IRREGULARES ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->uIrregulares, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'UNIDADES REGULARES ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->uRegulares, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'META NORMAL ', 1, 0, 'R', 1, '', 0);
        $this->Cell(90, 8, $dataProduccion[0]->metaNormal, 1, 0, 'L', 1, '', 0);
        $this->Cell(15, 8, '', 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'TOTAL HORAS ORDINARIAS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->totalHorasOrdinarias, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'TOTAL HORAS EXTRAS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->totalHorasExtras, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'TOTAL HORAS TRABAJADAS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->totalHorasTrabajadas, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'MENOS: HORAS NO PRODUCIDAS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->horasNoProducidas, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'TOTAL HORAS PRODUCIDAS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->horasProducidas, 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'META AJUSTADA ', 1, 0, 'R', 1, '', 0);
        $this->Cell(90, 8, $dataProduccion[0]->metaAjustada, 1, 0, 'L', 1, '', 0);
        $this->Cell(15, 8, '', 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'EFICIENCIA ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->eficiencia . '%', 1, 0, 'L', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'BONOS ', 1, 0, 'R', 1, '', 0);
        $this->Cell(105, 8, $dataProduccion[0]->bonos, 1, 0, 'L', 1, '', 0);
        $this->Ln(10);
        $this->Cell(180, 8, 'HORAS NO PRODUCIDAS', 0, 0, 'C', 1, '', 0);
        $this->Ln(10);
        $this->Cell(75, 8, 'MAQUINA MALA ', 1, 0, 'R', 1, '', 0);
        $this->Cell(90, 8, $dataProduccion[0]->maquinaMala, 1, 0, 'L', 1, '', 0);
        $this->Cell(15, 8, 'HORA', 1, 0, 'C', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'NO TRABAJO ', 1, 0, 'R', 1, '', 0);
        $this->Cell(90, 8, $dataProduccion[0]->noTrabajo, 1, 0, 'L', 1, '', 0);
        $this->Cell(15, 8, 'HORA', 1, 0, 'C', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'ENTRENAMIENTO ', 1, 0, 'R', 1, '', 0);
        $this->Cell(90, 8, $dataProduccion[0]->entrenamiento, 1, 0, 'L', 1, '', 0);
        $this->Cell(15, 8, 'HORA', 1, 0, 'C', 1, '', 0);
        $this->Ln(8);
        $this->Cell(75, 8, 'CAMBIO DE ESTILO ', 1, 0, 'R', 1, '', 0);
        $this->Cell(90, 8, $dataProduccion[0]->cambioEstilo, 1, 0, 'L', 1, '', 0);
        $this->Cell(15, 8, 'HORA', 1, 0, 'C', 1, '', 0);
        $this->Ln(10);
        $this->Cell(180, 8, 'OBSERVACIONES', 0, 0, 'C', 1, '', 0);
        $this->Ln(10);
        $this->MultiCell(180, 20, ' ' . strtoupper($dataProduccion[0]->observaciones), 1, '', 0, 1, '', '', true);
        $this->Ln(20);

        $centerX = $this->GetX();
        $centerY = $this->GetY();

        $lineStartX = $centerX + 60;
        $lineEndX = $centerX + 120;
        $this->Cell(180, 8, $dataProduccion[0]->name, 0, 0, 'C', 1, '', 0);
        $this->Line($lineStartX, $centerY, $lineEndX, $centerY);
    }
}

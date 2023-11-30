@extends('adminlte::page')

@section('title', 'Producción')

@section('content')
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="container-title">
                    <h3>Listado de producci&oacute;n</h3>
                </div>
            </div>
            <div class="col-sm-6">
                @can('Produccion.Crear')
                <a href="{{ route('produccion.create') }}" class="btn btn-dark" style="float: right; margin-left:10px;">Registrar
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                    </svg>
                </a>
                @endcan
            </div>
        </div>
        <br>
        @if ($errors->any())
        <div id="sectionErrors" class="form-group alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closeErrors">
                <span aria-hidden="true" style="color: white;">&times;</span>
            </button>
            <label>Corrija los siguientes errores:</label>
            <ul style="margin-bottom: 0px !important;">
                @foreach ($errors->all() as $error)
                <li class="">{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive-md">
                        <table class="table table-striped table-hover table-bordered mt-2 text-center" id="tblProduccion" style="width: 100%;">
                            <thead>
                                <th width="6%">Id</th>
                                <th width="10%">Fecha</th>
                                <th>L&iacute;nea</th>
                                <th>Estilo</th>
                                <th width="13%">Acciones</th>
                            </thead>
                            <tbody>
                                @foreach($produccion as $items)
                                <tr>
                                    <td>{{ $items->id }}</td>
                                    <td>{{ date("d-m-Y", strtotime($items->fecha)) }}</td>
                                    <td>{{ strtoupper($items->descripcionLinea) }}</td>
                                    <td>{{ $items->codigo }} - {{ strtoupper($items->descripcionEstilo) }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('produccion.destroy', $items->id) }}" method="POST" class="formEliminarRegistro">
                                            @method('DELETE')
                                            @csrf
                                            @can('Produccion.ReportePDF')
                                            <!-- <a href="" class="btn btn-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Visualizar formato" target="_blank">
                                                <i class="fas fa-file-pdf"></i>
                                            </a> -->
                                            @endcan
                                            @can('Produccion.Editar')
                                            <a href="{{ route('produccion.edit', $items->id) }}" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                </svg>
                                            </a>
                                            @endcan
                                            @can('Produccion.Eliminar')
                                            <button class="btn btn-dark btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                </svg>
                                            </button>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    $(document).ready(function() {
        $('#tblProduccion').DataTable({
            "lengthMenu": [
                [5, 10, 50, -1],
                [5, 10, 50, "Todos"]
            ],
            columns: [{
                    orderable: true
                },
                {
                    orderable: true
                },
                {
                    orderable: true
                },
                {
                    orderable: true
                },
                {
                    orderable: false
                }
            ],
            "ordering": true,
            "order": [
                [0, "asc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });

    (function() {
        'use strict';
        var forms = document.querySelectorAll('.formEliminarRegistro');
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    Swal.fire({
                        title: '¿Está seguro de eliminar el registro?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00887A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    })
                }, false);
            });
    })();
</script>
@stop
@extends('adminlte::page')

@section('title', 'Líneas')

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
                    <h3>Listado de l&iacute;neas</h3>
                </div>
            </div>
            <div class="col-sm-6">
                @can('Lineas.Crear')
                <a href="" class="btn btn-dark" style="float: right; margin-left:10px;" data-toggle="modal" data-target="#modalCreateLineas">Registrar
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
                        <table class="table table-striped table-hover table-bordered mt-2 text-center" id="CAT_lineas" style="width: 100%;">
                            <thead>
                                <th width="8%">Id</th>
                                <th>Descripci&oacute;n</th>
                                <th width="15%">Acciones</th>
                            </thead>
                            <tbody>
                                @foreach($lineas as $items)
                                <tr>
                                    <td>{{ $items->id }}</td>
                                    <td>{{ $items->descripcion }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('lineas.destroy', $items->id) }}" method="POST" class="formEliminarLinea">
                                            @method('DELETE')
                                            @csrf
                                            @can('Lineas.Editar')
                                            <a onclick="getDataLinea('{{ $items->id }}')" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                </svg>
                                            </a>
                                            @endcan
                                            @can('Lineas.Eliminar')
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

    <div class="modal fade" id="modalCreateLineas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong style="color: #5D7785;">Registrar nueva l&iacute;nea</strong></h5>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('lineas.store') }}" id="formLineas" class="formLineas">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="form-label" for="descripcion">Descripción</label>
                                <input type="text" name="descripcion" id="descripcion" class="form-control text-uppercase @error('descripcion') is-invalid @enderror" placeholder="..." maxlength="150" required>
                            </div>
                        </div>
                        <br>
                        <div class="col-sm-12 col-12">
                            <button type="submit" class="btn btn-success" style="background-color:#1A897A !important; float: right;">Registrar</button>
                            <button type="button" class="btn btn-dark mr-2" data-dismiss="modal" style="float: right;">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditLinea" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong style="color: #5D7785;">Editar l&iacute;nea</strong></h5>
                    <a class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('lineas.update') }}" id="formEditarLineas" class="formEditarLineas">
                        @method('PUT')
                        @csrf
                        <textarea name="linea_id" id="linea_id" class="d-none" cols="30" rows="10"></textarea>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="form-label" for="descripcionLinea">Descripción</label>
                                <input type="text" name="descripcion" id="descripcionLinea" class="form-control text-uppercase @error('descripcion') is-invalid @enderror" placeholder="..." maxlength="150" required>
                            </div>
                        </div>
                        <br>
                        <div class="col-sm-12 col-12">
                            <button type="submit" class="btn btn-success" style="background-color:#1A897A !important; float: right;">Actualizar</button>
                            <button type="button" class="btn btn-dark mr-2" data-bs-dismiss="modal" style="float: right;">Cancelar</button>
                        </div>
                    </form>
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
        $('#CAT_lineas').DataTable({
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

        function closeAlert() {
            $("#sectionErrors").fadeTo(5000, 500).slideUp(800, function() {
                $("#descripcion").removeClass("is-invalid");
                $("#descripcionLinea").removeClass("is-invalid");
                $("#sectionErrors").slideUp(3000);
            });
        }
        closeAlert();
    });

    $('#modalCreateLineas').on('hidden.bs.modal', function() {
        $(this).find("input").val('').end();
        $("#descripcion").removeClass("is-invalid");
        if (document.getElementById('sectionErrors')) {
            document.getElementById('sectionErrors').style.display = 'none';
        }
    });

    (function() {
        'use strict';
        var form = document.querySelectorAll('#formLineas');
        form[0].addEventListener('submit', function(event) {
            var contexto = this;
            event.preventDefault();
            event.stopPropagation();
            if (true) {
                function validate() {
                    Swal.fire({
                        title: '¿Está seguro de registrar la línea?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#00887A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            contexto.submit();
                        }
                    })
                }
                validate();
            }
        }, false);
    })();

    (function() {
        'use strict';
        var forms = document.querySelectorAll('.formEliminarLinea');
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                    Swal.fire({
                        title: '¿Está seguro de eliminar la línea?',
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

    (function() {
        'use strict';
        var form = document.querySelectorAll('#formEditarLineas');
        form[0].addEventListener('submit', function(event) {
            var contexto = this;
            event.preventDefault();
            event.stopPropagation();
            if (true) {
                function validate() {
                    Swal.fire({
                        title: '¿Está seguro de editar la línea?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#00887A',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            contexto.submit();
                        }
                    })
                }
                validate();
            }
        }, false);
    })();

    function getDataLinea(id) {
        let url = "{{ route('lineas.getDataLinea',':id') }}";
        $.ajax({
            url: url.replace(':id', id),
            type: 'GET',
            data: {
                "id": id
            },
            success: function(data) {
                $('#linea_id').html(id);
                $('#descripcionLinea').val(data.descripcion);
                $('#modalEditLinea').modal('show');
            },
            error: function(xhr) {
                alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
                console.log(xhr);
            }
        })
    }
</script>
@stop
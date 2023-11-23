@extends('adminlte::page')

@section('title', 'Editar rol y permisos')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container-fluid">
        <br>
        <div class="col-sm-12">
            <h3>Editar permisos de&nbsp;<strong style="color: #5D7785">{{$editRol->name}}</strong></h3>
        </div>
        <div class="col-sm-12">
            <div>
                <form method="POST" action="{{ route('roles.update', $editRol->id) }}" id="formEditarRolPermisos">
                    @method('PUT')
                    @csrf
                    <div class="col-sm-12">
                        @if ($errors->any())
                        <div class="form-group alert alert-danger" id="sectionErrors">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
                        <br>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 mt-2">
                                        <label class="form-label" for="name">Nombre del rol</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text" viewBox="0 0 16 16">
                                                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                                                        <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <input type="text" value="{{ $editRol->name }}" name="name" id="name" class="form-control @error('name') is-invalid @enderror" maxlength="255" placeholder="..." required>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <label class="form-label">Permisos</label>
                                <div class="row anyClass">
                                    @foreach($permisos as $item)
                                    <div class="col-sm-4">
                                        @foreach($item as $p)
                                        <div class="checkbox-group required">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <input type="checkbox" value="{{ $p->nombrePermiso}}" name="nombrePermiso[]" id="checkPermisos{{ $p->idPermiso}}" @if(is_array(old('nombrePermiso')) && in_array($p->nombrePermiso, old('nombrePermiso'))) checked @elseif(in_array($p->nombrePermiso, $permisosRol)) checked @endif>
                                                    </div>
                                                </div>
                                                <label class="form-control" for="checkPermisos{{ $p->idPermiso}}" readonly data-toggle="tooltip" data-placement="right" title="{{ $p->nombrePermiso }}">{{ $p->nombrePermiso }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 buttons">
                        <a type="submit" class="btn btn-dark mb-4" href="{{route('roles.index')}}">Cancelar</a>
                        <button type="submit" class="btn btn-success mb-4" style="background-color: #00887A !important; color:aliceblue; margin-left:10px !important;">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .anyClass {
        height: 340px;
        overflow-y: scroll;
    }

    .row.buttons {
        float: right !important;
    }

    .col-sm-12.buttons {
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script>
    function closeAlert() {
        $("#sectionErrors").fadeTo(5000, 500).slideUp(800, function() {
            $("#name").removeClass("is-invalid");
            $("#sectionErrors").slideUp(3000);
        });
    }
    closeAlert();

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });

    (function() {
        'use strict';
        var form = document.querySelectorAll('#formEditarRolPermisos');
        form[0].addEventListener('submit', function(event) {
            var contexto = this;
            event.preventDefault();
            event.stopPropagation();
            if (true) {
                function validate() {
                    if (!$('div.checkbox-group.required :checkbox:checked').length > 0) {
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'No ha seleccionado ningún permiso',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    } else {
                        Swal.fire({
                            title: '¿Está seguro de actualizar el rol?',
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
                }
                validate();
            }
        }, false);
    })();
</script>
@stop
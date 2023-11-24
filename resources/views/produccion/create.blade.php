@extends('adminlte::page')

@section('title', 'Registrar producción')

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
            <h3>Registrar nueva producci&oacute;n</h3>
        </div>
        <div class="col-sm-12">
            <div>
                <form method="POST" action="{{ route('produccion.store') }}" id="formGrabarProduccion">
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
                                    <div class="col-sm-2 mt-2">
                                        <label class="form-label" for="name">Fecha</label>
                                        <input type="date" value="{{ old('fecha') }}" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" required>
                                    </div>
                                    <div class="col-sm-3 mt-2">
                                        <label for="idLinea">Seleccione la l&iacute;nea</label>
                                        <div class="input-group">
                                            <select class="custom-select @error('idLinea') is-invalid @enderror" name="idLinea" id="idLinea">
                                                <option selected value="">...</option>
                                                @foreach($lineas as $item)
                                                <option value="{{ $item->id }}">{{ $item->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mt-2">
                                        <label for="idEstilo">Estilo</label>
                                        <div class="input-group">
                                            <select class="custom-select @error('idEstilo') is-invalid @enderror" name="idEstilo" id="idEstilo">
                                                <option selected value="">...</option>
                                                @foreach($estilos as $item)
                                                <option value="{{ $item->id }}">{{ $item->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 buttons">
                            <a type="submit" class="btn btn-dark mb-4" href="{{route('usuarios.index')}}">Cancelar</a>
                            <button type="submit" class="btn btn-success mb-4" style="background-color: #00887A !important; color:aliceblue; margin-left:10px !important;">Registrar</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
@stop

@section('css')
<style>
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
    (function() {
        'use strict';
        var form = document.querySelectorAll('#formGrabarProduccion');
        form[0].addEventListener('submit', function(event) {
            var contexto = this;
            event.preventDefault();
            event.stopPropagation();
            if (true) {
                function validate() {
                    Swal.fire({
                        title: '¿Está seguro de registrar el usuario?',
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
</script>
@stop
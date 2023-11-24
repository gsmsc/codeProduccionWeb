@extends('adminlte::page')

@section('title', 'Editar estilos')

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
            <h3>Editar estilos</h3>
        </div>
        <br>
        <div class="col-sm-12">
            <div>
                <form method="POST" action="{{ route('estilos.update', $estilosEdit->id) }}" id="formEditarEstilo">
                    @method('PUT')
                    @csrf
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
                                <div class="col-sm-2 mt-2">
                                    <label>C&oacute;digo</label>
                                    <input type="text" value="{{ $estilosEdit->codigo }}" class="form-control" readonly>
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <label for="idCliente">Cliente</label>
                                    <div class="input-group">
                                        <select class="custom-select @error('idCliente') is-invalid @enderror" name="idCliente" id="idCliente" required>
                                            <option selected value="">...</option>
                                            @foreach($clientes as $item)
                                            <option value="{{ $item->id }}" {{ $estilosEdit->idCliente == $item->id ? 'selected' : '' }}>
                                                {{ $item->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <label for="idDivision">Divisi&oacute;n</label>
                                    <div class="input-group">
                                        <select class="custom-select @error('idDivision') is-invalid @enderror" name="idDivision" id="idDivision" required>
                                            <option selected value="">...</option>
                                            @foreach($division as $item)
                                            <option value="{{ $item->id }}" {{ $estilosEdit->idDivision == $item->id ? 'selected' : '' }}>
                                                {{ $item->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 mt-2">
                                    <label for="idSubcategoria">Subcategor&iacute;a</label>
                                    <div class="input-group">
                                        <select class="custom-select @error('idSubcategoria') is-invalid @enderror" name="idSubcategoria" id="idSubcategoria" required>
                                            <option selected value="">...</option>
                                            @foreach($subcategoria as $item)
                                            <option value="{{ $item->id }}" {{ $estilosEdit->idSubcategoria == $item->id ? 'selected' : '' }}>
                                                {{ $item->nombre }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mt-2">
                                    <label class="form-label" for="referencia1">Referencia #1</label>
                                    <input type="text" value="{{ $estilosEdit->referencia1 }}" name="referencia1" id="referencia1" class="form-control text-uppercase @error('referencia1') is-invalid @enderror" placeholder="..." maxlength="150">
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <label class="form-label" for="referencia2">Referencia #2</label>
                                    <input type="text" value="{{ $estilosEdit->referencia2 }}" name="referencia2" id="referencia2" class="form-control text-uppercase @error('referencia2') is-invalid @enderror" placeholder="..." maxlength="150">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mt-2">
                                    <label class="form-label" for="descripcion">Descripci&oacute;n</label>
                                    <input type="text" value="{{ $estilosEdit->descripcion }}" name="descripcion" id="descripcion" class="form-control text-uppercase @error('descripcion') is-invalid @enderror" placeholder="..." maxlength="150" required>
                                </div>
                            </div>
                            <br>
                            <div class="col-sm-12 buttons">
                                <a type="submit" class="btn btn-dark mb-4" href="{{ route('estilos.index') }}">Cancelar</a>
                                <button type="submit" class="btn btn-success mb-4" style="background-color: #00887A !important; color:aliceblue; margin-left:10px !important;">Actualizar</button>
                            </div>
                        </div>
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
    .alert-danger {
        color: #fff;
        background-color: #64393d !important;
        border-color: #d32535;
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
    $(document).ready(function() {
        function closeAlert() {
            $("#sectionErrors").fadeTo(5000, 500).slideUp(800, function() {
                $("#codigo").removeClass("is-invalid");
                $("#descripcion").removeClass("is-invalid");
                $("#idCliente").removeClass("is-invalid");
                $("#idDivision").removeClass("is-invalid");
                $("#idSubcategoria").removeClass("is-invalid");
                $("#sectionErrors").slideUp(3000);
            });
        }
        closeAlert();
    });


    (function() {
        'use strict';
        var form = document.querySelectorAll('#formEditarEstilo');
        form[0].addEventListener('submit', function(event) {
            var contexto = this;
            event.preventDefault();
            event.stopPropagation();
            if (true) {
                function validate() {
                    Swal.fire({
                        title: '¿Está seguro de editar el estilo?',
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
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
                                        <label class="form-label" for="fecha">Fecha</label>
                                        <input type="date" value="{{ old('fecha') }}" name="fecha" id="fecha" class="form-control @error('fecha') is-invalid @enderror" required>
                                    </div>
                                    <div class="col-sm-4 mt-2">
                                        <label for="idLinea">Seleccione la l&iacute;nea</label>
                                        <div class="input-group">
                                            <select class="custom-select @error('idLinea') is-invalid @enderror" name="idLinea" id="idLinea" required>
                                                <option selected value="">...</option>
                                                @foreach($lineas as $item)
                                                <option value="{{ $item->id }}" {{ old('idLinea') == $item->id ? 'selected' : '' }}>{{ $item->descripcion }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label for="idEstilo">Seleccione el estilo</label>
                                        <div class="input-group">
                                            <select class="custom-select @error('idEstilo') is-invalid @enderror" name="idEstilo" id="idEstilo" required>
                                                <option selected value="">...</option>
                                                @foreach($estilos as $item)
                                                <option value="{{ $item->id }}" {{ old('idEstilo') == $item->id ? 'selected' : '' }}>{{ $item->descripcion }}-{{ $item->codigo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label>Operarios:</label>
                                        <input type="text" value="{{ old('operariosNormal') }}" name="operariosNormal" id="operariosNormal" class="form-control @error('operariosNormal') is-invalid @enderror" placeholder="Normal..." maxlength="9">
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label style="color: transparent;">...</label>
                                        <input type="text" value="{{ old('operariosRefuerzos') }}" name="operariosRefuerzos" id="operariosRefuerzos" class="form-control @error('operariosRefuerzos') is-invalid @enderror" placeholder="Refuerzos..." maxlength="9">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 mt-2">
                                        <label>Unidades:</label>
                                        <input type="text" value="{{ old('uProducidas') }}" name="uProducidas" id="uProducidas" class="form-control @error('uProducidas') is-invalid @enderror" placeholder="Producidas..." maxlength="9">
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label style="color: transparent;">...</label>
                                        <input type="text" value="{{ old('uIrregulares') }}" name="uIrregulares" id="uIrregulares" class="form-control @error('uIrregulares') is-invalid @enderror" placeholder="Irregulares..." maxlength="9">
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label style="color: transparent;">...</label>
                                        <input type="text" value="{{ old('uRegulares') }}" name="uRegulares" id="uRegulares" class="form-control @error('uRegulares') is-invalid @enderror" placeholder="Regulares..." maxlength="9">
                                    </div>
                                    <div class="col-sm-3 mt-2">
                                        <label for="metaNormal">Meta normal</label>
                                        <input type="text" value="{{ old('metaNormal') }}" name="metaNormal" id="metaNormal" class="form-control @error('metaNormal') is-invalid @enderror" placeholder="..." maxlength="10">
                                    </div>
                                    <div class="col-sm-1 mt-2">
                                        <label>Total horas:</label>
                                        <input type="text" value="{{ old('totalHorasOrdinarias') }}" name="totalHorasOrdinarias" id="totalHorasOrdinarias" class="form-control @error('totalHorasOrdinarias') is-invalid @enderror" placeholder="Ord..." maxlength="5">
                                    </div>
                                    <div class="col-sm-1 mt-2">
                                        <label style="color: transparent;">...</label>
                                        <input type="text" value="{{ old('totalHorasExtras') }}" name="totalHorasExtras" id="totalHorasExtras" class="form-control @error('totalHorasExtras') is-invalid @enderror" placeholder="Ext..." maxlength="9">
                                    </div>
                                    <div class="col-sm-1 mt-2">
                                        <label style="color: transparent;">...</label>
                                        <input type="text" value="{{ old('totalHorasTrabajadas') }}" name="totalHorasTrabajadas" id="totalHorasTrabajadas" class="form-control @error('totalHorasTrabajadas') is-invalid @enderror" placeholder="Trabaj..." maxlength="9">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 mt-2">
                                        <label for="horasNoProducidas">Hrs. no producidas</label>
                                        <input type="text" value="{{ old('horasNoProducidas') }}" name="horasNoProducidas" id="horasNoProducidas" class="form-control @error('horasNoProducidas') is-invalid @enderror" placeholder="..." maxlength="9">
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label for="horasProducidas">Hrs. producidas</label>
                                        <input type="text" value="{{ old('horasProducidas') }}" name="horasProducidas" id="horasProducidas" class="form-control @error('horasProducidas') is-invalid @enderror" placeholder="..." maxlength="9">
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label for="metaAjustada">Meta ajustada</label>
                                        <input type="text" value="{{ old('metaAjustada') }}" name="metaAjustada" id="metaAjustada" class="form-control @error('metaAjustada') is-invalid @enderror" placeholder="..." maxlength="10">
                                    </div>
                                    <div class="col-sm-2 mt-2">
                                        <label for="eficiencia">Eficiencia</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-percent" viewBox="0 0 16 16">
                                                    <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0M4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5m7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                                </svg>
                                            </span>
                                            <input type="text" value="{{ old('eficiencia') }}" name="eficiencia" id="eficiencia" class="form-control @error('eficiencia') is-invalid @enderror" placeholder="..." maxlength="4">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 mt-2">
                                        <label for="bonos">Bonos</label>
                                        <input type="text" value="{{ old('bonos') }}" name="bonos" id="bonos" class="form-control @error('bonos') is-invalid @enderror" placeholder="..." maxlength="9">
                                    </div>
                                </div>
                                <h5 class="text-center mt-3 mb-3" style="color: #6c757d;">Horas no producidas</h5>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="maquinaMala">Maquina mala</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483m.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535m-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </span>
                                            <input type="text" value="{{ old('maquinaMala') }}" name="maquinaMala" id="maquinaMala" class="form-control @error('maquinaMala') is-invalid @enderror" placeholder="..." maxlength="5">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="noTrabajo">No trabajo</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483m.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535m-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </span>
                                            <input type="text" value="{{ old('noTrabajo') }}" name="noTrabajo" id="noTrabajo" class="form-control @error('noTrabajo') is-invalid @enderror" placeholder="..." maxlength="5">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="entrenamiento">Entrenamiento</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483m.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535m-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </span>
                                            <input type="text" value="{{ old('entrenamiento') }}" name="entrenamiento" id="entrenamiento" class="form-control @error('entrenamiento') is-invalid @enderror" placeholder="..." maxlength="5">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="cambioEstilo">Cambio de estilo</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                                    <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483m.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535m-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                    <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                                </svg>
                                            </span>
                                            <input type="text" value="{{ old('cambioEstilo') }}" name="cambioEstilo" id="cambioEstilo" class="form-control @error('cambioEstilo') is-invalid @enderror" placeholder="..." maxlength="5">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 mt-2">
                                        <label for="observaciones">Observaciones:</label>
                                        <textarea class="form-control text-uppercase" name="observaciones" style="resize: none;" id="observaciones" maxlength="250" placeholder="...">{{ old('observaciones') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 buttons">
                            <a type="submit" class="btn btn-dark mb-4" href="{{route('produccion.index')}}">Cancelar</a>
                            <button type="submit" class="btn btn-success mb-4" style="background-color: #00887A !important; color:aliceblue; margin-left:10px !important;">Registrar</button>
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
<script src="../assets/js/jquery.numeric.js"></script>
<script>
    const fechaInput = document.getElementById("fecha");
    const fechaActual = new Date().toISOString().split('T')[0];
    fechaInput.value = fechaActual;

    $(document).ready(function() {
        function closeAlert() {
            $("#sectionErrors").fadeTo(5000, 500).slideUp(800, function() {
                $("#fecha").removeClass("is-invalid");
                $("#idLinea").removeClass("is-invalid");
                $("#idEstilo").removeClass("is-invalid");
                $("#operariosNormal").removeClass("is-invalid");
                $("#operariosRefuerzos").removeClass("is-invalid");
                $("#uProducidas").removeClass("is-invalid");
                $("#uIrregulares").removeClass("is-invalid");
                $("#uRegulares").removeClass("is-invalid");
                $("#metaNormal").removeClass("is-invalid");
                $("#totalHorasOrdinarias").removeClass("is-invalid");
                $("#totalHorasExtras").removeClass("is-invalid");
                $("#totalHorasTrabajadas").removeClass("is-invalid");
                $("#horasNoProducidas").removeClass("is-invalid");
                $("#horasProducidas").removeClass("is-invalid");
                $("#metaAjustada").removeClass("is-invalid");
                $("#eficiencia").removeClass("is-invalid");
                $("#bonos").removeClass("is-invalid");
                $("#maquinaMala").removeClass("is-invalid");
                $("#noTrabajo").removeClass("is-invalid");
                $("#entrenamiento").removeClass("is-invalid");
                $("#cambioEstilo").removeClass("is-invalid");
                $("#observaciones").removeClass("is-invalid");
                $("#sectionErrors").slideUp(3000);
            });
        }
        closeAlert();
    });


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
                        title: '¿Está seguro de registrar la producción?',
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

    $("#operariosNormal, #operariosRefuerzos, #uProducidas, #uRegulares, #uIrregulares, #metaNormal, #metaAjustada, #eficiencia, #bonos, #maquinaMala, #noTrabajo, #entrenamiento, #cambioEstilo").numeric({
        decimal: false,
        negative: false,
        decimalPlaces: false
    });

    $("#totalHorasOrdinarias, #totalHorasExtras, #totalHorasTrabajadas, #horasNoProducidas, #horasProducidas").numeric({
        decimal: ".",
        negative: false,
        decimalPlaces: 1
    });
</script>
@stop
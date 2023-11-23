@extends('adminlte::page')

@section('title', 'Registrar usuario')

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
            <h3>Registrar usuario</h3>
        </div>
        <div class="col-sm-12">
            <div>
                <form method="POST" action="{{ route('usuarios.store') }}" id="formGrabarUsuario">
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
                                    <div class="col-sm-4 mt-2">
                                        <label class="form-label" for="name">Nombre usuario</label>
                                        <input type="text" value="{{ old('name') }}" name="name" id="name" class="form-control @error('name') is-invalid @enderror" maxlength="255" placeholder="..." required>
                                    </div>
                                    <div class="col-sm-5 mt-2">
                                        <label class="form-label" for="email">Correo electrónico</label>
                                        <div class="input-group">
                                            <input type="email" value="{{old('email')}}" id="email" name="email" class="form-control @error('email') is-invalid @enderror" maxlength="255" placeholder="..." required>
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-at" viewBox="0 0 16 16">
                                                    <path d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mt-2">
                                        <label for="pass">Contrase&ntilde;a</label>
                                        <div class="input-group">
                                            <input id="pass" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" type="pass" name="password" maxlength="255" required />
                                            <span class="input-group-text">
                                                <i id="eye" class="far fa-eye" onclick="showHidePwd();"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 mt-2">
                                        <label for="idTipoUsuario">Tipo de usuario</label>
                                        <div class="input-group">
                                            <select class="custom-select @error('idTipoUsuario') is-invalid @enderror" name="idTipoUsuario" id="idTipoUsuario" required>
                                                <option selected value="">...</option>
                                                @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('idTipoUsuario') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                                                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Zm9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
                                                </svg>
                                            </span>
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
    function closeAlert() {
        $("#sectionErrors").fadeTo(5000, 500).slideUp(800, function() {
            $("#name").removeClass("is-invalid");
            $("#email").removeClass("is-invalid");
            $("#pass").removeClass("is-invalid");
            $("#idTipoUsuario").removeClass("is-invalid");
            $("#sectionErrors").slideUp(3000);
        });
    }
    closeAlert();

    (function() {
        'use strict';
        var form = document.querySelectorAll('#formGrabarUsuario');
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

    function showHidePwd() {
        var input = document.getElementById("pass");
        if (input.type === "password") {
            input.type = "text";
            document.getElementById("eye").className = "far fa-eye";
        } else {
            input.type = "password";
            document.getElementById("eye").className = "far fa-eye-slash";
        }
    }
</script>
@stop
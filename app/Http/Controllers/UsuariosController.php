<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuariosController extends Controller
{
    function __construct()
    {
        $this->middleware('can:Usuarios.Index', ['only' => ['index']]);
        $this->middleware('can:Usuarios.Crear', ['only' => ['create', 'store']]);
        $this->middleware('can:Usuarios.Editar', ['only' => ['edit', 'update']]);
        $this->middleware('can:Usuarios.Eliminar', ['only' => ['destroy']]);
    }

    public function index()
    {
        $usuarios = DB::table('users as USR')
            ->select(
                'USR.id',
                'USR.name',
                'USR.email',
                'RL.name as tipoUsuario'
            )
            ->leftJoin('roles as RL', 'USR.idRol', '=', 'RL.id')
            ->where('USR.idRol', '!=', 1)
            ->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::where('id', '!=', 1)->get();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validaciones = [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users',
            'password' => 'required|max:255',
            'idTipoUsuario' => 'required',
        ];

        $mensajesValidaciones = [
            'name.required' => 'El nombre de usuario es un valor requerido.',
            'name.max' => 'El nombre de usuario no debe de exceder de 255 caracteres.',
            'email.required' => 'El correo electrónico es una valor requerido.',
            'email.max' => 'El correo electrónico no debe de exceder de 255 caracteres.',
            'email.unique' => 'El correo electrónico dígitado ya existe.',
            'password.required' => 'La contraseña es un valor requerido.',
            'password.max' => 'La contraseña no debe de exceder de 255 caracteres.',
            'idTipoUsuario.required' => 'El tipo de usuario es un valor requerido.'
        ];

        $this->validate($request, $validaciones, $mensajesValidaciones);

        $input = $request->all();
        $arrayUsuario =  [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'idRol' => $input['idTipoUsuario']
        ];

        $roleName = Role::find($request->idTipoUsuario);
        if ($request->idTipoUsuario != 1) {
            $idUsuario = User::create($arrayUsuario)->id;
            $user = User::find($idUsuario);
            $user->assignRole($roleName);
        } else {
            return redirect()->route('usuarios.create')->with('info', 'Imposible registrar el tipo de usuario, intente de nuevo.');
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado con éxito');
    }

    public function edit($id)
    {
        $usuariosId = User::find($id);
        $roles = Role::where('id', '!=', 1)
            ->get();
        return view('usuarios.edit', compact('usuariosId', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $validaciones = [
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users,email,' . $id,
            'idTipoUsuario' => 'required'
        ];

        $mensajesValidaciones = [
            'name.required' => 'El nombre de usuario es un valor requerido.',
            'name.max' => 'El nombre de usuario no debe de exceder de 255 caracteres.',
            'email.required' => 'El correo electrónico es una valor requerido.',
            'email.max' => 'El correo electrónico no debe de exceder de 255 caracteres.',
            'email.unique' => 'El correo electrónico dígitado ya existe.',
            'idTipoUsuario.required' => 'El tipo de usuario es un valor requerido.',
        ];

        $this->validate($request, $validaciones, $mensajesValidaciones);

        $input = $request->all();

        if ($input['password'] == null) {
            $arrayUpdate = [
                'name' => $input['name'],
                'email' => $input['email'],
                'idRol' => $input['idTipoUsuario'],
            ];
        } else {
            $arrayUpdate = [
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'idRol' => $input['idTipoUsuario'],
            ];
        }

        $roleName = Role::find($request->idTipoUsuario);
        if ($request->idTipoUsuario != 1) {
            $user = User::find($id);

            $user->update($arrayUpdate);
            $user->roles()->detach();
            $user->assignRole($roleName);
        } else {
            return redirect()->route('usuarios.edit', $id)->with('info', 'Imposible registrar el tipo de usuario, intente de nuevo.');
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario editado con éxito');
    }

    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado con éxito');
    }
}

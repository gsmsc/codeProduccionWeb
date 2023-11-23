<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    function __construct()
    {
        $this->middleware('can:Roles.Index', ['only' => ['index']]);
        $this->middleware('can:Roles.Crear', ['only' => ['create', 'store']]);
        $this->middleware('can:Roles.Editar', ['only' => ['edit', 'update']]);
        $this->middleware('can:Roles.Eliminar', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = DB::table('roles')
            ->select('roles.id', 'roles.name as nombreRol', 'roles.created_at as fecha')
            ->where('roles.id', '!=', 1)
            ->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permisos = DB::table('permissions')
            ->select('id as idPermiso', 'name as nombrePermiso')
            ->where('guard_name', '=', 'web')
            ->get();
        $sections = intval(count($permisos) / 3);
        $mod = count($permisos) % 3;
        $sections += $mod;
        $permisos = $permisos->chunk($sections);
        return view('roles.create', compact('permisos'));
    }

    public function store(Request $request)
    {
        $validacionesInputs = [
            'name' => 'required|max:255',
        ];

        $respuestaValidaciones = [
            'name.required' => 'El nombre del rol es un dato requerido.',
            'name.max' => 'El nombre del rol no debe exceder de 255 caracteres.',
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);

        $input = $request->all();
        $idRol = Roles::create([
            'name' => $input['name'],
            'guard_name' => 'web',
        ])->id;

        $rol = Role::find($idRol);
        $rol->syncPermissions($input['nombrePermiso']);

        return redirect()->route('roles.index')->with('success', 'Rol grabado con éxito');
    }

    public function edit($id)
    {
        $editRol = Roles::find($id);
        $permisos = DB::table('permissions')
            ->select('id as idPermiso', 'name as nombrePermiso')
            ->where('guard_name', '=', 'web')
            ->get();
        $sections = intval(count($permisos) / 3);
        $mod = count($permisos) % 3;
        $sections += $mod;
        $permisos = $permisos->chunk($sections);

        $permisosRol = DB::table('role_has_permissions')
            ->select('permissions.name')
            ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_id', '=', $id)
            ->pluck('name')
            ->toArray();

        return view('roles.edit', compact('editRol', 'permisos', 'permisosRol'));
    }

    public function update(Request $request, $id)
    {
        $validacionesInputs = [
            'name' => 'required|max:255'
        ];

        $respuestaValidaciones = [
            'name.required' => 'El nombre del rol es un dato requerido',
            'name.max' => 'El nombre del rol no debe de exceder de 255 caracteres'
        ];

        $this->validate($request, $validacionesInputs, $respuestaValidaciones);
        $input = $request->all();

        $arrayUpdateRol = [
            'name' => $input['name'],
            'guard_name' => 'web'
        ];

        $rol = Role::find($id);
        $rol->update($arrayUpdateRol);

        $rol->syncPermissions($input['nombrePermiso']);

        return redirect()->route('roles.index')->with('success', 'Rol editado con éxito');
    }

    public function destroy($id)
    {
        $deleteRol = Role::find($id);
        $deleteRol->delete();

        DB::table('role_has_permissions')
            ->where('role_id', '=', $id)
            ->delete();

        return redirect()->route('roles.index')->with('info', 'Rol eliminado con éxito');
    }
}

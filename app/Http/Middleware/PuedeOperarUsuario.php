<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PuedeOperarUsuario
{
    public function handle(Request $request, Closure $next)
    {
        $idUsuario = $request->route()->parameter('idUsuario');
        $usuario = User::find($idUsuario);

        if ($usuario->idRol == 1) abort(403);
        if ($usuario->id == Auth::user()->id) abort(403);

        return $next($request);
    }
}

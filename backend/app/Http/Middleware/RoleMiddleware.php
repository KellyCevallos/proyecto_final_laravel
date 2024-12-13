<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Verifica si el usuario está autenticado y tiene el rol especificado
        if (!Auth::check() || !Auth::users()->hasRole($role)) {
            // Redirigir o mostrar un mensaje de error si no tiene acceso
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        // Continuar con la solicitud si el usuario tiene el rol adecuado
        return $next($request);
    }
}


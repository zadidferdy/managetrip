<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check() || !in_array(auth()->user()->role_user, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
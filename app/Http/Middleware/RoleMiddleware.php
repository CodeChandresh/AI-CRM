<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $role = $request->route()->parameter('role');

        if ($role) {
            $userRole = Auth::user()->role;

            if ($userRole !== $role) {
                abort(403, 'Unauthorized action');
            }
        }

        return $next($request);
    }
}
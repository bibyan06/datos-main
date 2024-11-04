<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->employee_id[0] != $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }

    protected function hasRole($user, $role)
    {
        return Str::startsWith($user->employee_id, $role);
    }
}


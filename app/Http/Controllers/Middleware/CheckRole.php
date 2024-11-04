<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            abort(403);
        }
        return $next($request);
    }
}

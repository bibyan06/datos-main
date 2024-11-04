<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Assuming roles are determined by the 'employee_id' column prefix
        $user = Auth::user();

        if (!$user || !str_starts_with($user->employee_id, $role)) {
            // Redirect or abort if the user does not have the required role
            return abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

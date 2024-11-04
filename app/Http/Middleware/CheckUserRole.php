<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $role): Response
    {   
        try {
            // Check if the user is authenticated
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            // Check if the user's role matches the required role
            $userRole = Auth::user()->role_id;
            if (Auth::check() && $userRole == $role) {
                return $next($request);
            }

            // If the role does not match, return an unauthorized response
            return abort(403, 'Unauthorized');
            
        } catch (\Throwable $th) {
            return abort(401, 'Unauthorized');
        }
    }
}

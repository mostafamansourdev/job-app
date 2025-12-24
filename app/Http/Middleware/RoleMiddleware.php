<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // checks if if user has one of the required roles to access the route
        if (auth()->check()) {
            $user = auth()->user();
            $hasAccess = in_array($user->role, $roles);
            // abort with 403 if user does not have access
            if (!$hasAccess) {
                abort(403, 'Unauthorized action.');
            }
        }

        // allow the request to proceed if the user has access
        return $next($request);
    }
}

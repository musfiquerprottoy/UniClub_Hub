<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Essential import
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is logged in AND has the required role string
        if (!Auth::check() || $request->user()->role !== $role) {
            abort(403, 'Unauthorized action. You do not have ' . $role . ' permissions.');
        }

        return $next($request);
    }
}
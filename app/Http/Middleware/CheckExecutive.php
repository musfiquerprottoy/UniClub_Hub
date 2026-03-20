<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExecutive
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is NOT an executive, send them back to the dashboard
        if (auth()->user()->role !== 'executive') {
            return redirect('/dashboard')->with('error', 'Access Denied: Only Club Executives can propose events.');
        }

        return $next($request);
    }
}
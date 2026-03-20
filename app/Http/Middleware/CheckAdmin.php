<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user's role is NOT admin, send them away
        if (auth()->user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Access Denied: Only Admins can create clubs.');
        }

        // Otherwise, let them through to the page
        return $next($request);
    }
}
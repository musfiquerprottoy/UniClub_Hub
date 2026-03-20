<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdvisor
{
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is NOT an advisor, send them back
        if (auth()->user()->role !== 'advisor') {
            return redirect('/dashboard')->with('error', 'Access Denied: Only Advisors can do this.');
        }

        return $next($request);
    }
}
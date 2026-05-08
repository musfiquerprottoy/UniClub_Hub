<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registering Middleware Aliases
        $middleware->alias([
            /*
            |--------------------------------------------------------------------------
            | Role-Based Middleware
            |--------------------------------------------------------------------------
            | These aliases allow you to protect routes using:
            | ->middleware('advisor') or ->middleware('role:advisor')
            */
            
            // Your specific class-based middlewares
            'admin'     => \App\Http\Middleware\CheckAdmin::class,
            'executive' => \App\Http\Middleware\CheckExecutive::class,
            'advisor'   => \App\Http\Middleware\CheckAdvisor::class, 
            'student'   => \App\Http\Middleware\CheckStudent::class,

            // The 'role' alias is required if your routes use the syntax: role:advisor
            // Ensure you have created the RoleMiddleware.php file!
            'role'      => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
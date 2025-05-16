<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // apiPrefix: '', // Ensure this is how you've removed the prefix if done here
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add your custom CORS middleware at the beginning of the global stack
        $middleware->prepend(\App\Http\Middleware\ForceCors::class);

        // You might have other global middleware here, e.g.:
        // $middleware->web(append: [
        //     \App\Http\Middleware\ExampleMiddleware::class,
        // ]);

        // If Laravel's default HandleCors is still registered globally or for api,
        // your custom one running first should take precedence for these headers.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();

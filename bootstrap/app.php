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
        // Register custom middleware
        $middleware->alias([
            'role' => App\Http\Middleware\CheckRole::class,
            'ukm.access' => App\Http\Middleware\CheckStaffUkmAccess::class,
        ]);

        // Middleware groups
        $middleware->web(append: [
            // Tambahkan middleware custom ke group web jika perlu
        ]);

        $middleware->api(append: [
            // Untuk API nanti
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
<?php

/*
|--------------------------------------------------------------------------
| bootstrap/app.php — Laravel 11
| Enregistrement du middleware "admin"
|--------------------------------------------------------------------------
*/

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        /*
        |----------------------------------------------------------------------
        | Alias de middleware personnalisés
        |----------------------------------------------------------------------
        |
        | 'admin'  → réservé aux administrateurs (role = admin)
        |
        | Usage dans les routes :
        |   Route::middleware(['auth', 'admin'])->group(...)
        */
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


/*
|--------------------------------------------------------------------------
| Pour Laravel 10 — Kernel.php
|--------------------------------------------------------------------------
|
| Si vous êtes sur Laravel 10, ajoutez dans :
| app/Http/Kernel.php → $routeMiddleware :
|
|   'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
|
*/
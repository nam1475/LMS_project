<?php

use App\Http\Middleware\CheckRoleMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetAutoCoupons;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'check_role' => CheckRoleMiddleware::class,
            'set_auto_coupons' => SetAutoCoupons::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Disable detailed exception pages in production
        if (!config('app.debug')) {
            $exceptions->dontReport([]);
        }
    })->create();

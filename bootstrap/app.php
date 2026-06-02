<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // 💡 FIX UTAMA: Jalur API wajib didaftarkan di sini!
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Custom middleware 'role' milikmu tetap aman terjaga di sini
        $middleware->alias([
            'role'    => \App\Http\Middleware\RoleMiddleware::class,
            'api.key' => \App\Http\Middleware\CheckApiKey::class,
            'jwt.auth'=> \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
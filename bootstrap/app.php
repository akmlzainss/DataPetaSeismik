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
    ->withMiddleware(function (Middleware $middleware): void {
        
        // Daftarkan middleware kustom Admin sebagai alias rute
        $middleware->alias([
            // Alias standar seperti 'auth', 'guest', dll., sudah otomatis didaftarkan.
            
            // --- ALIAS KUSTOM UNTUK ADMIN ---
            'auth.admin' => \App\Http\Middleware\AdminAuthMiddleware::class,
        ]);
        
        // Pendaftaran middleware global (jika ada) dan lainnya ditaruh di sini.
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Security headers middleware global
        $middleware->web(\App\Http\Middleware\SecurityHeaders::class);

        // Daftarkan middleware kustom Admin sebagai alias rute
        $middleware->alias([
            // Alias standar seperti 'auth', 'guest', dll., sudah otomatis didaftarkan.

            // --- ALIAS KUSTOM UNTUK ADMIN ---
            'auth.admin' => \App\Http\Middleware\AdminAuthMiddleware::class,
            'guest:admin' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
            
            // --- ALIAS KUSTOM UNTUK PEGAWAI INTERNAL ---
            'verified.pegawai' => \App\Http\Middleware\VerifiedPegawai::class,
        ]);

        // Rate limiting sudah diterapkan di routes

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom error handling for production
        $exceptions->render(function (Throwable $e, $request) {
            if (app()->environment('production')) {
                // Don't expose sensitive error details in production
                if ($e instanceof \Illuminate\Database\QueryException) {
                    return response()->view('errors.500', [], 500);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->view('errors.404', [], 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                    $statusCode = $e->getStatusCode();
                    if (view()->exists("errors.{$statusCode}")) {
                        return response()->view("errors.{$statusCode}", [], $statusCode);
                    }
                }
            }
        });
    })->create();

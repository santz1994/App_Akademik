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
    $middleware->alias([
        'role.admin' => \App\Http\Middleware\AdminMiddleware::class,
        'role.user'  => \App\Http\Middleware\UserMiddleware::class,
        'role.kepala' => \App\Http\Middleware\KepalaMiddleware::class,
        'role.tata_usaha' => \App\Http\Middleware\TataUsahaMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Render custom error pages
        $exceptions->renderable(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {
                return null; // Let API handle its own errors
            }

            $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            // Map known HTTP status codes to custom views
            $errorViews = [403, 404, 419, 429, 500, 503];

            if (in_array($status, $errorViews, true)) {
                $viewPath = "errors.{$status}";
                if (view()->exists($viewPath)) {
                    return response()->view($viewPath, [
                        'code' => $status,
                        'message' => $e->getMessage(),
                    ], $status);
                }
            }

            // For other errors, use the generic error page
            if (view()->exists('errors.error')) {
                return response()->view('errors.error', [
                    'code' => $status,
                    'message' => $e->getMessage(),
                    'detail' => config('app.debug') ? $e->getFile() . ':' . $e->getLine() : null,
                ], $status);
            }

            return null;
        });
    })->create();

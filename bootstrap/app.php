<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\KepalaMiddleware;
use App\Http\Middleware\TataUsahaMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role.admin' => AdminMiddleware::class,
            'role.user' => UserMiddleware::class,
            'role.kepala' => KepalaMiddleware::class,
            'role.tata_usaha' => TataUsahaMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle authentication errors — redirect to login instead of 500
        $exceptions->renderable(function (Throwable $e, $request) {
            // Unauthenticated / session expired → redirect to login
            if ($e instanceof AuthenticationException
                || $e instanceof UnauthorizedHttpException) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }

                return redirect()->route('login')
                    ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }

            // CSRF token mismatch (419) → redirect back with error
            if ($e instanceof TokenMismatchException) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['message' => 'CSRF token mismatch.'], 419);
                }

                return back()->with('error', 'Token keamanan telah kedaluwarsa. Silakan coba lagi.');
            }

            if ($request->is('api/*')) {
                return null;
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
                    'detail' => config('app.debug') ? $e->getFile().':'.$e->getLine() : null,
                ], $status);
            }

            return null;
        });
    })->create();

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            if (Auth::check()) {
                $route = Auth::user()->is_kepala ? 'kepala.dashboard' : 'user.dashboard';

                return redirect()->route($route)
                    ->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin.');
            }

            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KepalaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        if (!$user->is_kepala) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ini khusus Kepala Pimpinan Pos.');
            }

            return redirect()->route('user.dashboard')
                ->with('error', 'Akses ini khusus Kepala Pimpinan Pos.');
        }

        return $next($request);
    }
}

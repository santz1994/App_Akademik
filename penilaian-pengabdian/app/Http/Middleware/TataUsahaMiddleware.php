<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TataUsahaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Tata usaha can access this area
        if ($user->role === 'tata_usaha') {
            return $next($request);
        }

        // Admin can also access tata usaha area
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ini khusus Tata Usaha.');
        }

        if ($user->is_kepala) {
            return redirect()->route('kepala.dashboard')
                ->with('error', 'Akses ini khusus Tata Usaha.');
        }

        return redirect()->route('user.dashboard')
            ->with('error', 'Akses ini khusus Tata Usaha.');
    }
}

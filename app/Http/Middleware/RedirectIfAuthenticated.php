<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Pastikan jika sudah login dan ada role, langsung diarahkan ke dashboard yang sesuai
        if (Auth::check()) {
            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'owner':
                    return redirect()->route('dashboard.owner');
                case 'user':
                    return redirect()->route('dashboard.user');
                default:
                    return redirect('/'); // fallback jika role tidak dikenali
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Jika user sudah login, arahkan berdasarkan role-nya
        if (Auth::check()) {
            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'owner':
                    return redirect()->route('owner.dashboard');
                case 'user':
                    return redirect()->route('user.dashboard');
                default:
                    return redirect('/'); // fallback jika role tidak dikenali
            }
        }

        return $next($request);
    }
}

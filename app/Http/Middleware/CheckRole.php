<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Ambil role user, normalisasi: lowercase + ganti spasi/underscore jadi strip
        $userRole = strtolower(str_replace([' ', '_'], '-', Auth::user()->role));

        // Normalisasi roles yang diizinkan
        $allowedRoles = array_map(function($r) {
            return strtolower(str_replace([' ', '_'], '-', $r));
        }, $roles);

        // Cek apakah role user ada di daftar role yang diizinkan
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

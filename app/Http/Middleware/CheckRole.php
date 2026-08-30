<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Cek apakah user yang login punya role yang diizinkan.
     * Contoh pemakaian di route: ->middleware('role:admin')
     *
     * @param  string  ...$roles  Role yang diizinkan, bisa lebih dari satu
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Kalau belum login, lempar ke halaman login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Cek kolom 'role' di tabel users cocok dengan role yang diizinkan
        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\PenempatanMagang;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login (GET /login)
     */
    public function showLoginForm()
    {
        $jumlahSiswa = Siswa::count();
        $jumlahGuru = Guru::count();
        $jumlahPenempatanMagang = PenempatanMagang::count();

        return view('auth.login', compact(
            'jumlahSiswa',
            'jumlahGuru',
            'jumlahPenempatanMagang'
        ));
    }

    /**
     * Proses login (POST /login)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Catat activity log LOGIN_SUCCESS
            ActivityLog::create([
                'level'       => 'info',
                'action_type' => 'LOGIN_SUCCESS',
                'actor_email' => $user->email ?? null,
                'actor_role'  => $user->role ?? null,
                'ip_address'  => $request->ip(),
                'metadata'    => [
                    'description' => "User {$user->email} berhasil login sebagai " . strtoupper($user->role ?? 'user'),
                ],
            ]);

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'guru' => redirect()->route('guru.dashboard'),
                'siswa' => redirect()->route('siswa.dashboard'),
                default => redirect()->route('landing'),
            };
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    /**
     * Tampilkan halaman lupa password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses pengiriman permintaan reset password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);

        $profile = \App\Models\Profile::where('email', $request->email)->first();

        if (!$profile) {
            return back()->withErrors([
                'email' => 'Alamat email tidak terdaftar di sistem SIMMAS.',
            ])->withInput();
        }

        return back()->with('status', 'Permintaan pemulihan telah dikirim. Silakan hubungi Administrator sekolah jika Anda memerlukan bantuan lebih lanjut.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Catat activity log LOGOUT sebelum sesi dihancurkan
            ActivityLog::create([
                'level'       => 'info',
                'action_type' => 'LOGOUT',
                'actor_email' => $user->email ?? null,
                'actor_role'  => $user->role ?? null,
                'ip_address'  => $request->ip(),
                'metadata'    => [
                    'description' => "User {$user->email} (" . strtoupper($user->role ?? 'user') . ") telah logout",
                ],
            ]);
        }

        Auth::logout();

        // Hapus session lama
        $request->session()->invalidate();

        // Generate CSRF token baru
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
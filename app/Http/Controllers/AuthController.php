<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\PenempatanMagang;

class AuthController extends Controller
{
    /**
     * Tampilkan form login (GET /login)
     */
    public function showLoginForm()
    {
        // Ambil data langsung dari database
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

        return match (Auth::user()->role) {
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
        Auth::logout();

        // Hapus session lama
        $request->session()->invalidate();

        // Generate CSRF token baru
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
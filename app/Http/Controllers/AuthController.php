<?php

namespace App\Http\Controllers;

// Import Model yang digunakan dalam autentikasi dan log
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
     * Mengambil data statistik singkat untuk ditampilkan di halaman login
     */
    public function showLoginForm()
    {
        // Menghitung total siswa, guru, dan penempatan magang
        $jumlahSiswa = Siswa::count();
        $jumlahGuru = Guru::count();
        $jumlahPenempatanMagang = PenempatanMagang::count() + \App\Models\PengajuanMagang::count();

        // Mengirimkan variabel ke view auth/login.blade.php
        return view('auth.login', compact(
            'jumlahSiswa',
            'jumlahGuru',
            'jumlahPenempatanMagang'
        ));
    }

    /**
     * Proses autentikasi login pengguna (POST /login)
     */
    public function login(Request $request)
    {
        // Validasi input email dan password
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        // Mencoba login dengan kredensial yang dimasukkan
        if (Auth::attempt(
            $credentials,
            $request->boolean('remember') // Opsi ingat saya
        )) {
            // Mencegah Session Fixation Attack dengan meregenerasi session ID
            $request->session()->regenerate();

            // Mengambil objek user yang berhasil login
            $user = Auth::user();

            // Mencatat log aktivitas keberhasilan login ke database
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

            // Mengarahkan (redirect) user ke dashboard sesuai role masing-masing
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'guru' => redirect()->route('guru.dashboard'),
                'siswa' => redirect()->route('siswa.dashboard'),
                default => redirect()->route('landing'),
            };
        }

        // Jika email atau password tidak cocok, kembalikan ke form login dengan pesan error
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email'); // Mempertahankan isi input email sebelumnya
    }

    /**
     * Tampilkan halaman lupa password (GET /forgot-password)
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses pengiriman permintaan reset password (POST /forgot-password)
     */
    public function sendResetLink(Request $request)
    {
        // Validasi email dengan custom message Bahasa Indonesia
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);

        // Memeriksa apakah email terdaftar di tabel profiles
        $profile = \App\Models\Profile::where('email', $request->email)->first();

        if (!$profile) {
            return back()->withErrors([
                'email' => 'Alamat email tidak terdaftar di sistem SIMMAS.',
            ])->withInput();
        }

        // Memberikan respon notifikasi sukses
        return back()->with('status', 'Permintaan pemulihan telah dikirim. Silakan hubungi Administrator sekolah jika Anda memerlukan bantuan lebih lanjut.');
    }

    /**
     * Proses Logout Pengguna (POST /logout)
     */
    public function logout(Request $request)
    {
        // Memeriksa apakah user sedang dalam kondisi login
        if (Auth::check()) {
            $user = Auth::user();

            // Mencatat log aktivitas logout ke database
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

        // Menghapus status autentikasi user
        Auth::logout();

        // Menghancurkan session lama pengguna
        $request->session()->invalidate();

        // Menghasilkan token CSRF baru untuk keamanan
        $request->session()->regenerateToken();

        // Mengarahkan kembali ke landing page
        return redirect()->route('landing');
    }
}
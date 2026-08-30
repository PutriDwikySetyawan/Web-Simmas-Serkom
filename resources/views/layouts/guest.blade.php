<!DOCTYPE html>
<html lang="id">
<head>

    {{-- ===== 1. META DASAR ===== --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('simmas.nama_aplikasi', 'SIMMAS') . ' - ' . config('simmas.kepanjangan', 'Sistem Informasi Manajemen Magang Siswa'))</title>
    <meta name="description" content="@yield('meta_description', config('simmas.deskripsi_aplikasi', 'Platform manajemen magang siswa SMK yang menghubungkan sekolah, guru pembimbing, dan dunia usaha industri.'))">

    {{-- ===== 2. FONT: Plus Jakarta Sans (judul) + Inter (teks biasa) ===== --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ===== 3. BOOTSTRAP ICONS — dipakai di navbar, kartu fitur, sidebar preview ===== --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- ===== 4. CSS & JS CUSTOM KITA — via Vite (sudah termasuk Bootstrap CSS/JS) ===== --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Slot: halaman anak bisa nambah CSS sendiri --}}
    @stack('styles')

</head>
<body>

    {{-- ============================================
         NAVBAR — mengambang (floating pill), tidak
         mentok tepi kiri-kanan layar
         ============================================ --}}
   @unless ($__env->hasSection('hide_navbar'))
    <div class="simmas-navbar-wrap">
        <nav class="navbar navbar-expand-lg navbar-light simmas-navbar">
            <div class="container-fluid px-2">

                {{-- Logo: badge bulat biru + ikon topi wisuda (representasi sekolah) --}}
                <a class="simmas-brand" href="{{ url('/') }}">
                    <span class="simmas-mark"><i class="bi bi-mortarboard-fill"></i></span>
                    <span class="simmas-brand-text">{{ config('simmas.nama_aplikasi', 'SIMMAS') }}</span>
                </a>

                {{-- Tombol hamburger untuk tampilan mobile --}}
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#simmasNavbar"
                    aria-controls="simmasNavbar" aria-expanded="false" aria-label="Buka menu navigasi">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="simmasNavbar">

                    {{-- Tautan menu tengah — tampil sebagai chip kecil --}}
                    <ul class="navbar-nav mx-auto simmas-nav-links">
                        <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#panduan">Panduan</a></li>
                    </ul>

                    {{-- Tombol aksi kanan: Masuk (outline) & Mulai Sekarang (solid), keduanya pill kecil --}}
                    <div class="d-flex align-items-center gap-2 simmas-nav-actions">
                        <a href="{{ route('login') }}" class="btn simmas-btn-ghost">Masuk</a>
                        <a href="{{ route('login') }}" class="btn simmas-btn-primary-sm">
                            Mulai Sekarang <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </nav>
    </div>
        @endunless

    {{-- ============================================
         KONTEN HALAMAN — diisi oleh halaman anak
         ============================================ --}}
    <main>
        @yield('content')
    </main>

    {{-- ============================================
         FOOTER — biru gelap senada dengan hero
         ============================================ --}}
      @unless ($__env->hasSection('hide_footer'))
    <footer class="simmas-footer">
        <div class="container">
            <div class="row gy-4">

                {{-- Kolom 1: Brand + deskripsi singkat --}}
                <div class="col-lg-4">
                    <a href="{{ url('/') }}" class="simmas-brand simmas-footer-brand">
                        <span class="simmas-mark"><i class="bi bi-mortarboard-fill"></i></span>
                        <span class="simmas-brand-text">{{ config('simmas.nama_aplikasi', 'SIMMAS') }}</span>
                    </a>
                    <p class="simmas-footer-desc">
                        {{ config('simmas.deskripsi_aplikasi', 'Sistem Informasi Manajemen Magang Siswa — menghubungkan sekolah, guru pembimbing, dan dunia usaha industri dalam satu platform.') }}
                    </p>
                </div>

                {{-- Kolom 2: Navigasi --}}
                <div class="col-lg-4 col-6">
                    <h6 class="simmas-footer-title">Navigasi</h6>
                    <ul class="simmas-footer-links">
                        <li><a href="#fitur">Fitur Utama</a></li>
                        <li><a href="#panduan">Panduan Penggunaan</a></li>
                        <li><a href="{{ route('login') }}">Masuk ke Portal</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Kontak sekolah dinamis --}}
                <div class="col-lg-4 col-6">
                    <h6 class="simmas-footer-title">Data & Kontak Sekolah</h6>
                    <ul class="simmas-footer-links">
                        <li><i class="bi bi-building"></i> <strong>{{ config('simmas.nama_sekolah', 'SMK Negeri 1 Bangil') }}</strong></li>
                        @if(config('simmas.nama_kepala_sekolah'))
                            <li><i class="bi bi-person-badge"></i> Kepsek: {{ config('simmas.nama_kepala_sekolah') }}</li>
                        @endif
                        <li><i class="bi bi-geo-alt"></i> {{ config('simmas.alamat_sekolah', 'Jl. Tongkol No.3, Kec. Bangil, Kab. Pasuruan') }}</li>
                        @if(config('simmas.website_sekolah'))
                            <li><i class="bi bi-globe"></i> <a href="{{ config('simmas.website_sekolah') }}" target="_blank" style="color:rgba(255,255,255,0.8);text-decoration:underline;">{{ config('simmas.website_sekolah') }}</a></li>
                        @endif
                        <li><i class="bi bi-telephone"></i> {{ config('simmas.no_telepon_sekolah', '(0343) 744144') }}</li>
                    </ul>
                </div>

            </div>

            {{-- Baris hak cipta, tahun otomatis dari server --}}
            <div class="simmas-footer-bottom">
                <p>&copy; {{ date('Y') }} {{ config('simmas.nama_aplikasi', 'SIMMAS') }} &mdash; {{ config('simmas.nama_sekolah', 'SMK Negeri 1 Bangil') }}. Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>
      @endunless

    {{-- Slot: halaman anak bisa nambah JS sendiri --}}
    @stack('scripts')

</body>
</html>
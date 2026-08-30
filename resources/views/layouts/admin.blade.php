<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin') - SIMMAS</title>

    {{-- CSRF token, dipakai semua request AJAX (fetch/axios) di halaman admin --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap CSS, dibutuhkan oleh SEMUA halaman admin yang memakai
         class Bootstrap standar (card, row/col, table, modal, badge, btn,
         form-control, dsb). Sebelumnya cuma Bootstrap Icons + JS bundle
         yang ke-load, jadi semua komponen tampil polos tanpa styling. --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    {{-- Bootstrap Icons, dipakai untuk semua ikon sidebar & tombol aksi --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- CSS custom aplikasi (warna brand, komponen shared) --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Slot untuk CSS tambahan khusus per halaman (mis. style tabel monitoring) --}}
    @stack('styles')

    <style>
        /* =========================================================
           VARIABEL WARNA BRAND SIMMAS
           Dipusatkan di sini supaya konsisten di seluruh halaman
           admin, guru, dan siswa.
        ========================================================= */
        :root {
            --simmas-blue: #2563eb;
            --simmas-blue-dark: #1d4ed8;
            --simmas-blue-light: rgba(37, 99, 235, 0.12);
            --simmas-ink: #0f172a;
            --simmas-muted: #64748b;
            --simmas-border: #e2e8f0;
            --simmas-bg: #f8fafc;
            --simmas-danger: #dc2626;
            --simmas-danger-light: rgba(220, 38, 38, 0.08);
            --font-display: 'Inter', system-ui, sans-serif;

            /* ===== dipakai di halaman-halaman modul admin
               (kartu statistik, badge status) yang belum ada tokennya di sini ===== */
            --simmas-paper: var(--simmas-bg);                    /* alias, background lembut */
            --simmas-green: #16A34A;
            --simmas-green-light: #E8F8EE;
            --simmas-amber: #B45309;
            --simmas-amber-light: #FEF3E2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: var(--font-display);
            background: var(--simmas-bg);
            color: var(--simmas-ink);
        }

        /* =========================================================
           LAYOUT UTAMA: SIDEBAR + KONTEN
        ========================================================= */

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ---------------------------------------------------
           SIDEBAR
        --------------------------------------------------- */

        .admin-sidebar {
            width: 260px;
            min-width: 260px;
            background: #fff;
            border-right: 1px solid var(--simmas-border);

            display: flex;
            flex-direction: column;

            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-sidebar__brand {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 20px 24px;
            border-bottom: 1px solid var(--simmas-border);
        }

        .admin-sidebar__brand-mark {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;
            background: var(--simmas-blue);
            color: #fff;

            font-weight: 800;
        }

        .admin-sidebar__brand-text strong {
            display: block;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .admin-sidebar__brand-text span {
            font-size: 0.7rem;
            color: var(--simmas-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .admin-sidebar__nav {
            flex: 1;
            padding: 16px 12px;
        }

        .admin-sidebar__group-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--simmas-muted);

            padding: 14px 12px 6px;
        }

        .admin-sidebar__link {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 9px 12px;
            margin-bottom: 2px;

            border-radius: 8px;

            font-size: 0.86rem;
            font-weight: 600;
            color: var(--simmas-ink);

            text-decoration: none;

            transition: background 0.15s ease, color 0.15s ease;
        }

        .admin-sidebar__link i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
        }

        .admin-sidebar__link:hover {
            background: var(--simmas-bg);
        }

        .admin-sidebar__link.is-active {
            background: var(--simmas-blue-light);
            color: var(--simmas-blue);
        }

        /* ---------------------------------------------------
           KONTEN UTAMA
        --------------------------------------------------- */

        .admin-main {
            flex: 1;
            min-width: 0; /* supaya konten flex tidak overflow */
            display: flex;
            flex-direction: column;
        }

        /* ---------------------------------------------------
           TOPBAR
        --------------------------------------------------- */

        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 14px 28px;

            background: #fff;
            border-bottom: 1px solid var(--simmas-border);

            position: sticky;
            top: 0;
            z-index: 20;
        }

        .admin-topbar__title {
            font-weight: 700;
            font-size: 1rem;
        }

        .admin-topbar__search {
            display: flex;
            align-items: center;
            gap: 8px;

            padding: 8px 14px;

            background: var(--simmas-bg);
            border: 1px solid transparent;
            border-radius: 999px;

            color: var(--simmas-muted);
            font-size: 0.82rem;

            min-width: 220px;

            transition: border-color 0.15s ease, background 0.15s ease;
        }

        .admin-topbar__search:focus-within {
            background: #fff;
            border-color: var(--simmas-blue);
        }

        .admin-topbar__search input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.82rem;
            width: 100%;
            color: var(--simmas-ink);
        }

        .admin-topbar__actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-topbar__divider {
            width: 1px;
            height: 26px;
            background: var(--simmas-border);
        }

        .admin-topbar__user {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 6px 10px 6px 6px;
            border-radius: 999px;

            font-size: 0.85rem;
            font-weight: 600;

            cursor: pointer;
            user-select: none;

            transition: background 0.15s ease;
        }

        .admin-topbar__user:hover,
        .admin-topbar__user[aria-expanded="true"] {
            background: var(--simmas-bg);
        }

        .admin-topbar__avatar {
            width: 32px;
            height: 32px;
            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            background: var(--simmas-blue);
            color: #fff;

            font-size: 0.8rem;
            font-weight: 700;
        }

        .admin-topbar__user-meta {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .admin-topbar__user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--simmas-ink);
        }

        .admin-topbar__user-role {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--simmas-muted);
        }

        .admin-topbar__user .bi-chevron-down {
            font-size: 0.7rem;
            color: var(--simmas-muted);
            transition: transform 0.15s ease;
        }

        .admin-topbar__user[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }

        /* ---------------------------------------------------
           DROPDOWN (dipakai untuk menu user di topbar)
           Bootstrap-icons yang di-load cuma ikon fontnya saja,
           bukan CSS komponen Bootstrap — jadi style dropdown
           di-handle manual di sini. Class .dropdown-menu / .show
           tetap dipakai supaya kompatibel dengan bootstrap.bundle.js
           (yang menangani toggle buka/tutupnya).
        --------------------------------------------------- */

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;

            min-width: 190px;
            margin: 0;
            padding: 6px;

            list-style: none;

            background: #fff;
            border: 1px solid var(--simmas-border);
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.1);

            z-index: 50;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu li + li {
            margin-top: 2px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 9px;

            width: 100%;

            padding: 9px 10px;

            border: none;
            border-radius: 8px;
            background: none;

            font-family: inherit;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--simmas-ink);
            text-align: left;

            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .dropdown-item i {
            font-size: 0.95rem;
            width: 16px;
            text-align: center;
        }

        .dropdown-item:hover {
            background: var(--simmas-danger-light);
            color: var(--simmas-danger);
        }

        /* ---------------------------------------------------
           AREA KONTEN (SLOT PER HALAMAN)
        --------------------------------------------------- */

        .admin-content {
            flex: 1;
            padding: 24px 28px;
        }

        /* =========================================================
           TOMBOL SHARED — dipakai di halaman-halaman modul admin
           (Tambah Penempatan, tombol Batal/Simpan di semua modal, dst).
           Sebelumnya class ini cuma ada di CSS landing page
           (layouts.guest), jadi tidak ke-load di area admin.
        ========================================================= */

        /* Tombol solid biru kecil — dipakai untuk aksi utama (mis. "+ Tambah Penempatan") */
        .simmas-btn-primary-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--simmas-blue);
            color: #fff;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 18px;
            border: none;
            border-radius: 999px;
            transition: background .15s ease, transform .15s ease;
        }
        .simmas-btn-primary-sm:hover {
            background: var(--simmas-blue-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        /* Tombol solid biru versi besar — kalau nanti dipakai di halaman admin lain */
        .simmas-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--simmas-blue);
            color: #fff;
            font-weight: 600;
            padding: 11px 26px;
            border: none;
            border-radius: 999px;
            transition: background .15s ease, transform .15s ease;
        }
        .simmas-btn-primary:hover {
            background: var(--simmas-blue-dark);
            color: #fff;
            transform: translateY(-1px);
        }

        /* Tombol outline putih — dipakai untuk aksi sekunder (mis. "Batal" di semua modal) */
        .hero-simmas__btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid var(--simmas-border);
            color: var(--simmas-ink);
            font-weight: 600;
            padding: 9px 22px;
            border-radius: 999px;
            background: #fff;
        }
        .hero-simmas__btn-outline:hover {
            background: var(--simmas-bg);
            color: var(--simmas-ink);
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991px) {
            .admin-sidebar {
                display: none;
            }

            .admin-topbar__search {
                display: none;
            }

            .admin-topbar__user-meta {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="admin-layout">

        {{-- =====================================================
             SIDEBAR
        ====================================================== --}}

        <aside class="admin-sidebar">

            {{-- Brand --}}
            <div class="admin-sidebar__brand">
                <span class="admin-sidebar__brand-mark">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>

                <div class="admin-sidebar__brand-text">
                    <strong>SIMMAS</strong>
                    <span>Administrator</span>
                </div>
            </div>

            {{-- Navigasi --}}
            <nav class="admin-sidebar__nav">

                {{-- Grup: OVERVIEW --}}
                <p class="admin-sidebar__group-label">Overview</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.monitoring') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.monitoring*') ? 'is-active' : '' }}">
                    <i class="bi bi-activity"></i>
                    Monitoring Global
                </a>

                {{-- Grup: MASTER DATA --}}
                <p class="admin-sidebar__group-label">Master Data</p>

                <a href="{{ route('admin.guru.index') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.guru.*') ? 'is-active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    Data Guru
                </a>

                <a href="{{ route('admin.siswa.index') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.siswa.*') ? 'is-active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    Data Siswa
                </a>

                <a href="{{ route('admin.dudi.index') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.dudi.*') ? 'is-active' : '' }}">
                    <i class="bi bi-building-fill"></i>
                    Data DUDI
                </a>

                {{-- Grup: MANAJEMEN --}}
                <p class="admin-sidebar__group-label">Manajemen</p>

                <a href="{{ route('admin.penempatan.index') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.penempatan.*') ? 'is-active' : '' }}">
                    <i class="bi bi-diagram-3-fill"></i>
                    Penempatan Magang
                    @php
                        $jmlPengajuanMenunggu = \App\Models\PengajuanMagang::where('status','menunggu')->count();
                    @endphp
                    @if ($jmlPengajuanMenunggu > 0)
                        <span style="
                            margin-left:auto;
                            background:#DC2626;
                            color:#fff;
                            font-size:.65rem;
                            font-weight:700;
                            min-width:18px;
                            height:18px;
                            border-radius:999px;
                            display:inline-flex;
                            align-items:center;
                            justify-content:center;
                            padding:0 5px;
                            line-height:1;
                        ">{{ $jmlPengajuanMenunggu }}</span>
                    @endif
                </a>

                {{-- Grup: SISTEM --}}
                <p class="admin-sidebar__group-label">Sistem</p>

                <a href="{{ route('admin.settings.edit') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">
                    <i class="bi bi-gear-fill"></i>
                    Pengaturan Sistem
                </a>

                <a href="{{ route('admin.logs') }}"
                   class="admin-sidebar__link {{ request()->routeIs('admin.logs') ? 'is-active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    Log Aktivitas & Audit
                </a>

            </nav>

        </aside>


        {{-- =====================================================
             KONTEN UTAMA
        ====================================================== --}}

        <div class="admin-main">

            {{-- Topbar --}}
            <header class="admin-topbar">

                <div class="admin-topbar__title">
                    @yield('page-title', 'Dashboard')
                </div>

                <div class="admin-topbar__actions">

                    <div class="admin-topbar__search">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari...">
                    </div>

                    <span class="admin-topbar__divider"></span>

                    {{-- Dropdown user + tombol logout --}}
                    <div class="dropdown">

                        <div
                            class="admin-topbar__user"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <span class="admin-topbar__avatar">
                                {{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 1)) }}
                            </span>

                            <span class="admin-topbar__user-meta">
                                <span class="admin-topbar__user-name">{{ auth()->user()->nama ?? 'Admin' }}</span>
                                <span class="admin-topbar__user-role">Administrator</span>
                            </span>

                            <i class="bi bi-chevron-down"></i>
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>

                    </div>

                </div>

            </header>

            {{-- Slot konten utama, diisi oleh view masing-masing halaman --}}
            <main class="admin-content">
                @yield('content')
            </main>

        </div>

    </div>

    {{-- Bootstrap JS Bundle, dibutuhkan untuk dropdown & modal --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS custom aplikasi --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Slot untuk JS tambahan khusus per halaman --}}
    @stack('scripts')

</body>
</html>
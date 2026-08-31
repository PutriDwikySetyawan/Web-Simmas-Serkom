<!DOCTYPE html>
<html lang="id">

<head>
    {{-- ============================================================
         1. META & JUDUL HALAMAN
    ============================================================ --}}
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard') - SIMMAS Guru Pembimbing
    </title>


    {{-- ============================================================
         2. ASSET CSS & JAVASCRIPT
    ============================================================ --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    >


    {{-- ============================================================
         3. CSS KHUSUS LAYOUT GURU PEMBIMBING
    ============================================================ --}}
    <style>

        /* ==========================================================
           TOKEN WARNA
        ========================================================== */

        :root {
            --guru-primary: #2f5cf0;
            --guru-primary-dark: #2447c4;
            --guru-primary-soft: #eef2ff;

            --guru-danger: #ee4148;
            --guru-danger-soft: #fdecec;

            --guru-ink: #1f2430;
            --guru-muted: #8a92a3;

            --guru-border: #e9ebf0;
            --guru-bg: #f6f7fb;

            --guru-sidebar-width: 260px;
        }


        /* ==========================================================
           BODY
        ========================================================== */

        body {
            background: var(--guru-bg);
            color: var(--guru-ink);
            margin: 0;
        }


        /* ==========================================================
           WRAPPER UTAMA
        ========================================================== */

        .guru-app-shell {
            display: flex;
            min-height: 100vh;
        }


        /* ==========================================================
           SIDEBAR
        ========================================================== */

        .guru-sidebar {
            width: var(--guru-sidebar-width);
            flex-shrink: 0;

            background: #fff;
            border-right: 1px solid var(--guru-border);

            display: flex;
            flex-direction: column;

            position: sticky;
            top: 0;

            height: 100vh;
        }


        /* ==========================================================
           BRAND / LOGO
        ========================================================== */

        .guru-sidebar-brand {
            display: flex;
            align-items: center;

            gap: 0.7rem;

            padding: 1.35rem 1.25rem;

            border-bottom: 1px solid var(--guru-border);
        }


        .guru-sidebar-brand-icon {
            width: 40px;
            height: 40px;

            flex-shrink: 0;

            border-radius: 10px;

            background: var(--guru-primary);
            color: #fff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 1.15rem;
            font-weight: 700;
        }


        .guru-sidebar-brand-name {
            font-weight: 800;
            font-size: 1rem;

            line-height: 1.1;

            color: var(--guru-ink);
        }


        .guru-sidebar-brand-role {
            font-size: 0.66rem;
            font-weight: 600;

            letter-spacing: 0.04em;

            color: var(--guru-muted);

            text-transform: uppercase;
        }


        /* ==========================================================
           NAVIGASI SIDEBAR
        ========================================================== */

        .guru-sidebar-nav {
            flex-grow: 1;

            padding: 1rem 0.85rem;

            overflow-y: auto;
        }


        .guru-sidebar-nav ul {
            list-style: none;

            padding: 0;
            margin: 0;
        }


        .guru-sidebar-nav li {
            list-style: none;
        }


        /* ==========================================================
           LINK NAVIGASI
        ========================================================== */

        .guru-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 0.6rem;

            padding: 0.62rem 0.85rem;

            border-radius: 8px;

            color: var(--guru-ink);

            font-size: 0.86rem;
            font-weight: 500;

            text-decoration: none;

            margin-bottom: 0.25rem;

            transition:
                background 0.15s ease,
                color 0.15s ease;
        }


        .guru-nav-link .guru-nav-left {
            display: flex;
            align-items: center;

            gap: 0.65rem;
        }


        .guru-nav-link i {
            font-size: 1rem;

            width: 18px;

            text-align: center;

            color: var(--guru-muted);
        }


        /* Hover */

        .guru-nav-link:hover {
            background: var(--guru-primary-soft);

            color: var(--guru-primary);
        }


        .guru-nav-link:hover i {
            color: var(--guru-primary);
        }


        /* ==========================================================
           MENU AKTIF
        ========================================================== */

        .guru-nav-link.active {
            background: var(--guru-primary);

            color: #fff;
        }


        .guru-nav-link.active i {
            color: #fff;
        }


        /* ==========================================================
           BADGE
        ========================================================== */

        .guru-nav-badge {
            background: var(--guru-danger);

            color: #fff;

            font-size: 0.68rem;
            font-weight: 700;

            min-width: 18px;
            height: 18px;

            border-radius: 999px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            padding: 0 0.35rem;
        }


        .guru-nav-link.active .guru-nav-badge {
            background: #fff;

            color: var(--guru-primary);
        }


        /* ==========================================================
           PROFIL GURU
        ========================================================== */

        .guru-sidebar-profile {
            border-top: 1px solid var(--guru-border);

            padding: 0.9rem 1.1rem;

            display: flex;
            align-items: center;

            gap: 0.65rem;
        }


        .guru-profile-avatar {
            width: 36px;
            height: 36px;

            flex-shrink: 0;

            border-radius: 50%;

            background: var(--guru-primary-soft);

            color: var(--guru-primary);

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: 700;
            font-size: 0.82rem;
        }


        .guru-profile-name {
            font-size: 0.82rem;

            font-weight: 600;

            color: var(--guru-ink);

            line-height: 1.2;
        }


        .guru-profile-role {
            font-size: 0.72rem;

            color: var(--guru-muted);
        }


        .guru-profile-dropdown-toggle {
            background: none;

            border: none;

            color: var(--guru-muted);

            padding: 0.2rem;

            margin-left: auto;

            cursor: pointer;
        }


        /* ==========================================================
           AREA UTAMA
        ========================================================== */

        .guru-main {
            flex-grow: 1;

            min-width: 0;

            display: flex;
            flex-direction: column;
        }


        /* ==========================================================
           TOPBAR
        ========================================================== */

        .guru-topbar {
            background: #fff;

            border-bottom: 1px solid var(--guru-border);

            padding: 1rem 1.75rem;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }


        .guru-topbar-title {
            font-size: 1.05rem;

            font-weight: 700;

            color: var(--guru-ink);

            margin: 0;
        }


        /* ==========================================================
           CONTENT
        ========================================================== */

        .guru-content {
            padding: 1.5rem 1.75rem;

            flex-grow: 1;
            min-width: 0;
        }

        .guru-content .table-responsive {
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        .guru-content table { max-width: 100%; }


        /* ==========================================================
           TOGGLE SIDEBAR
        ========================================================== */

        .guru-sidebar-toggle {
            display: none;

            background: none;

            border: none;

            font-size: 1.3rem;

            color: var(--guru-ink);

            cursor: pointer;
        }


        /* ==========================================================
           RESPONSIVE / MOBILE
        ========================================================== */

        @media (max-width: 991.98px) {

            .guru-sidebar {
                position: fixed;

                left: 0;
                top: 0;

                z-index: 1040;

                transform: translateX(-100%);

                transition: transform 0.2s ease;

                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            }


            .guru-sidebar.is-open {
                transform: translateX(0);
            }


            .guru-sidebar-toggle {
                display: inline-flex;

                align-items: center;
                justify-content: center;

                width: 42px;
                height: 42px;

                padding: 0;

                border: 1px solid var(--guru-border);

                background: #fff;

                border-radius: 8px;
            }


            .guru-content {
                padding: 1.1rem;
            }


            .guru-topbar {
                padding: 0.9rem 1.1rem;
            }
        }

        @media (max-width: 575.98px) {
            .guru-content { padding: .9rem .75rem 1.5rem; }
            .guru-topbar { padding: .75rem; gap: .65rem; }
            .guru-topbar__title {
                font-size: .92rem;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .guru-content .btn:not(.btn-sm),
            .guru-content .form-control,
            .guru-content .form-select { min-height: 42px; }
            .guru-content .modal-dialog { margin: .75rem; }
            .guru-content .modal-footer > * { flex: 1; }
        }


        /* ==========================================================
           OVERLAY SIDEBAR
        ========================================================== */

        .guru-sidebar-overlay {
            display: none;
        }


        @media (max-width: 991.98px) {

            .guru-sidebar-overlay {
                position: fixed;

                inset: 0;

                background: rgba(0, 0, 0, 0.35);

                z-index: 1039;

                display: none;
            }


            .guru-sidebar-overlay.is-open {
                display: block;
            }
        }

    </style>

    {{-- CSS tambahan dari halaman --}}
    @yield('styles')

</head>


<body>

    <div class="guru-app-shell">


        {{-- ========================================================
             SIDEBAR
        ========================================================= --}}

        <aside
            class="guru-sidebar"
            id="guruSidebar"
        >

            {{-- ====================================================
                 BRAND
            ===================================================== --}}

            <div class="guru-sidebar-brand">

                <div class="guru-sidebar-brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>

                <div>

                    <div class="guru-sidebar-brand-name">
                        SIMMAS
                    </div>

                    <div class="guru-sidebar-brand-role">
                        Guru Pembimbing
                    </div>

                </div>

            </div>


            {{-- ====================================================
                 NAVIGASI
            ===================================================== --}}

            <nav class="guru-sidebar-nav">

                <ul>

                    {{-- Dashboard --}}

                    <li>

                        <a
                            href="{{ route('guru.dashboard') }}"
                            class="guru-nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
                        >

                            <span class="guru-nav-left">

                                <i class="bi bi-grid-1x2-fill"></i>

                                Dashboard

                            </span>

                        </a>

                    </li>


                    {{-- Validasi Jurnal & Absensi --}}

                    <li>

                        <a
                            href="{{ route('guru.jurnal.index') }}"
                            class="guru-nav-link {{ request()->routeIs('guru.jurnal.*') ? 'active' : '' }}"
                        >

                            <span class="guru-nav-left">

                                <i class="bi bi-journal-check"></i>

                                Validasi Jurnal & Absensi

                            </span>


                            @if (($sidebarJurnalPendingCount ?? 0) > 0)

                                <span class="guru-nav-badge">
                                    {{ $sidebarJurnalPendingCount }}
                                </span>

                            @endif

                        </a>

                    </li>


                    {{-- Siswa Bimbingan --}}

                    <li>

                        <a
                            href="{{ route('guru.siswa.index') }}"
                            class="guru-nav-link {{ request()->routeIs('guru.siswa.*') ? 'active' : '' }}"
                        >

                            <span class="guru-nav-left">

                                <i class="bi bi-people-fill"></i>

                                Siswa Bimbingan

                            </span>


                            @if (($sidebarSiswaBimbinganCount ?? 0) > 0)

                                <span class="guru-nav-badge">
                                    {{ $sidebarSiswaBimbinganCount }}
                                </span>

                            @endif

                        </a>

                    </li>


                    {{-- Kunjungan Lapangan --}}

                    <li>

                        <a
                            href="{{ route('guru.kunjungan.index') }}"
                            class="guru-nav-link {{ request()->routeIs('guru.kunjungan.*') ? 'active' : '' }}"
                        >

                            <span class="guru-nav-left">

                                <i class="bi bi-geo-alt-fill"></i>

                                Kunjungan Lapangan

                            </span>

                        </a>

                    </li>

                </ul>

            </nav>


            {{-- ====================================================
                 PROFIL GURU
            ===================================================== --}}

            <div class="guru-sidebar-profile">

                {{-- Avatar --}}

                <div class="guru-profile-avatar">

                    {{ strtoupper(substr(auth()->user()->nama ?? 'GP', 0, 2)) }}

                </div>


                {{-- Informasi Guru --}}

                <div>

                    <div class="guru-profile-name">

                        {{ auth()->user()->nama ?? 'Guru Pembimbing' }}

                    </div>

                    <div class="guru-profile-role">

                        Guru Pembimbing

                    </div>

                </div>


                {{-- Dropdown --}}

                <div class="dropdown">

                    <button
                        class="guru-profile-dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <i class="bi bi-three-dots-vertical"></i>

                    </button>


                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item text-danger"
                                >

                                    <i class="bi bi-box-arrow-right me-2"></i>

                                    Keluar

                                </button>

                            </form>

                        </li>

                    </ul>

                </div>

            </div>

        </aside>


        {{-- ========================================================
             OVERLAY MOBILE
        ========================================================= --}}

        <div
            class="guru-sidebar-overlay"
            id="guruSidebarOverlay"
        ></div>


        {{-- ========================================================
             AREA UTAMA
        ========================================================= --}}

        <div class="guru-main">


            {{-- ====================================================
                 TOPBAR
            ===================================================== --}}

            <header class="guru-topbar">

                <div class="d-flex align-items-center gap-2">

                    {{-- Tombol sidebar mobile --}}

                    <button
                        class="guru-sidebar-toggle"
                        id="guruSidebarToggle"
                        type="button"
                        aria-label="Buka menu"
                    >

                        <i class="bi bi-list"></i>

                    </button>


                    {{-- Judul halaman --}}

                    <h1 class="guru-topbar-title">

                        @yield('page-title', 'Dashboard')

                    </h1>

                </div>

            </header>


            {{-- ====================================================
                 KONTEN HALAMAN
            ===================================================== --}}

            <main class="guru-content">

                @yield('content')

            </main>

        </div>

    </div>


    {{-- ============================================================
         JAVASCRIPT
    ============================================================ --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.getElementById('guruSidebar');

            const toggle = document.getElementById('guruSidebarToggle');

            const overlay = document.getElementById('guruSidebarOverlay');


            if (toggle && sidebar) {

                toggle.addEventListener('click', function () {

                    sidebar.classList.toggle('is-open');

                    if (overlay) {
                        overlay.classList.toggle('is-open');
                    }

                });

            }


            if (overlay && sidebar) {

                overlay.addEventListener('click', function () {

                    sidebar.classList.remove('is-open');

                    overlay.classList.remove('is-open');

                });

            }

        });

    </script>


    {{-- JavaScript tambahan dari halaman --}}
    @yield('scripts')

</body>

</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &mdash; SIMMAS Peserta Magang</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        :root {
            --guru-primary:       #3B5BFB;
            --guru-primary-dark:  #2540D6;
            --guru-primary-soft:  #EAEEFF;
            --guru-ink:           #111827;
            --guru-muted:         #6B7280;
            --guru-border:        #E5E7EB;
            --guru-danger:        #DC3545;
            --guru-danger-soft:   #FDEAEA;
            --guru-bg:            #F5F6FA;

            --app-sidebar-w: 248px;
            --app-topbar-h: 68px;
            --app-transition: 180ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: var(--guru-bg);
            color: var(--guru-ink);
            margin: 0;
        }

        a { text-decoration: none; }

        /* ================= APP SHELL ================= */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */
        .app-sidebar {
            width: var(--app-sidebar-w);
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid var(--guru-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: transform var(--app-transition);
        }

        .app-sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 1.3rem 1.25rem 1.1rem;
            border-bottom: 1px solid var(--guru-border);
        }

        .app-sidebar-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--guru-primary), var(--guru-primary-dark));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .app-sidebar-brand-name {
            font-weight: 800;
            font-size: 0.98rem;
            color: var(--guru-ink);
            line-height: 1.2;
        }

        .app-sidebar-brand-tag {
            font-size: 0.66rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--guru-muted);
        }

        .app-sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1.1rem 0.9rem;
        }

        .app-nav-group {
            margin-bottom: 1.4rem;
        }

        .app-nav-group-title {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--guru-muted);
            padding: 0 0.6rem;
            margin-bottom: 0.5rem;
        }

        .app-nav-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.62rem 0.75rem;
            border-radius: 10px;
            font-size: 0.87rem;
            font-weight: 500;
            color: var(--guru-muted);
            margin-bottom: 0.15rem;
            transition: background var(--app-transition), color var(--app-transition);
        }

        .app-nav-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .app-nav-link:hover {
            background: var(--guru-bg);
            color: var(--guru-ink);
        }

        .app-nav-link.active {
            background: var(--guru-primary);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 12px -4px rgba(59, 91, 251, 0.45);
        }

        .app-nav-link:focus-visible {
            outline: 2px solid var(--guru-primary);
            outline-offset: 2px;
        }

        .app-sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--guru-border);
            font-size: 0.72rem;
            color: var(--guru-muted);
        }

        /* ================= MAIN ================= */
        .app-main {
            flex: 1;
            margin-left: var(--app-sidebar-w);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .app-topbar {
            height: var(--app-topbar-h);
            background: #fff;
            border-bottom: 1px solid var(--guru-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .app-topbar-left {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            min-width: 0;
        }

        .app-sidebar-toggle {
            display: none;
            border: 1px solid var(--guru-border);
            background: #fff;
            width: 36px;
            height: 36px;
            border-radius: 9px;
            align-items: center;
            justify-content: center;
            color: var(--guru-ink);
            flex-shrink: 0;
        }

        .app-page-title {
            font-size: 1.02rem;
            font-weight: 700;
            color: var(--guru-ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .app-topbar-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .app-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--guru-border);
            background: #fff;
            color: var(--guru-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            position: relative;
            transition: color var(--app-transition), border-color var(--app-transition);
        }

        .app-icon-btn:hover { color: var(--guru-primary); border-color: var(--guru-primary); }

        .app-icon-btn:focus-visible {
            outline: 2px solid var(--guru-primary);
            outline-offset: 2px;
        }

        .app-icon-btn .dot {
            position: absolute;
            top: 7px;
            right: 8px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--guru-danger);
            border: 1.5px solid #fff;
        }

        .app-search {
            display: none;
            align-items: center;
            gap: 0.5rem;
            background: var(--guru-bg);
            border: 1px solid var(--guru-border);
            border-radius: 10px;
            padding: 0.45rem 0.8rem;
            color: var(--guru-muted);
            font-size: 0.82rem;
            width: 220px;
        }

        .app-search input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 0.82rem;
            width: 100%;
            color: var(--guru-ink);
        }

        .app-user-btn {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            border: 1px solid var(--guru-border);
            background: #fff;
            border-radius: 10px;
            padding: 0.35rem 0.7rem 0.35rem 0.35rem;
            color: var(--guru-ink);
        }

        .app-user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--guru-primary-soft);
            color: var(--guru-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .app-user-name {
            font-size: 0.83rem;
            font-weight: 600;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ================= CONTENT ================= */
        .app-content {
            padding: 1.6rem 1.75rem 2.5rem;
            flex: 1;
        }

        /* ================= SIDEBAR OVERLAY (mobile) ================= */
        .app-sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.45);
            z-index: 1030;
        }

        .app-sidebar-overlay.show { display: block; }

        /* ================= TOAST ================= */
        .app-toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 1080;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .app-toast {
            min-width: 260px;
            max-width: 340px;
            background: #fff;
            border: 1px solid var(--guru-border);
            border-left: 4px solid var(--guru-primary);
            border-radius: 10px;
            box-shadow: 0 12px 28px -10px rgba(16, 24, 40, 0.25);
            padding: 0.8rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            font-size: 0.85rem;
            color: var(--guru-ink);
            animation: appToastIn 220ms ease;
        }

        .app-toast.success { border-left-color: #1C9C5B; }
        .app-toast.danger  { border-left-color: var(--guru-danger); }

        .app-toast i { font-size: 1.05rem; margin-top: 0.05rem; }
        .app-toast.success i { color: #1C9C5B; }
        .app-toast.danger i  { color: var(--guru-danger); }

        @keyframes appToastIn {
            from { opacity: 0; transform: translateX(16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ================= RESPONSIVE ================= */
        @media (min-width: 992px) {
            .app-search { display: flex; }
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
            }
            .app-sidebar.show {
                transform: translateX(0);
                box-shadow: 0 0 0 100vmax rgba(0,0,0,0);
            }
            .app-main { margin-left: 0; }
            .app-sidebar-toggle { display: inline-flex; }
        }

        @media (max-width: 575.98px) {
            .app-content { padding: 1.25rem 1rem 2rem; }
            .app-topbar { padding: 0 1rem; }
            .app-user-name { display: none; }
        }

    </style>

    @yield('styles')
</head>
<body>

    <div class="app-shell">

        {{-- ============================================================ --}}
        {{-- SIDEBAR --}}
        {{-- ============================================================ --}}
        <aside class="app-sidebar" id="appSidebar">
            <div class="app-sidebar-brand">
                <div class="app-sidebar-brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div>
                    <div class="app-sidebar-brand-name">SIMMAS</div>
                    <div class="app-sidebar-brand-tag">Peserta Magang</div>
                </div>
            </div>

            <nav class="app-sidebar-nav">
                <div class="app-nav-group">
                    <div class="app-nav-group-title">Utama</div>
                    <a href="{{ url('siswa/dashboard') }}"
                       class="app-nav-link {{ request()->is('siswa/dashboard') || request()->is('siswa') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ url('siswa/pengajuan') }}"
                       class="app-nav-link {{ request()->is('siswa/pengajuan*') ? 'active' : '' }}">
                        <i class="bi bi-send-check-fill"></i> Pengajuan Magang
                    </a>
                </div>

                <div class="app-nav-group">
                    <div class="app-nav-group-title">Kegiatan Magang</div>
                    <a href="{{ url('siswa/absensi-harian') }}"
                       class="app-nav-link {{ request()->is('siswa/absensi-harian*') ? 'active' : '' }}">
                        <i class="bi bi-calendar2-check-fill"></i> Absensi Harian
                    </a>
                    <a href="{{ url('siswa/jurnal-kegiatan') }}"
                       class="app-nav-link {{ request()->is('siswa/jurnal-kegiatan*') ? 'active' : '' }}">
                        <i class="bi bi-journal-richtext"></i> Jurnal Kegiatan
                    </a>
                </div>

                <div class="app-nav-group">
                    <div class="app-nav-group-title">Pengaturan</div>
                    <a href="{{ url('siswa/profil') }}"
                       class="app-nav-link {{ request()->is('siswa/profil*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> Pengaturan Akun
                    </a>
                </div>
            </nav>

            <div class="app-sidebar-footer">
                &copy; {{ date('Y') }} SIMMAS &mdash; Sistem Informasi Manajemen Magang Siswa
            </div>
        </aside>

        <div class="app-sidebar-overlay" id="appSidebarOverlay"></div>

        {{-- ============================================================ --}}
        {{-- MAIN ================= --}}
        {{-- ============================================================ --}}
        <div class="app-main">
            <header class="app-topbar">
                <div class="app-topbar-left">
                    <button type="button" class="app-sidebar-toggle" id="appSidebarToggle" aria-label="Buka menu">
                        <i class="bi bi-list" style="font-size: 1.2rem;"></i>
                    </button>
                    <div class="app-page-title">@yield('page-title', 'Dashboard')</div>
                </div>

                <div class="app-topbar-right">
                    <label class="app-search">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari...">
                    </label>

                    <button type="button" class="app-icon-btn" title="Notifikasi" aria-label="Notifikasi">
                        <i class="bi bi-bell"></i>
                        <span class="dot"></span>
                    </button>

                    <a href="{{ url('siswa/dashboard') }}" class="app-icon-btn" title="Beranda" aria-label="Beranda">
                        <i class="bi bi-house"></i>
                    </a>

                    <div class="dropdown">
                        <button class="app-user-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="app-user-avatar">
                                {{ strtoupper(substr(Auth::user()->nama ?? Auth::user()->name ?? 'S', 0, 1)) }}
                            </span>
                            <span class="app-user-name">{{ Auth::user()->nama ?? Auth::user()->name ?? 'Siswa' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ url('siswa/profil') }}"><i class="bi bi-person-gear me-2"></i>Pengaturan Akun</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ url('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>

    <div class="app-toast-container" id="appToastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ============================================================
        // Sidebar toggle (mobile)
        // ============================================================
        (function () {
            const sidebar  = document.getElementById('appSidebar');
            const overlay  = document.getElementById('appSidebarOverlay');
            const toggleBtn = document.getElementById('appSidebarToggle');

            function openSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
            }
            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }

            toggleBtn && toggleBtn.addEventListener('click', function () {
                sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
            });
            overlay && overlay.addEventListener('click', closeSidebar);

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 992) closeSidebar();
            });
        })();

        // ============================================================
        // Toast helper global — dipakai oleh semua halaman siswa/guru
        // Panggil: window.showAppToast('Pesan berhasil disimpan.', 'success' | 'danger')
        // ============================================================
        window.showAppToast = function (message, type = 'success') {
            const container = document.getElementById('appToastContainer');
            if (!container) { alert(message); return; }

            const icon = type === 'danger' ? 'bi-x-circle-fill' : 'bi-check-circle-fill';
            const el = document.createElement('div');
            el.className = `app-toast ${type}`;
            el.innerHTML = `<i class="bi ${icon}"></i><span>${message}</span>`;
            container.appendChild(el);

            setTimeout(() => {
                el.style.transition = 'opacity 200ms ease, transform 200ms ease';
                el.style.opacity = '0';
                el.style.transform = 'translateX(16px)';
                setTimeout(() => el.remove(), 220);
            }, 3200);
        };
    </script>

    @yield('scripts')
</body>
</html>

@extends('layouts.guru')

{{-- ============================================================
     JUDUL TAB BROWSER
============================================================ --}}
@section('title', 'Dashboard')

@section('content')

    {{-- ============================================================
         1. HERO BANNER
         Sapaan personal + jumlah jurnal menunggu evaluasi + tombol aksi
    ============================================================ --}}
    <div class="guru-hero d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            {{-- Sapaan dengan nama guru yang login --}}
            <h5 class="fw-bold text-white mb-1">
                Selamat datang, {{ $guruName ?? 'Guru Pembimbing' }}
            </h5>
            {{-- Info ringkas jumlah jurnal yang menunggu evaluasi --}}
            <p class="text-white-50 mb-0 guru-hero-sub">
                Ada <span class="fw-bold text-white">{{ $jurnalBelumDinilai ?? 0 }} jurnal</span>
                siswa bimbingan yang menunggu evaluasi Anda.
            </p>
        </div>

        {{-- Tombol aksi cepat menuju daftar jurnal --}}
        <a href="{{ route('guru.jurnal.index') }}" class="btn guru-btn-hero d-flex align-items-center gap-2">
            <i class="bi bi-journal-text"></i>
            Lihat Jurnal
        </a>
    </div>

    {{-- ============================================================
         2. STAT CARD (3 KARTU RINGKASAN)
         Siswa Bimbingan, Jurnal Belum Dinilai (amber), Kehadiran Hari Ini
    ============================================================ --}}
    <div class="row g-3 mt-1">

        {{-- ---- Kartu 1: Siswa Bimbingan ---- --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm guru-stat-card">
                <div class="card-body d-flex align-items-start gap-3">
                    <div class="guru-stat-icon guru-stat-icon-blue">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="guru-stat-label">SISWA BIMBINGAN</div>
                        <div class="guru-stat-value">{{ $totalSiswaBimbingan ?? 0 }}</div>
                        <div class="guru-stat-caption">Siswa aktif magang</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---- Kartu 2: Jurnal Belum Dinilai (aksen amber) ---- --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm guru-stat-card">
                <div class="card-body d-flex align-items-start gap-3">
                    <div class="guru-stat-icon guru-stat-icon-amber">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div>
                        <div class="guru-stat-label">JURNAL BELUM DINILAI</div>
                        <div class="guru-stat-value text-warning-emphasis">{{ $jurnalBelumDinilai ?? 0 }}</div>
                        <div class="guru-stat-caption">Perlu evaluasi segera</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---- Kartu 3: Kehadiran Hari Ini ---- --}}
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm guru-stat-card">
                <div class="card-body d-flex align-items-start gap-3">
                    <div class="guru-stat-icon guru-stat-icon-green">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        @php
                            // Menghitung persentase kehadiran hari ini, aman dari pembagian nol
                            $hadir = $kehadiranHadir ?? 0;
                            $total = $kehadiranTotal ?? 0;
                            $persenHadir = $total > 0 ? round(($hadir / $total) * 100) : 0;
                        @endphp
                        <div class="guru-stat-label">KEHADIRAN HARI INI</div>
                        <div class="guru-stat-value">{{ $hadir }}/{{ $total }}</div>
                        <div class="guru-stat-caption">{{ $persenHadir }}% siswa hadir</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         3. DUA WIDGET BERDAMPINGAN
         Kiri: Jurnal Perlu Evaluasi | Kanan: Daftar Siswa Bimbingan
    ============================================================ --}}
    <div class="row g-3 mt-1">

        {{-- ------------------------------------------------------
             3.1 WIDGET: JURNAL PERLU EVALUASI
             Daftar laporan jurnal siswa terbaru yang menunggu review
        ------------------------------------------------------- --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm guru-widget-card h-100">

                {{-- Header widget --}}
                <div class="card-header bg-white guru-widget-header">
                    <i class="bi bi-clock-history text-warning-emphasis me-2"></i>
                    <span class="fw-bold">Jurnal Perlu Evaluasi</span>
                </div>

                {{-- Body: list jurnal --}}
                <div class="card-body p-0">
                    <ul class="list-unstyled mb-0">
                        @forelse ($jurnalPerluEvaluasi ?? [] as $jurnal)
                            <li class="guru-list-item d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-start gap-2">
                                    {{-- Ikon dokumen kecil di kiri --}}
                                    <i class="bi bi-file-earmark-text guru-list-icon"></i>
                                    <div>
                                        <div class="guru-list-title">{{ $jurnal->siswa_nama }}</div>
                                        <div class="guru-list-sub">
                                            {{ $jurnal->deskripsi }} · {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Tombol Review menuju halaman Validasi Jurnal (validasi dilakukan lewat modal di sana) --}}
                                    <a href="{{ route('guru.jurnal.index') }}"
                                    class="btn btn-sm guru-btn-review">
                                        Review
                                    </a>
                            </li>
                        @empty
                            {{-- ---- Empty state jika tidak ada jurnal yang perlu direview ---- --}}
                            <li class="text-center text-muted py-4">
                                <i class="bi bi-check2-circle guru-empty-icon"></i>
                                <p class="mb-0 mt-2 small">Semua jurnal sudah dievaluasi. Kerja bagus!</p>
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Footer: link lihat semua tugas jurnal --}}
                @if (($jurnalBelumDinilai ?? 0) > 0)
                    <div class="card-footer bg-white text-center guru-widget-footer">
                        <a href="{{ route('guru.jurnal.index') }}" class="guru-link-footer">
                            Lihat semua tugas ({{ $jurnalBelumDinilai }})
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ------------------------------------------------------
             3.2 WIDGET: DAFTAR SISWA BIMBINGAN
             Status kehadiran harian masing-masing siswa
        ------------------------------------------------------- --}}
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm guru-widget-card h-100">

                {{-- Header widget + link Lihat Semua --}}
                <div class="card-header bg-white guru-widget-header d-flex align-items-center justify-content-between">
                    <span>
                        <i class="bi bi-person-lines-fill text-primary me-2"></i>
                        <span class="fw-bold">Daftar Siswa Bimbingan</span>
                    </span>
                    <a href="{{ route('guru.siswa.index') }}" class="guru-link-small">Lihat Semua</a>
                </div>

                {{-- Body: list siswa bimbingan --}}
                <div class="card-body p-0">
                    <ul class="list-unstyled mb-0">
                        @forelse ($siswaBimbingan ?? [] as $siswa)
                            <li class="guru-list-item d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <div class="guru-list-title">{{ $siswa->nama }}</div>
                                    <div class="guru-list-sub">{{ $siswa->tempat_magang }}</div>
                                </div>

                                {{-- Badge status kehadiran, warna menyesuaikan status --}}
                                @php
                                    // Menentukan warna badge berdasarkan status kehadiran hari ini
                                    $statusClass = match ($siswa->status_kehadiran) {
                                        'Hadir'        => 'guru-badge-hadir',
                                        'Sakit'        => 'guru-badge-sakit',
                                        'Izin'         => 'guru-badge-izin',
                                        'Alfa'         => 'guru-badge-alfa',
                                        default        => 'guru-badge-belum', // Belum Absen
                                    };
                                @endphp
                                <span class="badge rounded-pill guru-status-badge {{ $statusClass }}">
                                    {{ $siswa->status_kehadiran }}
                                </span>
                            </li>
                        @empty
                            {{-- ---- Empty state jika belum ada siswa bimbingan ---- --}}
                            <li class="text-center text-muted py-4">
                                <i class="bi bi-person-x guru-empty-icon"></i>
                                <p class="mb-0 mt-2 small">Belum ada siswa bimbingan yang terdaftar.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         4. CSS KHUSUS HALAMAN INI
         Ditaruh langsung di dalam @section('content') supaya pasti ke-render
         walaupun layout tidak menyediakan @yield('styles').
    ============================================================ --}}
    <style>
        /* ==============================================================
           TOKEN WARNA HALAMAN DASHBOARD GURU
        ============================================================== */
        :root {
            --guru-primary: #2f5cf0;
            --guru-primary-dark: #2447c4;
            --guru-amber: #f5a524;
            --guru-amber-soft: #fff8ec;
            --guru-green: #1a9c5a;
            --guru-green-soft: #eafaf1;
            --guru-red: #ee4148;
            --guru-red-soft: #fdecec;
            --guru-ink: #1f2430;
            --guru-muted: #8a92a3;
            --guru-border: #e9ebf0;
        }

        /* ---- Hero banner biru dengan gradasi lembut ---- */
        .guru-hero {
            background: linear-gradient(135deg, var(--guru-primary), var(--guru-primary-dark));
            border-radius: 1rem;
            padding: 1.5rem 1.75rem;
        }

        .guru-hero-sub {
            font-size: 0.86rem;
        }

        /* ---- Tombol "Lihat Jurnal" putih solid di atas hero biru ---- */
        .guru-btn-hero {
            background: #fff;
            color: var(--guru-primary);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.55rem 1.1rem;
            border-radius: 8px;
            border: none;
            white-space: nowrap;
        }

        .guru-btn-hero:hover {
            background: #f1f4ff;
            color: var(--guru-primary-dark);
        }

        /* ---- Kartu statistik (3 kartu ringkasan) ---- */
        .guru-stat-card {
            border-radius: 0.9rem;
        }

        .guru-stat-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .guru-stat-icon-blue  { background: #eef2ff; color: var(--guru-primary); }
        .guru-stat-icon-amber { background: var(--guru-amber-soft); color: var(--guru-amber); }
        .guru-stat-icon-green { background: var(--guru-green-soft); color: var(--guru-green); }

        .guru-stat-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--guru-muted);
            text-transform: uppercase;
        }

        .guru-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--guru-ink);
            line-height: 1.3;
        }

        .text-warning-emphasis {
            color: var(--guru-amber) !important;
        }

        .guru-stat-caption {
            font-size: 0.76rem;
            color: var(--guru-muted);
        }

        /* ---- Widget card (Jurnal Perlu Evaluasi & Daftar Siswa Bimbingan) ---- */
        .guru-widget-card {
            border-radius: 0.9rem;
            overflow: hidden;
        }

        .guru-widget-header {
            border-bottom: 1px solid var(--guru-border);
            padding: 0.9rem 1.1rem;
            font-size: 0.9rem;
        }

        .guru-widget-footer {
            border-top: 1px solid var(--guru-border);
            padding: 0.7rem;
        }

        /* ---- Baris list item (jurnal / siswa) ---- */
        .guru-list-item {
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid var(--guru-border);
        }

        .guru-list-item:last-child {
            border-bottom: none;
        }

        .guru-list-icon {
            color: var(--guru-amber);
            font-size: 0.9rem;
            margin-top: 3px;
        }

        .guru-list-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--guru-ink);
        }

        .guru-list-sub {
            font-size: 0.76rem;
            color: var(--guru-muted);
            margin-top: 1px;
        }

        /* ---- Tombol "Review" outline amber ---- */
        .guru-btn-review {
            background: #fff;
            border: 1px solid #fbe8c6;
            color: var(--guru-amber);
            font-weight: 600;
            font-size: 0.76rem;
            padding: 0.3rem 0.75rem;
            border-radius: 6px;
            white-space: nowrap;
        }

        .guru-btn-review:hover {
            background: var(--guru-amber-soft);
            color: #b3720a;
        }

        /* ---- Link "Lihat semua tugas (n)" di footer widget kiri ---- */
        .guru-link-footer {
            color: var(--guru-red);
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
        }

        .guru-link-footer:hover {
            text-decoration: underline;
            color: #d13438;
        }

        /* ---- Link "Lihat Semua" kecil di header widget kanan ---- */
        .guru-link-small {
            color: var(--guru-primary);
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
        }

        .guru-link-small:hover {
            text-decoration: underline;
        }

        /* ---- Badge status kehadiran siswa ---- */
        .guru-status-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.35rem 0.7rem;
        }

        .guru-badge-hadir { background: var(--guru-green-soft); color: var(--guru-green); }
        .guru-badge-sakit { background: var(--guru-amber-soft); color: #b3720a; }
        .guru-badge-izin  { background: #eef4ff; color: #3b6fe0; }
        .guru-badge-alfa  { background: var(--guru-red-soft); color: var(--guru-red); }
        .guru-badge-belum { background: #f1f2f4; color: var(--guru-muted); }

        /* ---- Empty state ikon besar abu-abu ---- */
        .guru-empty-icon {
            font-size: 2rem;
            color: #d7dbe3;
        }

        /* ---- Responsif: hero banner menumpuk vertikal di layar kecil ---- */
        @@media (max-width: 575.98px) {
            .guru-hero {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .guru-btn-hero {
                width: 100%;
                justify-content: center;
            }

            .guru-list-item {
                align-items: flex-start !important;
                flex-direction: column;
                gap: .65rem !important;
            }

            .guru-list-item .btn,
            .guru-list-item .badge { align-self: flex-end; }
        }
    </style>

@endsection

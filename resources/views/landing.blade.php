{{-- ============================================
     LANDING PAGE — numpang di layout guest
     ============================================ --}}
@extends('layouts.guest')

@section('title', 'SIMMAS - Sistem Informasi Manajemen Magang Siswa')
@section('meta_description', 'Platform manajemen magang siswa SMK yang menghubungkan sekolah, guru pembimbing, dan dunia usaha industri.')

{{-- ============================================
     CSS KHUSUS LANDING PAGE
     ============================================ --}}
@push('styles')
<style>

    /* ===== 1. HERO SECTION — background terang + shape diagonal biru ===== */
    .hero-simmas {
        position: relative;
        overflow: hidden;
        background: var(--simmas-paper);
        padding: 100px 0 90px;
    }
    /* Shape diagonal biru tegas di sisi kanan, ciri khas visual utama halaman ini */
    .hero-simmas__diagonal {
        position: absolute;
        top: 0; right: 0; bottom: 0;
        width: 46%;
        background: linear-gradient(160deg, var(--simmas-blue) 0%, var(--simmas-blue-dark) 100%);
        clip-path: polygon(35% 0, 100% 0, 100% 100%, 0% 100%);
        z-index: 0;
    }
    /* Lingkaran dekoratif tipis di kiri bawah, murni aksen halus */
    .hero-simmas__rings {
        position: absolute;
        bottom: -140px; left: -140px;
        width: 340px; height: 340px;
        border: 40px solid rgba(58, 92, 224, 0.06);
        border-radius: 50%;
        z-index: 0;
    }
    .hero-simmas__content { position: relative; z-index: 1; }

    /* Label kecil di atas judul */
    .hero-simmas__eyebrow {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--simmas-muted);
    }
    /* Judul utama hero — besar, tegas, hitam pekat */
    .hero-simmas__title {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: clamp(2.8rem, 5vw, 3.8rem);
        line-height: 1.05;
        letter-spacing: -0.03em;
        color: var(--simmas-ink);
    }
    .hero-simmas__desc {
        font-size: 1.02rem;
        color: var(--simmas-muted);
        max-width: 460px;
        line-height: 1.65;
    }
    /* Checklist 3 poin keunggulan, ikon centang bulat biru */
    .hero-simmas__checklist { list-style: none; padding: 0; margin: 0 0 28px; }
    .hero-simmas__checklist li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.94rem;
        color: var(--simmas-ink);
        margin-bottom: 10px;
    }
    .hero-simmas__check {
        width: 20px; height: 20px;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        background: var(--simmas-blue-light);
        color: var(--simmas-blue);
        font-size: 0.7rem;
    }
    /* Tombol "Lihat Fitur" versi outline putih */
    .hero-simmas__btn-outline {
        border: 1px solid var(--simmas-border);
        color: var(--simmas-ink);
        font-weight: 600;
        padding: 9px 22px;
        border-radius: 30px;
        background: #fff;
    }
    .hero-simmas__btn-outline:hover { background: var(--simmas-paper); color: var(--simmas-ink); }

    /* ===== 2. HERO PREVIEW — kartu dashboard hidup, mengambang di atas shape biru ===== */
    .hero-preview { position: relative; z-index: 2; padding: 24px; }

    .hero-preview__window {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 40px 70px -25px rgba(19, 27, 58, 0.35);
        overflow: hidden;
        transform: rotate(-1.5deg);
        transition: transform .3s ease;
    }
    .hero-preview:hover .hero-preview__window { transform: rotate(0deg); }

    .hero-preview__titlebar {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 14px;
        background: #F1F3F9;
    }
    .hero-preview__dot { width: 7px; height: 7px; border-radius: 50%; background: #D7DBE6; }

    .hero-preview__body { display: flex; min-height: 300px; }

    /* Sidebar dengan ikon + label teks, sesuai menu admin sesungguhnya */
    .hero-preview__sidebar {
        width: 128px;
        background: #fff;
        border-right: 1px solid var(--simmas-border);
        padding: 14px 10px;
        flex-shrink: 0;
    }
    .hero-preview__side-brand {
        display: flex; align-items: center; gap: 6px;
        font-weight: 800; font-size: 0.78rem;
        color: var(--simmas-ink);
        margin-bottom: 16px;
        padding-left: 4px;
    }
    .hero-preview__side-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.68rem;
        color: var(--simmas-muted);
        padding: 7px 8px;
        border-radius: 7px;
        margin-bottom: 2px;
    }
    .hero-preview__side-item--active {
        background: var(--simmas-blue-light);
        color: var(--simmas-blue);
        font-weight: 700;
    }

    /* Konten utama dashboard mini */
    .hero-preview__main { flex: 1; padding: 16px 18px; min-width: 0; }
    .hero-preview__main-title { font-size: 0.85rem; font-weight: 700; margin-bottom: 12px; }

    /* 3 kartu statistik mini */
    .hero-preview__stats { display: flex; gap: 8px; margin-bottom: 16px; }
    .hero-preview__stat { flex: 1; }
    .hero-preview__stat-label { display: block; font-size: 0.6rem; color: var(--simmas-muted); margin-bottom: 2px; }
    .hero-preview__stat-value { display: block; font-size: 1.1rem; font-weight: 800; font-family: var(--font-display); color: var(--simmas-ink); }

    /* Tabel cuplikan permohonan magang */
    .hero-preview__table-head { font-size: 0.68rem; font-weight: 700; color: var(--simmas-ink); margin-bottom: 8px; }
    .hero-preview__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 0;
        border-top: 1px solid var(--simmas-border);
        font-size: 0.68rem;
    }
    .hero-preview__row-name { color: var(--simmas-ink); font-weight: 500; }
    .hero-preview__badge { font-size: 0.6rem; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
    .hero-preview__badge--success { background: var(--simmas-green-light); color: var(--simmas-green); }
    .hero-preview__badge--warning { background: var(--simmas-amber-light); color: var(--simmas-amber); }

    /* Badge "LIVE" mengambang di pojok kanan atas kartu, di atas shape biru */
    .hero-preview__live-badge {
        position: absolute;
        top: 4px; right: 4px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
        background: var(--simmas-blue-dark);
        color: #fff;
        padding: 8px 16px;
        border-radius: 14px;
        box-shadow: 0 14px 26px -10px rgba(19, 27, 58, 0.5);
        z-index: 3;
    }
    .hero-preview__live-tag {
        display: flex; align-items: center; gap: 5px;
        font-size: 0.6rem; font-weight: 700; letter-spacing: 0.05em;
        color: rgba(255,255,255,0.7);
    }
    .hero-preview__live-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #4ADE80;
        animation: livePulse 1.6s ease-in-out infinite;
    }
    .hero-preview__live-value { font-size: 0.92rem; font-weight: 800; }
    @keyframes livePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0.6); }
        50% { box-shadow: 0 0 0 5px rgba(74, 222, 128, 0); }
    }

    /* ===== 3. FITUR PERAN (3 kartu) ===== */
    /* GANTI: background jadi biru muda + posisi relative buat naruh ring dekoratif,
   biar senada sama section .statistik-simmas */
.fitur-simmas {
    padding: 90px 0;
    background: var(--simmas-blue-light); /* sebelumnya #fff */
    border-top: 1px solid var(--simmas-border);    /* BARU, samain kayak statistik */
    border-bottom: 1px solid var(--simmas-border); /* BARU, samain kayak statistik */
    position: relative;  /* wajib: patokan posisi ring dekoratif */
    overflow: hidden;    /* wajib: kunci ring dekoratif biar nggak meluber */
}

/* BARU: ring dekoratif, disalin dari .statistik-simmas__decor,
   ditaruh di KIRI biar beda sisi sama punya statistik (kanan) */
.fitur-simmas__decor {
    position: absolute;
    top: -80px; left: -80px;
    width: 220px; height: 220px;
    border: 30px solid rgba(58, 92, 224, 0.08);
    border-radius: 50%;
    z-index: 0;
    pointer-events: none; /* murni hiasan, jangan ganggu klik kartu */
}

/* BARU: container harus di atas ring dekoratif */
.fitur-simmas .container {
    position: relative;
    z-index: 1;
}

    .card-simmas {
        border: 1px solid var(--simmas-border);
        border-radius: 18px;
        transition: transform .2s ease, box-shadow .2s ease;
        height: 100%;
    }
    .card-simmas:hover { transform: translateY(-4px); box-shadow: 0 16px 32px -12px rgba(19, 27, 58, 0.12); }

    /* Ikon peran — BULAT PENUH (border-radius: 50%), senada
       sama .simmas-mark (logo navbar/footer) dan
       .panduan-simmas__step-number (nomor 1-4 di Alur Penggunaan) */
    .fitur-simmas__icon {
        width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        font-size: 1.3rem;
        margin-bottom: 18px;
    }
    .fitur-simmas__icon--siswa { background: var(--simmas-blue-light); color: var(--simmas-blue); }
    .fitur-simmas__icon--guru  { background: var(--simmas-green-light); color: var(--simmas-green); }
    .fitur-simmas__icon--admin { background: #F1F3F9; color: var(--simmas-ink); }

    /* ===== 4. STATISTIK — terang, senada hero ===== */
    .statistik-simmas {
        background: var(--simmas-blue-light);
        padding: 60px 0;
        border-top: 1px solid var(--simmas-border);
        border-bottom: 1px solid var(--simmas-border);
        position: relative;  /* wajib: patokan posisi buat ring dekoratif di bawah */
        overflow: hidden;    /* wajib: kunci ring dekoratif biar nggak meluber keluar section */
    }

    /* Ring dekoratif tipis di pojok kanan atas section — biar nggak polos,
       senada sama .hero-simmas__rings tapi dibuat lebih kecil & di sisi kanan */
    .statistik-simmas__decor {
        position: absolute;
        top: -80px; right: -80px;
        width: 220px; height: 220px;
        border: 30px solid rgba(58, 92, 224, 0.08);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none; /* murni hiasan, jangan ganggu interaksi */
    }

    /* Ikon bulat di atas tiap angka statistik — bentuknya sama
       kayak .fitur-simmas__icon (bulat penuh), diperkecil karena
       ruangnya sempit (4 kolom sejajar) */
    .statistik-simmas__icon {
        width: 44px; height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        color: var(--simmas-blue);
        font-size: 1.15rem;
        margin: 0 auto 14px;
        box-shadow: 0 8px 18px -8px rgba(19, 27, 58, 0.15);
        position: relative;
        z-index: 1; /* tampil di atas ring dekoratif */
    }

    .statistik-simmas__angka {
        font-family: var(--font-display);
        font-weight: 800;
        font-size: 2.4rem;
        color: var(--simmas-blue-dark);
        position: relative;
        z-index: 1;
    }
    .statistik-simmas__label {
        color: var(--simmas-muted);
        font-size: 0.85rem;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    /* ===== 5. PANDUAN ALUR — timeline horizontal, lega ===== */
    .panduan-simmas {
        padding: 90px 0;
        background: var(--simmas-blue-light); /* sebelumnya var(--simmas-paper) */
        border-top: 1px solid var(--simmas-border);    /* BARU, samain kayak statistik */
        border-bottom: 1px solid var(--simmas-border); /* BARU, samain kayak statistik */
        position: relative;
        overflow: hidden;
    }
    .panduan-simmas__subtitle { margin-bottom: 70px !important; }

    /* Blob dekoratif organik di sisi kiri bawah section — variasi dari
       .hero-simmas__rings, dibuat bentuk blob (bukan lingkaran sempurna)
       biar section ini punya "ciri" visual sendiri */
    .panduan-simmas__decor {
        position: absolute;
        bottom: -100px; left: -60px;
        width: 260px; height: 260px;
        background: rgba(58, 92, 224, 0.05);
        border-radius: 40% 60% 55% 45% / 50% 40% 60% 50%;
        z-index: 0;
        pointer-events: none;
    }

    .panduan-simmas__track { position: relative; margin-top: 50px; z-index: 1; }
    .panduan-simmas__track::before {
        content: '';
        position: absolute;
        top: 28px; left: 8%; right: 8%;
        height: 2px;
        background: var(--simmas-border);
        z-index: 0;
    }
    .panduan-simmas__step-number {
        width: 56px; height: 56px;
        display: flex; align-items: center; justify-content: center;
        background: #fff;
        border: 2px solid var(--simmas-blue);
        color: var(--simmas-blue);
        font-weight: 800;
        font-size: 1.2rem;
        border-radius: 50%;
        margin: 0 auto 26px;
        position: relative;
        z-index: 1;
    }

    /* Badge ikon kecil yang "nempel" di pojok kanan atas lingkaran nomor —
       konsepnya sama kayak .hero-preview__live-badge (badge mengambang),
       supaya tiap step kelihatan lebih hidup, bukan cuma angka polos */
    .panduan-simmas__step-icon {
        position: absolute;
        top: -6px; right: -6px;
        width: 26px; height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--simmas-blue);
        color: #fff;
        border-radius: 50%;
        border: 2px solid #fff; /* ring putih, biar "motong" lingkaran nomor di belakangnya */
        font-size: 0.7rem;
        z-index: 2;
    }

    .panduan-simmas__step-title { margin-bottom: 10px !important; }
    </style>
@endpush

@section('content')

    {{-- ============================================
         1. HERO SECTION
         ============================================ --}}
    <section class="hero-simmas">

        {{-- Shape diagonal biru — elemen visual utama sisi kanan --}}
        <div class="hero-simmas__diagonal"></div>
        {{-- Lingkaran dekoratif tipis sisi kiri bawah --}}
        <div class="hero-simmas__rings"></div>

        <div class="container hero-simmas__content">
            <div class="row align-items-center g-5">

                {{-- ===== Kolom kiri: pesan utama ===== --}}
                <div class="col-lg-6">
                    <p class="hero-simmas__eyebrow mb-2">{{ config('simmas.kepanjangan', 'Sistem Informasi Manajemen Magang Siswa') }}</p>
                    <h1 class="hero-simmas__title mb-3" style="font-size: clamp(2.2rem, 4.5vw, 3.4rem); line-height: 1.15;">
                        {{ config('simmas.hero_judul', 'Magang lebih teratur.') }}
                    </h1>
                    <p class="hero-simmas__desc mb-4">
                        {{ config('simmas.hero_deskripsi', 'Platform manajemen magang siswa SMK yang menghubungkan sekolah, guru pembimbing, dan dunia usaha dalam satu sistem terpadu.') }}
                    </p>

                    {{-- Checklist 3 poin keunggulan --}}
                    <ul class="hero-simmas__checklist">
                        <li><span class="hero-simmas__check"><i class="bi bi-check-lg"></i></span> Penempatan magang terpusat & transparan</li>
                        <li><span class="hero-simmas__check"><i class="bi bi-check-lg"></i></span> Monitoring kehadiran & jurnal real-time</li>
                        <li><span class="hero-simmas__check"><i class="bi bi-check-lg"></i></span> Koordinasi sekolah, guru, dan industri</li>
                    </ul>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <a href="{{ route('login') }}" class="btn simmas-btn-primary btn-lg">Mulai Sekarang &rarr;</a>
                        <a href="#fitur" class="btn hero-simmas__btn-outline">Lihat Fitur</a>
                    </div>
                        </div>

                {{-- ===== Kolom kanan: preview dashboard hidup ===== --}}
                <div class="col-lg-6">
                    <div class="hero-preview">

                        <div class="hero-preview__window">

                            {{-- Titlebar ala browser --}}
                            <div class="hero-preview__titlebar">
                                <span class="hero-preview__dot"></span>
                                <span class="hero-preview__dot"></span>
                                <span class="hero-preview__dot"></span>
                            </div>

                            <div class="hero-preview__body">

                                {{-- Sidebar dengan label teks lengkap --}}
                                <aside class="hero-preview__sidebar">
                                    <p class="hero-preview__side-brand"><i class="bi bi-mortarboard-fill"></i> {{ config('simmas.nama_aplikasi', 'SIMMAS') }}</p>
                                    <div class="hero-preview__side-item hero-preview__side-item--active"><i class="bi bi-grid-1x2-fill"></i> Beranda</div>
                                    <div class="hero-preview__side-item"><i class="bi bi-person-fill"></i> Data Siswa</div>
                                    <div class="hero-preview__side-item"><i class="bi bi-person-workspace"></i> Data Guru</div>
                                    <div class="hero-preview__side-item"><i class="bi bi-building"></i> Mitra DUDI</div>
                                    <div class="hero-preview__side-item"><i class="bi bi-send-fill"></i> Permohonan</div>
                                    <div class="hero-preview__side-item"><i class="bi bi-diagram-3-fill"></i> Penempatan</div>
                                    <div class="hero-preview__side-item"><i class="bi bi-bar-chart-fill"></i> Laporan</div>
                                </aside>

                                {{-- Konten dashboard mini --}}
                                <div class="hero-preview__main">
                                    <p class="hero-preview__main-title">Dashboard</p>

                                    {{-- 3 statistik — data real dari controller --}}
                                    <div class="hero-preview__stats">
                                        <div class="hero-preview__stat">
                                            <span class="hero-preview__stat-label">Total Siswa</span>
                                            <span class="hero-preview__stat-value">{{ $siswaAktif }}</span>
                                        </div>
                                        <div class="hero-preview__stat">
                                            <span class="hero-preview__stat-label">Total Guru</span>
                                            <span class="hero-preview__stat-value">{{ $totalGuru }}</span>
                                        </div>
                                        <div class="hero-preview__stat">
                                            <span class="hero-preview__stat-label">Mitra DUDI</span>
                                            <span class="hero-preview__stat-value">{{ $totalMitraDudi }}</span>
                                        </div>
                                    </div>

                                   {{-- Cuplikan tabel — data permohonan magang terbaru dari database --}}
                                        <p class="hero-preview__table-head">Daftar Permohonan Magang Siswa</p>
                                        @forelse ($pengajuanTerbaru as $pengajuan)
                                            @php
                                                $namaSiswa = $pengajuan->siswa->profile->nama ?? '-';
                                                $badgeClass = match($pengajuan->status) {
                                                    'disetujui' => 'hero-preview__badge--success',
                                                    'ditolak'   => 'hero-preview__badge--danger',
                                                    default     => 'hero-preview__badge--warning',
                                                };
                                                $statusLabel = match($pengajuan->status) {
                                                    'disetujui' => 'Diterima',
                                                    'ditolak'   => 'Ditolak',
                                                    default     => 'Menunggu',
                                                };
                                            @endphp
                                                <div class="hero-preview__row">
                                                    <span class="hero-preview__row-name">{{ $namaSiswa }}</span>
                                                    <span class="hero-preview__badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                                </div>
                                            @empty
                                                <div class="hero-preview__row">
                                                    <span class="hero-preview__row-name text-muted">Belum ada pengajuan</span>
                                                </div>
                                            @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Badge "LIVE" — angka WAJIB dari database --}}
                        <span class="hero-preview__live-badge">
                            <span class="hero-preview__live-tag"><span class="hero-preview__live-dot"></span> LIVE</span>
                            <span class="hero-preview__live-value">{{ $siswaAktif }} Siswa</span>
                        </span>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================
         2. FITUR PERAN
         ============================================ --}}
    <section class="fitur-simmas" id="fitur">

    {{-- Ring dekoratif — murni hiasan, ikut pola section Statistik --}}
    <div class="fitur-simmas__decor"></div>

        {{-- Konten fitur peran --}}
        <div class="container">
            <h2 class="text-center fw-bold mb-2">Fitur untuk Setiap Peran</h2>
            <p class="text-center text-muted mx-auto mb-5" style="max-width: 500px;">
                Kartu fitur terpisah untuk Siswa Magang, Guru Pembimbing, dan Administrator Sekolah.
            </p>
            <div class="row g-4">

                {{-- Kartu 1: Siswa Magang --}}
                <div class="col-md-4">
                    <div class="card card-simmas p-4">
                        <div class="fitur-simmas__icon fitur-simmas__icon--siswa"><i class="bi bi-mortarboard-fill"></i></div>
                        <h3 class="h5 fw-bold">Siswa Magang</h3>
                        <p class="text-muted small mb-0">Isi jurnal harian, pantau presensi, dan lihat status persetujuan magang secara real-time.</p>
                    </div>
                </div>

                {{-- Kartu 2: Guru Pembimbing --}}
                <div class="col-md-4">
                    <div class="card card-simmas p-4">
                        <div class="fitur-simmas__icon fitur-simmas__icon--guru"><i class="bi bi-person-workspace"></i></div>
                        <h3 class="h5 fw-bold">Guru Pembimbing</h3>
                        <p class="text-muted small mb-0">Monitoring kehadiran dan jurnal siswa bimbingan secara terpusat dan efisien.</p>
                    </div>
                </div>

                {{-- Kartu 3: Administrator Sekolah --}}
                <div class="col-md-4">
                    <div class="card card-simmas p-4">
                        <div class="fitur-simmas__icon fitur-simmas__icon--admin"><i class="bi bi-building-fill-gear"></i></div>
                        <h3 class="h5 fw-bold">Administrator Sekolah</h3>
                        <p class="text-muted small mb-0">Kelola data DUDI mitra, penempatan siswa, dan laporan magang sekolah.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================
         3. STATISTIK RINGKAS
         ============================================ --}}
    <section class="statistik-simmas">

        {{-- Ring dekoratif — murni hiasan, tidak ada konten di dalamnya --}}
        <div class="statistik-simmas__decor"></div>

        <div class="container">
            <div class="row text-center g-4">

                {{-- Kartu 1: Jurnal Disetujui — ikon buku dengan centang --}}
                <div class="col-6 col-md-3">
                    <span class="statistik-simmas__icon"><i class="bi bi-journal-check"></i></span>
                    <p class="statistik-simmas__angka mb-0">{{ $jurnalDisetujui }}</p>
                    <p class="statistik-simmas__label">Jurnal Disetujui</p>
                </div>

                {{-- Kartu 2: Presensi Tercatat — ikon kalender dengan centang --}}
                <div class="col-6 col-md-3">
                    <span class="statistik-simmas__icon"><i class="bi bi-calendar2-check"></i></span>
                    <p class="statistik-simmas__angka mb-0">{{ $presensiTercatat }}</p>
                    <p class="statistik-simmas__label">Presensi Tercatat</p>
                </div>

                {{-- Kartu 3: Siswa Aktif — ikon topi wisuda, senada logo SIMMAS --}}
                <div class="col-6 col-md-3">
                    <span class="statistik-simmas__icon"><i class="bi bi-mortarboard-fill"></i></span>
                    <p class="statistik-simmas__angka mb-0">{{ $siswaAktif }}</p>
                    <p class="statistik-simmas__label">Siswa Aktif</p>
                </div>

                {{-- Kartu 4: Total Mitra DUDI — ikon gedung --}}
                <div class="col-6 col-md-3">
                    <span class="statistik-simmas__icon"><i class="bi bi-building"></i></span>
                    <p class="statistik-simmas__angka mb-0">{{ $totalMitraDudi }}</p>
                    <p class="statistik-simmas__label">Total Mitra DUDI</p>
                </div>

            </div>
        </div>
    </section>

    {{-- ============================================
         4. PANDUAN ALUR
         ============================================ --}}
    <section class="panduan-simmas" id="panduan">
        <div class="container">

            <h2 class="text-center fw-bold mb-2">Alur Penggunaan</h2>
            <p class="text-center text-muted mx-auto panduan-simmas__subtitle" style="max-width: 500px;">
                4 langkah sederhana dari registrasi hingga monitoring.
            </p>

            <div class="row g-5 text-center panduan-simmas__track">

                {{-- Blob dekoratif — murni hiasan, diletakkan sekali di dalam track --}}
                <div class="panduan-simmas__decor"></div>

                {{-- Step 1: Registrasi DUDI — badge ikon gedung dengan tanda tambah --}}
                <div class="col-md-3">
                    <div class="panduan-simmas__step-number">
                        1
                        <span class="panduan-simmas__step-icon"><i class="bi bi-building-add"></i></span>
                    </div>
                    <h3 class="h6 fw-bold panduan-simmas__step-title">Registrasi DUDI</h3>
                    <p class="small text-muted">Sekolah mendaftarkan mitra dunia usaha/industri.</p>
                </div>

                {{-- Step 2: Pengajuan — badge ikon kertas terkirim --}}
                <div class="col-md-3">
                    <div class="panduan-simmas__step-number">
                        2
                        <span class="panduan-simmas__step-icon"><i class="bi bi-send-fill"></i></span>
                    </div>
                    <h3 class="h6 fw-bold panduan-simmas__step-title">Pengajuan</h3>
                    <p class="small text-muted">Siswa mengajukan penempatan magang.</p>
                </div>

                {{-- Step 3: Persetujuan — badge ikon centang tervalidasi --}}
                <div class="col-md-3">
                    <div class="panduan-simmas__step-number">
                        3
                        <span class="panduan-simmas__step-icon"><i class="bi bi-patch-check-fill"></i></span>
                    </div>
                    <h3 class="h6 fw-bold panduan-simmas__step-title">Persetujuan</h3>
                    <p class="small text-muted">Guru pembimbing dan admin menyetujui pengajuan.</p>
                </div>

                {{-- Step 4: Monitoring Real-time — badge ikon grafik naik --}}
                <div class="col-md-3">
                    <div class="panduan-simmas__step-number">
                        4
                        <span class="panduan-simmas__step-icon"><i class="bi bi-graph-up"></i></span>
                    </div>
                    <h3 class="h6 fw-bold panduan-simmas__step-title">Monitoring Real-time</h3>
                    <p class="small text-muted">Presensi dan jurnal dipantau selama magang berjalan.</p>
                </div>

            </div>
        </div>
    </section>

@endsection
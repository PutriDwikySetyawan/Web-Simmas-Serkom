
@extends('layouts.siswa')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@php
    $namaSiswa            = $namaSiswa ?? 'Siswa Magang';
    $namaPerusahaan       = $namaPerusahaan ?? '-';
    $alamatSingkat        = $alamatSingkat ?? null;
    $namaGuru             = $namaGuru ?? '-';
    $nipGuru               = $nipGuru ?? null;
    $statusPengajuan      = strtolower($statusPengajuan ?? 'belum_mengajukan');
    $hariKe               = $hariKe ?? 0;
    $totalHariMagang      = $totalHariMagang ?? 0;
    $tanggalSelesaiMagang = $tanggalSelesaiMagang ?? '-';
    $progresPersen        = max(0, min(100, $progresPersen ?? 0));
    $totalKehadiran       = $totalKehadiran ?? 0;
    $totalJurnal          = $totalJurnal ?? 0;
    $jurnalTerverifikasi  = $jurnalTerverifikasi ?? 0;
    $sudahAbsenHariIni    = $sudahAbsenHariIni ?? false;
    $magangAktif          = $magangAktif ?? false;

    $statusMap = [
        'disahkan'       => ['label' => 'Sedang Magang', 'class' => 'sw-badge-green'],
        'lulus_magang'   => ['label' => 'Magang Selesai', 'class' => 'sw-badge-green'],
        'menunggu'       => ['label' => 'Menunggu Validasi', 'class' => 'sw-badge-orange'],
        'belum_disahkan' => ['label' => 'Belum Disahkan', 'class' => 'sw-badge-orange'],
        'ditolak'   => ['label' => 'Ditolak', 'class' => 'sw-badge-red'],
        'belum_mengajukan' => ['label' => 'Belum Mengajukan', 'class' => 'sw-badge-orange'],
    ];
    $statusBadge = $statusMap[$statusPengajuan] ?? $statusMap['belum_mengajukan'];
@endphp

@section('styles')
<style>
    :root {
        --sw-primary:      var(--guru-primary, #3B5BFB);
        --sw-primary-soft: var(--guru-primary-soft, #EAEEFF);
        --sw-ink:          var(--guru-ink, #111827);
        --sw-muted:        var(--guru-muted, #6B7280);
        --sw-border:       var(--guru-border, #E5E7EB);
        --sw-radius-sm: 10px;
        --sw-radius-md: 14px;
        --sw-radius-lg: 20px;
        --sw-shadow-rest:  0 1px 2px rgba(16, 24, 40, 0.04);
        --sw-shadow-hover: 0 10px 28px -10px rgba(16, 24, 40, 0.18);
        --sw-transition: 180ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (prefers-reduced-motion: reduce) {
        * { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
    }

    .sw-fade-in {
        opacity: 0;
        transform: translateY(10px);
        animation: swFadeIn 480ms ease forwards;
        animation-delay: calc(var(--sw-i, 0) * 90ms);
    }
    @keyframes swFadeIn { to { opacity: 1; transform: translateY(0); } }

    /* ================= BANNER ================= */
    .sw-banner {
        position: relative;
        overflow: hidden;
        border-radius: var(--sw-radius-lg);
        padding: 1.75rem 2rem;
        background: linear-gradient(120deg, #0F1E4D 0%, #23368F 45%, #3B5BFB 100%);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .sw-banner::after {
        content: "";
        position: absolute;
        top: -60px;
        right: -40px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.14) 0%, transparent 70%);
        pointer-events: none;
    }

    .sw-banner-eyebrow {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.72);
        margin-bottom: 0.4rem;
    }

    .sw-banner-title {
        font-size: 1.35rem;
        font-weight: 800;
        margin-bottom: 0.3rem;
        letter-spacing: -0.01em;
    }

    .sw-banner-desc {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.82);
        max-width: 46ch;
        line-height: 1.55;
        margin: 0;
    }

    .sw-banner-desc strong { color: #fff; font-weight: 700; }

    .sw-banner-cta {
        position: relative;
        z-index: 1;
        flex-shrink: 0;
        background: #fff;
        color: var(--sw-primary);
        font-weight: 700;
        font-size: 0.86rem;
        border: none;
        border-radius: var(--sw-radius-sm);
        padding: 0.65rem 1.15rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: transform var(--sw-transition), box-shadow var(--sw-transition);
        text-decoration: none;
    }

    .sw-banner-cta:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.35);
        color: var(--sw-primary);
    }

    .sw-banner-cta.is-done {
        background: rgba(255, 255, 255, 0.16);
        color: #fff;
        cursor: default;
    }
    .sw-banner-cta.is-done:hover { transform: none; box-shadow: none; }

    /* ================= STAT CARDS ================= */
    .sw-stat-card {
        position: relative;
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-md);
        padding: 1.15rem 1.3rem;
        height: 100%;
        box-shadow: var(--sw-shadow-rest);
        transition: transform var(--sw-transition), box-shadow var(--sw-transition);
    }

    .sw-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--sw-shadow-hover);
    }

    .sw-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.85rem;
    }

    .sw-stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--sw-muted);
    }

    .sw-stat-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .sw-stat-icon.blue   { background: var(--sw-primary-soft); color: var(--sw-primary); }
    .sw-stat-icon.green  { background: #E7F8EF; color: #1C9C5B; }
    .sw-stat-icon.violet { background: #F3E8FF; color: #7C3AED; }

    .sw-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--sw-ink);
        line-height: 1.1;
        margin-bottom: 0.25rem;
        font-variant-numeric: tabular-nums;
    }

    .sw-stat-sub {
        font-size: 0.76rem;
        color: var(--sw-muted);
    }

    .sw-progress-track {
        margin-top: 0.85rem;
        height: 5px;
        border-radius: 999px;
        background: var(--sw-border);
        overflow: hidden;
    }

    .sw-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--sw-primary), #6C8BFF);
        transition: width 600ms ease;
    }

    /* ================= INFO MAGANG CARD ================= */
    .sw-card {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-md);
        padding: 1.4rem 1.5rem;
        height: 100%;
    }

    .sw-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1.1rem;
    }

    .sw-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--sw-ink);
        margin-bottom: 0.2rem;
    }

    .sw-card-desc {
        font-size: 0.8rem;
        color: var(--sw-muted);
        margin: 0;
    }

    .sw-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .sw-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .sw-badge-green  { background: #E7F8EF; color: #1C9C5B; }
    .sw-badge-orange { background: #FFF4E5; color: #D98324; }
    .sw-badge-red    { background: #FDEAEA; color: #DC3545; }

    .sw-info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.85rem 0;
        border-top: 1px solid var(--sw-border);
    }

    .sw-info-row:first-of-type { border-top: none; padding-top: 0; }

    .sw-info-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .sw-info-label {
        font-size: 0.74rem;
        color: var(--sw-muted);
        margin-bottom: 0.1rem;
    }

    .sw-info-value {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--sw-ink);
        line-height: 1.3;
    }

    .sw-info-note {
        font-size: 0.76rem;
        color: var(--sw-muted);
        margin-top: 0.1rem;
    }

    /* ================= QUICK ACTION CARDS ================= */
    .sw-action-card {
        border-radius: var(--sw-radius-md);
        padding: 1.25rem 1.4rem;
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
        transition: transform var(--sw-transition), box-shadow var(--sw-transition);
    }

    .sw-action-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--sw-shadow-hover);
    }

    .sw-action-card.primary {
        background: linear-gradient(120deg, #23368F 0%, #3B5BFB 100%);
        color: #fff;
    }

    .sw-action-card.plain {
        background: #fff;
        border: 1px solid var(--sw-border);
        color: var(--sw-ink);
    }

    .sw-action-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .sw-action-card.primary .sw-action-icon { background: rgba(255,255,255,0.18); color: #fff; }
    .sw-action-card.plain .sw-action-icon   { background: var(--sw-primary-soft); color: var(--sw-primary); }

    .sw-action-title {
        font-size: 0.92rem;
        font-weight: 700;
        margin-bottom: 0.15rem;
    }

    .sw-action-desc {
        font-size: 0.8rem;
        line-height: 1.5;
        margin: 0;
    }

    .sw-action-card.primary .sw-action-desc { color: rgba(255, 255, 255, 0.82); }
    .sw-action-card.plain .sw-action-desc   { color: var(--sw-muted); }

    .sw-action-btn {
        margin-top: 0.15rem;
        align-self: flex-start;
        font-size: 0.82rem;
        font-weight: 700;
        border: none;
        border-radius: var(--sw-radius-sm);
        padding: 0.55rem 1.1rem;
        transition: transform var(--sw-transition), box-shadow var(--sw-transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .sw-action-card.primary .sw-action-btn {
        background: #fff;
        color: var(--sw-primary);
    }

    .sw-action-card.plain .sw-action-btn {
        background: var(--sw-primary);
        color: #fff;
    }

    .sw-action-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 18px -8px rgba(16,24,40,0.3); }

    .sw-action-btn:focus-visible,
    .sw-banner-cta:focus-visible {
        outline: 2px solid #fff;
        outline-offset: 2px;
    }

    @media (max-width: 575.98px) {
        .sw-banner { padding: 1.25rem; }
        .sw-banner-title { font-size: 1.25rem; }
        .sw-banner-desc { font-size: .86rem; }
        .sw-banner-cta { width: 100%; justify-content: center; }
        .sw-stat-card { min-height: auto; }
        .sw-stat-value { font-size: 1.25rem; }
        .sw-card-header { align-items: flex-start; gap: .75rem; }
        .sw-info-row { align-items: flex-start; }
        .sw-action-card { align-items: flex-start; flex-wrap: wrap; }
        .sw-action-card .sw-action-btn { width: 100%; justify-content: center; }
    }
</style>
@endsection

@section('content')

{{-- ============================================================ --}}
{{-- BANNER SAPAAN --}}
{{-- ============================================================ --}}
<div class="sw-banner sw-fade-in" style="--sw-i: 0;">
    <div>
        <div class="sw-banner-eyebrow">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        <div class="sw-banner-title">Semangat magang, {{ $namaSiswa }}!</div>
        <p class="sw-banner-desc mb-0">
            @if($magangAktif)
            Anda magang di <strong>{{ $namaPerusahaan }}</strong>.
            @if(!$sudahAbsenHariIni)
                Jangan lupa isi absensi hari ini.
            @else
                Absensi hari ini sudah tercatat, terima kasih!
            @endif
            @elseif($statusPengajuan === 'menunggu')
                Pengajuan magang Anda sedang menunggu validasi admin.
            @elseif($statusPengajuan === 'ditolak')
                Pengajuan magang Anda ditolak. Silakan ajukan kembali.
            @elseif($statusPengajuan === 'lulus_magang')
                Selamat, program magang Anda telah selesai.
            @else
                Anda belum memiliki penempatan magang aktif.
            @endif
        </p>
    </div>

    @if($magangAktif && !$sudahAbsenHariIni)
        <a href="{{ url('siswa/absensi-harian') }}" class="sw-banner-cta">
            Isi Absensi <i class="bi bi-arrow-right"></i>
        </a>
    @elseif($magangAktif)
        <span class="sw-banner-cta is-done">
            <i class="bi bi-check-circle-fill"></i> Absensi Terisi
        </span>
    @elseif($statusPengajuan === 'lulus_magang')
        <a href="{{ route('siswa.profil.index') }}" class="sw-banner-cta">
            Lihat Profil <i class="bi bi-arrow-right"></i>
        </a>
    @else
        <a href="{{ route('siswa.pengajuan.index') }}" class="sw-banner-cta">
            Ajukan Magang <i class="bi bi-arrow-right"></i>
        </a>
    @endif
</div>

{{-- ============================================================ --}}
{{-- STAT RINGKASAN --}}
{{-- ============================================================ --}}
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-lg-4">
        <div class="sw-stat-card sw-fade-in" style="--sw-i: 1;">
            <div class="sw-stat-top">
                <div class="sw-stat-label">Progres Magang</div>
                <div class="sw-stat-icon blue"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
            <div class="sw-stat-value">Hari ke-{{ $hariKe }}</div>
            <div class="sw-stat-sub">dari {{ $totalHariMagang }} hari magang (s/d {{ $tanggalSelesaiMagang }})</div>
            <div class="sw-progress-track">
                <div class="sw-progress-fill" style="width: {{ $progresPersen }}%;"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="sw-stat-card sw-fade-in" style="--sw-i: 2;">
            <div class="sw-stat-top">
                <div class="sw-stat-label">Total Kehadiran</div>
                <div class="sw-stat-icon green"><i class="bi bi-check2-circle"></i></div>
            </div>
            <div class="sw-stat-value">{{ $totalKehadiran }} hari</div>
            <div class="sw-stat-sub">Kehadiran tercatat</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="sw-stat-card sw-fade-in" style="--sw-i: 3;">
            <div class="sw-stat-top">
                <div class="sw-stat-label">Jurnal Ditulis</div>
                <div class="sw-stat-icon violet"><i class="bi bi-journal-richtext"></i></div>
            </div>
            <div class="sw-stat-value">{{ $totalJurnal }} laporan</div>
            <div class="sw-stat-sub">
                @if($totalJurnal > 0 && $jurnalTerverifikasi == $totalJurnal)
                    Semua terverifikasi
                @elseif($jurnalTerverifikasi > 0)
                    {{ $jurnalTerverifikasi }} dari {{ $totalJurnal }} terverifikasi
                @else
                    Belum ada yang terverifikasi
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- INFORMASI MAGANG + AKSI CEPAT --}}
{{-- ============================================================ --}}
<div class="row g-3">
    <div class="col-lg-6">
        <div class="sw-card sw-fade-in" style="--sw-i: 4;">
            <div class="sw-card-header">
                <div>
                    <div class="sw-card-title">Informasi Magang</div>
                    <p class="sw-card-desc">Detail tempat dan pembimbing magang Anda.</p>
                </div>
                <span class="sw-badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
            </div>

            <div class="sw-info-row">
                <div class="sw-info-icon"><i class="bi bi-building"></i></div>
                <div>
                    <div class="sw-info-label">Tempat Magang (DUDI)</div>
                    <div class="sw-info-value">{{ $namaPerusahaan }}</div>
                    @if($alamatSingkat)
                        <div class="sw-info-note">{{ $alamatSingkat }}</div>
                    @endif
                </div>
            </div>

            <div class="sw-info-row">
                <div class="sw-info-icon"><i class="bi bi-person-badge"></i></div>
                <div>
                    <div class="sw-info-label">Guru Pembimbing</div>
                    <div class="sw-info-value">{{ $namaGuru }}</div>
                    @if($nipGuru)
                        <div class="sw-info-note">NIP. {{ $nipGuru }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="d-flex flex-column gap-3 h-100">
            <div class="sw-action-card primary sw-fade-in" style="--sw-i: 5;">
                <div class="sw-action-icon"><i class="bi bi-calendar2-check"></i></div>
                <div>
                    <div class="sw-action-title">Absensi Hari Ini</div>
                    <p class="sw-action-desc">Jangan lupa mengisi daftar hadir sebelum jam kerja magang.</p>
                </div>
                @if($magangAktif)
                <a href="{{ url('siswa/absensi-harian') }}" class="sw-action-btn">
                    @if($sudahAbsenHariIni)
                        <i class="bi bi-check2"></i> Sudah Diisi
                    @else
                        Isi Absensi
                    @endif
                </a>
                @else
                <a href="{{ route('siswa.pengajuan.index') }}" class="sw-action-btn">Lihat Pengajuan</a>
                @endif
            </div>

            <div class="sw-action-card plain sw-fade-in" style="--sw-i: 6;">
                <div class="sw-action-icon"><i class="bi bi-journal-text"></i></div>
                <div>
                    <div class="sw-action-title">Jurnal Kegiatan</div>
                    <p class="sw-action-desc">Tulis pengalaman dan aktivitas harian Anda.</p>
                </div>
                @if($magangAktif)
                    <a href="{{ url('siswa/jurnal-kegiatan') }}" class="sw-action-btn">Tulis Jurnal</a>
                @else
                    <span class="text-muted small">Tersedia setelah penempatan disahkan.</span>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.siswa')

{{-- Mengatur judul halaman pada tab browser --}}
@section('title', 'Pengajuan Magang')

{{-- Mengatur judul halaman pada bagian header / navbar --}}
@section('page-title', 'Pengajuan Magang')

@php
    // ============================================================
    // 1. PENGAMBILAN & PENGOLAHAN DATA DARI CONTROLLER / DATABASE
    // ============================================================

    // Nilai default awal jika belum ada data pengajuan
    $status = 'belum_mengajukan';
    $namaDudi = '-';
    $alamatDudi = null;
    $posisi = '-';
    $tanggalPengajuan = '-';
    $periodeMagang = null;
    $catatanPenolakan = null;

    // Jika siswa sudah memiliki riwayat pengajuan magang mandiri
    if ($pengajuanTerakhir) {
        // Menentukan status pengesahan
        $status = match ($pengajuanTerakhir->status_pengesahan) {
            'disahkan', 'lulus_magang' => 'disetujui',
            default => $pengajuanTerakhir->status_pengesahan,
        };
        // Mengambil data relasi DUDI / Perusahaan Mitra
        $namaDudi = $pengajuanTerakhir->tempatMagang->nama_perusahaan ?? '-';
        $alamatDudi = $pengajuanTerakhir->tempatMagang->alamat ?? null;
        // Mengambil data posisi & format tanggal pengajuan
        $posisi = $pengajuanTerakhir->posisi ?? '-';
        $tanggalPengajuan = \Carbon\Carbon::parse($pengajuanTerakhir->created_at)->translatedFormat('d F Y');
        // Mengatur teks rentang tanggal periode magang
        $periodeMagang = \Carbon\Carbon::parse($pengajuanTerakhir->tanggal_mulai)->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($pengajuanTerakhir->tanggal_selesai)->translatedFormat('d M Y');
        $catatanPenolakan = $pengajuanTerakhir->catatan_penolakan;
    } elseif ($penempatan && ($siswa->status === 'sedang_magang' || $penempatan->status_pengesahan === 'disahkan')) {
        // Fallback jika siswa sudah resmi ditempatkan oleh admin langsung
        $status = 'disetujui';
        $namaDudi = $penempatan->tempatMagang->nama_perusahaan ?? '-';
        $alamatDudi = $penempatan->tempatMagang->alamat ?? null;
        $posisi = 'Peserta Magang';
        $tanggalPengajuan = \Carbon\Carbon::parse($penempatan->created_at)->translatedFormat('d F Y');
        $periodeMagang = \Carbon\Carbon::parse($penempatan->tanggal_mulai)->translatedFormat('d M Y') . ' s/d ' . \Carbon\Carbon::parse($penempatan->tanggal_selesai)->translatedFormat('d M Y');
    }

    // ============================================================
    // 2. LOGIKA KONDISI STEP TRACKER & STATUS KARTU
    // ============================================================
    // Step 1: Ajukan
    // Step 2: Ditinjau Sekolah
    // Step 3: Disetujui / Ditolak
    $step1Active = in_array($status, ['menunggu', 'disetujui', 'ditolak']);
    $step2Active = in_array($status, ['menunggu', 'disetujui', 'ditolak']);
    $step2Done   = in_array($status, ['disetujui', 'ditolak']);
    $step3Active = in_array($status, ['disetujui', 'ditolak']);
    $isApproved  = ($status === 'disetujui');
    $isRejected  = ($status === 'ditolak');
    $isPending   = ($status === 'menunggu');

    // ============================================================
    // 3. PEMETAAN LABEL & WARNA BADGE STATUS
    // ============================================================
    $badgeMap = [
        'disetujui' => ['label' => 'Disetujui', 'class' => 'sw-badge-green'],
        'menunggu'  => ['label' => 'Menunggu Validasi Admin', 'class' => 'sw-badge-orange'],
        'ditolak'   => ['label' => 'Ditolak', 'class' => 'sw-badge-red'],
        'belum_mengajukan' => ['label' => 'Belum Mengajukan', 'class' => 'sw-badge-gray'],
    ];
    // Memilih badge yang sesuai dengan status pengajuan saat ini
    $currentBadge = $badgeMap[$status] ?? $badgeMap['belum_mengajukan'];
@endphp

@section('styles')
<style>
    /* ================= VARIABEL WARNA & TEMA CSS ================= */
    :root {
        --sw-primary:      var(--guru-primary, #3B5BFB);    /* Warna tema utama (Biru) */
        --sw-primary-dark: var(--guru-primary-dark, #2540D6);
        --sw-primary-soft: var(--guru-primary-soft, #EAEEFF);/* Warna background lembut */
        --sw-ink:          var(--guru-ink, #111827);        /* Warna teks utama */
        --sw-muted:        var(--guru-muted, #6B7280);      /* Warna teks redup/keterangan */
        --sw-border:       var(--guru-border, #E5E7EB);     /* Warna garis batas */
        --sw-radius-sm:    10px;                            /* Radius sudut kecil */
        --sw-radius-md:    14px;                            /* Radius sudut sedang */
        --sw-radius-lg:    18px;                            /* Radius sudut kartu besar */
        --sw-shadow:       0 1px 3px rgba(16, 24, 40, 0.05); /* Bayangan lembut */
    }

    /* Mengatur kontainer kartu utama */
    .sw-main-card {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-lg);
        padding: 1.85rem 2rem;
        box-shadow: var(--sw-shadow);
        margin-bottom: 1.5rem;
    }

    /* ================= STEP TRACKER (PROGRES PENGAJUAN) ================= */
    /* Container pembungkus tracker dan badge */
    .sw-tracker-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        padding-bottom: 1.75rem;
        margin-bottom: 1.75rem;
        border-bottom: 1px solid var(--sw-border);
        flex-wrap: wrap;
    }

    /* Kontainer barisan step tracker */
    .sw-stepper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
        max-width: 620px;
    }

    /* Item tiap tahapan */
    .sw-step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
        position: relative;
        text-align: center;
        min-width: 80px;
    }

    /* Lingkaran nomor/ikon tahapan */
    .sw-step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        font-weight: 700;
        transition: all 0.25s ease;
        background: #F3F4F6;
        color: var(--sw-muted);
        border: 2px solid transparent;
    }

    /* Warna lingkaran saat aktif */
    .sw-step-circle.active-blue {
        background: var(--sw-primary);
        color: #fff;
        box-shadow: 0 4px 12px rgba(59, 91, 251, 0.35);
    }

    .sw-step-circle.active-green {
        background: #1C9C5B;
        color: #fff;
        box-shadow: 0 4px 12px rgba(28, 156, 91, 0.35);
    }

    .sw-step-circle.active-red {
        background: #DC3545;
        color: #fff;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.35);
    }

    /* Teks label di bawah lingkaran */
    .sw-step-label {
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--sw-muted);
        white-space: nowrap;
    }

    .sw-step-label.active {
        color: var(--sw-ink);
        font-weight: 700;
    }

    /* Garis penghubung antar lingkaran tracker */
    .sw-step-line {
        flex: 1;
        height: 3px;
        background: #E5E7EB;
        border-radius: 999px;
        margin-bottom: 1.35rem;
        transition: background 0.3s ease;
    }

    .sw-step-line.active {
        background: var(--sw-primary);
    }

    .sw-step-line.active-green {
        background: #1C9C5B;
    }

    /* ================= BADGE STATUS ================= */
    .sw-badge {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Titik bulat kecil di dalam badge */
    .sw-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Variasi warna badge status */
    .sw-badge-green  { background: #E7F8EF; color: #1C9C5B; }
    .sw-badge-orange { background: #FFF4E5; color: #D98324; }
    .sw-badge-red    { background: #FDEAEA; color: #DC3545; }
    .sw-badge-gray   { background: #F3F4F6; color: #6B7280; }

    /* ================= KARTU DETAIL (KIRI) ================= */
    .sw-detail-box {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-md);
        padding: 1.5rem;
        height: 100%;
    }

    .sw-detail-title {
        font-size: 1.02rem;
        font-weight: 800;
        color: var(--sw-ink);
        margin-bottom: 0.2rem;
    }

    .sw-detail-subtitle {
        font-size: 0.8rem;
        color: var(--sw-muted);
        margin-bottom: 1.35rem;
    }

    /* Baris item detail info */
    .sw-info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
        padding: 0.9rem 0;
        border-top: 1px solid #F1F3F5;
    }

    .sw-info-item:first-of-type {
        border-top: none;
        padding-top: 0;
    }

    /* Kotak ikon detail info */
    .sw-info-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .sw-info-icon-box.blue   { background: var(--sw-primary-soft); color: var(--sw-primary); }
    .sw-info-icon-box.purple { background: #F3E8FF; color: #7C3AED; }
    .sw-info-icon-box.green  { background: #E7F8EF; color: #1C9C5B; }
    .sw-info-icon-box.gray   { background: #F3F4F6; color: #9CA3AF; }

    .sw-info-label {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: var(--sw-muted);
        margin-bottom: 0.15rem;
    }

    .sw-info-value {
        font-size: 0.94rem;
        font-weight: 700;
        color: var(--sw-ink);
        line-height: 1.35;
    }

    .sw-info-sub {
        font-size: 0.78rem;
        color: var(--sw-muted);
        margin-top: 0.15rem;
        line-height: 1.4;
    }

    /* ================= KOTAK STATUS DINAMIS (KANAN) ================= */
    .sw-status-box {
        border-radius: var(--sw-radius-md);
        padding: 2.2rem 1.75rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: transform 0.2s ease;
    }

    /* Warna border & background berdasarkan status */
    .sw-status-box.approved {
        background: #F8FDF9;
        border: 1px solid #D1FAE5;
    }

    .sw-status-box.pending {
        background: #FFFDF8;
        border: 1px solid #FEF3C7;
    }

    .sw-status-box.rejected {
        background: #FEF8F8;
        border: 1px solid #FEE2E2;
    }

    .sw-status-box.empty {
        background: #F8FAFF;
        border: 1px dashed #D0DBFF;
    }

    /* Lingkaran ikon status besar */
    .sw-status-icon-circle {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1.1rem;
    }

    .sw-status-icon-circle.green  { background: #E7F8EF; color: #1C9C5B; }
    .sw-status-icon-circle.orange { background: #FFF4E5; color: #D98324; }
    .sw-status-icon-circle.red    { background: #FDEAEA; color: #DC3545; }
    .sw-status-icon-circle.blue   { background: var(--sw-primary-soft); color: var(--sw-primary); }

    .sw-status-headline {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--sw-ink);
        margin-bottom: 0.45rem;
    }

    .sw-status-text {
        font-size: 0.86rem;
        color: var(--sw-muted);
        max-width: 360px;
        line-height: 1.55;
        margin-bottom: 1.25rem;
    }

    .sw-btn-action {
        font-size: 0.84rem;
        font-weight: 700;
        padding: 0.6rem 1.25rem;
        border-radius: var(--sw-radius-sm);
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.18s ease;
    }

    /* ================= MODAL & FORM INPUT ================= */
    .simmas-modal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 16px 36px -10px rgba(17, 24, 39, 0.25);
    }

    .simmas-modal .modal-header {
        border-bottom: 1px solid var(--sw-border);
        padding: 1.25rem 1.5rem;
    }

    .simmas-modal .modal-body {
        padding: 1.5rem;
    }

    .simmas-modal .modal-footer {
        border-top: 1px solid var(--sw-border);
        padding: 1rem 1.5rem;
        background: #FAFBFD;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
    }

    .simmas-modal-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-right: 0.85rem;
    }

    .simmas-form-label {
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #374151;
        margin-bottom: 0.45rem;
        display: block;
    }

    .simmas-form-control {
        border: 1px solid var(--sw-border);
        border-radius: 9px;
        padding: 0.65rem 0.85rem;
        font-size: 0.88rem;
        color: var(--sw-ink);
        background: #fff;
        width: 100%;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    /* Efek fokus saat input aktif */
    .simmas-form-control:focus {
        border-color: var(--sw-primary);
        box-shadow: 0 0 0 3px rgba(59, 91, 251, 0.15);
        outline: none;
    }

    /* Pesan error validasi form */
    .simmas-form-error {
        font-size: 0.74rem;
        color: #DC2626;
        margin-top: 0.3rem;
        display: none;
    }

    /* Penyesuaian responsif layar HP / Mobile */
    @media (max-width: 767.98px) {
        .sw-main-card { padding: 1.25rem 1rem; }
        .sw-stepper { width: 100%; justify-content: space-between; }
        .sw-step-line { min-width: 20px; }
    }
</style>
@endsection

@section('content')

{{-- ============================================================ --}}
{{-- KARTU UTAMA: STEP TRACKER & STATUS PENGAJUAN --}}
{{-- ============================================================ --}}
<div class="sw-main-card">

    {{-- ------------------------------------------------------------
         1. STEP PROGRESS BAR & STATUS BADGE REAL-TIME
         ------------------------------------------------------------ --}}
    <div class="sw-tracker-wrap">

        <div class="sw-stepper">
            {{-- Step 1: Tahap Pengajuan --}}
            <div class="sw-step-item">
                <div class="sw-step-circle {{ $step1Active ? 'active-blue' : '' }}">
                    <i class="bi bi-file-earmark-arrow-up-fill"></i>
                </div>
                <div class="sw-step-label {{ $step1Active ? 'active' : '' }}">Ajukan</div>
            </div>

            {{-- Garis penghubung Step 1 ke Step 2 --}}
            <div class="sw-step-line {{ $step2Active ? 'active' : '' }}"></div>

            {{-- Step 2: Tahap Peninjauan oleh Sekolah / Admin --}}
            <div class="sw-step-item">
                <div class="sw-step-circle {{ $step2Active ? ($step2Done ? 'active-blue' : 'active-blue') : '' }}">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <div class="sw-step-label {{ $step2Active ? 'active' : '' }}">Ditinjau Sekolah</div>
            </div>

            {{-- Garis penghubung Step 2 ke Step 3 --}}
            <div class="sw-step-line {{ $isApproved ? 'active-green' : ($isRejected ? 'active' : '') }}"></div>

            {{-- Step 3: Hasil Keputusan (Disetujui / Ditolak) --}}
            <div class="sw-step-item">
                @if($isApproved)
                    {{-- Kondisi jika pengajuan disetujui --}}
                    <div class="sw-step-circle active-green">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="sw-step-label active">Disetujui</div>
                @elseif($isRejected)
                    {{-- Kondisi jika pengajuan ditolak --}}
                    <div class="sw-step-circle active-red">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <div class="sw-step-label active text-danger">Ditolak</div>
                @else
                    {{-- Kondisi default sebelum ada keputusan --}}
                    <div class="sw-step-circle">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="sw-step-label">Disetujui</div>
                @endif
            </div>
        </div>

        {{-- Badge Status di Bagian Kanan Atas --}}
        <div>
            <span class="sw-badge {{ $currentBadge['class'] }}">
                {{ $currentBadge['label'] }}
            </span>
        </div>

    </div>

    {{-- ------------------------------------------------------------
         2. KONTEN DUA KOLOM: DETAIL (KIRI) & STATUS BOX (KANAN)
         ------------------------------------------------------------ --}}
    <div class="row g-4">

        {{-- Kolom Kiri: Detail Data Pengajuan Tempat Magang --}}
        <div class="col-lg-6">
            <div class="sw-detail-box">
                <div class="sw-detail-title">Detail Pengajuan Magang</div>
                <div class="sw-detail-subtitle">Informasi tempat magang yang diajukan.</div>

                {{-- Baris 1: Nama Perusahaan & Alamat --}}
                <div class="sw-info-item">
                    <div class="sw-info-icon-box blue">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="sw-info-label">Tempat Magang (DUDI)</div>
                        <div class="sw-info-value">{{ $namaDudi }}</div>
                        @if($alamatDudi)
                            <div class="sw-info-sub">{{ $alamatDudi }}</div>
                        @endif
                    </div>
                </div>

                {{-- Baris 2: Posisi & Periode Magang --}}
                <div class="sw-info-item">
                    <div class="sw-info-icon-box purple">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div>
                        <div class="sw-info-label">Posisi yang Diajukan</div>
                        <div class="sw-info-value">{{ $posisi }}</div>
                        @if($periodeMagang)
                            <div class="sw-info-sub"><i class="bi bi-clock me-1"></i> Periode: {{ $periodeMagang }}</div>
                        @endif
                    </div>
                </div>

                {{-- Baris 3: Tanggal Pengajuan Dibuat --}}
                <div class="sw-info-item">
                    <div class="sw-info-icon-box green">
                        <i class="bi bi-calendar2-check-fill"></i>
                    </div>
                    <div>
                        <div class="sw-info-label">Tanggal Pengajuan</div>
                        <div class="sw-info-value">{{ $tanggalPengajuan }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Status Box Dinamis Berdasarkan Kondisi Status --}}
        <div class="col-lg-6">
            @if($isApproved)
                {{-- KONDISI 1: PENGAJUAN DISETUJUI / AKTIF --}}
                <div class="sw-status-box approved">
                    <div class="sw-status-icon-circle green">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="sw-status-headline">Pengajuan Magang Aktif</div>
                    <p class="sw-status-text">
                        Selamat! Anda sudah terdaftar dan aktif magang. Silakan isi absensi atau jurnal harian Anda.
                    </p>
                    {{-- Tombol aksi navigasi menuju Absensi & Jurnal --}}
                    <div class="d-flex gap-2 flex-wrap justify-content-center">
                        <a href="{{ url('siswa/absensi-harian') }}" class="btn btn-outline-primary sw-btn-action">
                            <i class="bi bi-calendar-check"></i> Isi Absensi
                        </a>
                        <a href="{{ url('siswa/jurnal-kegiatan') }}" class="btn btn-primary sw-btn-action">
                            <i class="bi bi-journal-text"></i> Tulis Jurnal
                        </a>
                    </div>
                </div>

            @elseif($isPending)
                {{-- KONDISI 2: MENUNGGU VALIDASI ADMIN / SEKOLAH --}}
                <div class="sw-status-box pending">
                    <div class="sw-status-icon-circle orange">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="sw-status-headline">Pengajuan Sedang Ditinjau</div>
                    <p class="sw-status-text">
                        Permohonan magang Anda sedang dalam tahap peninjauan dan validasi oleh pihak sekolah/admin. Harap menunggu konfirmasi.
                    </p>
                    {{-- Tombol untuk memicu modal ubah data --}}
                    <button type="button" class="btn btn-outline-primary sw-btn-action" data-bs-toggle="modal" data-bs-target="#modalFormPengajuan">
                        <i class="bi bi-pencil-square"></i> Ubah Pengajuan
                    </button>
                </div>

            @elseif($isRejected)
                {{-- KONDISI 3: PENGAJUAN DITOLAK BESERTA ALASAN --}}
                <div class="sw-status-box rejected">
                    <div class="sw-status-icon-circle red">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="sw-status-headline text-danger">Pengajuan Ditolak</div>
                    <p class="sw-status-text mb-2">
                        Mohon maaf, pengajuan magang Anda belum dapat disetujui.
                    </p>
                    {{-- Menampilkan catatan penolakan jika ada --}}
                    @if($catatanPenolakan)
                        <div class="alert alert-danger py-2 px-3 small mb-3 text-start w-100" style="max-width: 380px;">
                            <strong>Catatan Penolakan:</strong><br>
                            {{ $catatanPenolakan }}
                        </div>
                    @endif
                    {{-- Tombol untuk mengajukan ulang --}}
                    <button type="button" class="btn btn-primary sw-btn-action" data-bs-toggle="modal" data-bs-target="#modalFormPengajuan">
                        <i class="bi bi-arrow-repeat"></i> Ajukan Ulang Magang
                    </button>
                </div>

            @else
                {{-- KONDISI 4: BELUM PERNAH MENGAJUKAN MAGANG --}}
                <div class="sw-status-box empty">
                    <div class="sw-status-icon-circle blue">
                        <i class="bi bi-send-plus-fill"></i>
                    </div>
                    <div class="sw-status-headline">Form Pengajuan Mandiri</div>
                    <p class="sw-status-text">
                        Fasilitas bagi siswa untuk mengajukan permohonan magang secara mandiri ke perusahaan mitra DUDI sebelum periode magang dimulai.
                    </p>
                    {{-- Tombol membuka modal form pengajuan baru --}}
                    <button type="button" class="btn btn-primary sw-btn-action" data-bs-toggle="modal" data-bs-target="#modalFormPengajuan">
                        <i class="bi bi-plus-lg"></i> Ajukan Tempat Magang
                    </button>
                </div>
            @endif
        </div>

    </div>

</div>

{{-- ============================================================ --}}
{{-- MODAL DIALOG: FORM PENGAJUAN TEMPAT MAGANG --}}
{{-- ============================================================ --}}
<div class="modal fade simmas-modal" id="modalFormPengajuan" tabindex="-1" aria-labelledby="modalFormPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- Form pengiriman data ke route store pengajuan siswa --}}
            <form id="formPengajuanMagang" method="POST" action="{{ route('siswa.pengajuan.store') }}">
                {{-- Token keamanan CSRF Laravel --}}
                @csrf

                {{-- Modal Header --}}
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="simmas-modal-icon">
                            <i class="bi bi-building-add"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0" id="modalFormPengajuanLabel">Form Pengajuan Tempat Magang</h6>
                            <small class="text-muted">Pilih industri mitra yang membuka kuota penerimaan.</small>
                        </div>
                    </div>
                    {{-- Tombol silang untuk menutup modal --}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body: Input Form --}}
                <div class="modal-body">

                    {{-- Kotak notifikasi error umum (jika validasi gagal atau server error) --}}
                    <div class="alert alert-danger py-2 small mb-3 simmas-form-error" id="formGeneralError"></div>

                    {{-- 1. DROPDOWN PILIH PERUSAHAAN MITRA DUDI --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="tempat_magang_id">
                            PILIH PERUSAHAAN MITRA DUDI
                        </label>
                        <select name="tempat_magang_id" id="tempat_magang_id" class="simmas-form-control form-select" required>
                            <option value="" disabled selected>-- Pilih Perusahaan Mitra --</option>
                            {{-- Looping daftar DUDI dari database --}}
                            @forelse ($dudiList as $dudi)
                                @php
                                    $sisa = $dudi->sisa_kuota;
                                    $isPenuh = ($sisa <= 0); // Cek apakah kuota sudah habis
                                @endphp
                                {{-- Jika penuh, opsi dinonaktifkan (disabled) --}}
                                <option value="{{ $dudi->id }}" {{ $isPenuh ? 'disabled' : '' }}>
                                    {{ $dudi->nama_perusahaan }} (Sisa Kuota: {{ $sisa }}){{ $isPenuh ? ' - Penuh' : '' }}
                                </option>
                            @empty
                                {{-- Ditampilkan jika belum ada data mitra DUDI --}}
                                <option value="" disabled>Belum ada mitra DUDI terverifikasi</option>
                            @endforelse
                        </select>
                        {{-- Wadah pesan error validasi DUDI --}}
                        <div class="simmas-form-error" id="error_tempat_magang_id"></div>
                    </div>

                    {{-- 2. INPUT POSISI / DIVISI MAGANG --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="posisi">
                            POSISI / DIVISI YANG DIMINATI
                        </label>
                        <input type="text"
                               name="posisi"
                               id="posisi"
                               class="simmas-form-control"
                               placeholder="Contoh: Web Developer Intern, Network Engineer..."
                               value="{{ old('posisi', ($pengajuanTerakhir ? $pengajuanTerakhir->posisi : '')) }}"
                               required>
                        {{-- Wadah pesan error validasi posisi --}}
                        <div class="simmas-form-error" id="error_posisi"></div>
                    </div>

                    {{-- 3. INPUT TANGGAL MULAI & SELESAI --}}
                    <div class="row g-3">
                        {{-- Input Tanggal Mulai --}}
                        <div class="col-6">
                            <label class="simmas-form-label" for="tanggal_mulai">
                                TANGGAL MULAI
                            </label>
                            <input type="date"
                                   name="tanggal_mulai"
                                   id="tanggal_mulai"
                                   class="simmas-form-control"
                                   value="{{ old('tanggal_mulai', ($pengajuanTerakhir ? $pengajuanTerakhir->tanggal_mulai : date('Y-m-d'))) }}"
                                   required>
                            <div class="simmas-form-error" id="error_tanggal_mulai"></div>
                        </div>

                        {{-- Input Tanggal Selesai (Default +3 bulan dari hari ini) --}}
                        <div class="col-6">
                            <label class="simmas-form-label" for="tanggal_selesai">
                                TANGGAL SELESAI
                            </label>
                            <input type="date"
                                   name="tanggal_selesai"
                                   id="tanggal_selesai"
                                   class="simmas-form-control"
                                   value="{{ old('tanggal_selesai', ($pengajuanTerakhir ? $pengajuanTerakhir->tanggal_selesai : date('Y-m-d', strtotime('+3 months')))) }}"
                                   required>
                            <div class="simmas-form-error" id="error_tanggal_selesai"></div>
                        </div>
                    </div>

                </div>

                {{-- Modal Footer: Tombol Batal & Simpan --}}
                <div class="modal-footer d-flex justify-content-end gap-2">
                    {{-- Tombol Batal --}}
                    <button type="button" class="btn btn-light px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="border: 1px solid var(--sw-border); font-size: 0.84rem;">
                        Batal
                    </button>
                    {{-- Tombol Kirim Form --}}
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" id="btnSubmitPengajuan" style="font-size: 0.84rem; background: var(--sw-primary); border-color: var(--sw-primary);">
                        <i class="bi bi-send me-1"></i> Kirim Pengajuan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Menjalankan script setelah seluruh elemen DOM selesai dimuat
document.addEventListener('DOMContentLoaded', function () {
    // Mengambil elemen form, tombol submit, modal, dan notifikasi error
    const form = document.getElementById('formPengajuanMagang');
    const submitBtn = document.getElementById('btnSubmitPengajuan');
    const modalEl = document.getElementById('modalFormPengajuan');
    const generalError = document.getElementById('formGeneralError');

    if (!form) return;

    // Fungsi untuk mereset dan membersihkan semua pesan error sebelum submit
    function clearErrors() {
        if (generalError) {
            generalError.style.display = 'none';
            generalError.textContent = '';
        }
        document.querySelectorAll('.simmas-form-error').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        document.querySelectorAll('.simmas-form-control').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }

    // Event listener saat form dikirim (submit) secara AJAX / Fetch
    form.addEventListener('submit', async function (e) {
        e.preventDefault(); // Mencegah reload halaman secara default
        clearErrors();      // Reset error sebelumnya

        const formData = new FormData(form);
        const originalBtnHtml = submitBtn.innerHTML;

        // Mengubah status tombol menjadi Loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

        try {
            // Mengirim request POST via Fetch API ke URL route form
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Mengidentifikasi request sebagai AJAX
                    'Accept': 'application/json',         // Meminta response dalam bentuk JSON
                }
            });

            const data = await response.json();

            // Penanganan jika respon error (HTTP 422 Validasi atau error server lainnya)
            if (!response.ok) {
                if (response.status === 422 && data.errors) {
                    // Menampilkan pesan error validasi di bawah masing-masing input field
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(`error_${field}`);
                        const inputEl = document.getElementById(field);
                        if (errorEl) {
                            errorEl.textContent = data.errors[field][0];
                            errorEl.style.display = 'block';
                        }
                        if (inputEl) {
                            inputEl.classList.add('is-invalid');
                        }
                    });
                } else {
                    // Menampilkan error umum di bagian atas modal
                    if (generalError) {
                        generalError.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                        generalError.style.display = 'block';
                    }
                }
                // Mengembalikan tombol submit ke kondisi aktif
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                return;
            }

            // Penanganan jika submit BERHASIL
            // Menampilkan toast notifikasi berhasil
            if (typeof window.showAppToast === 'function') {
                window.showAppToast(data.message || 'Pengajuan magang berhasil dikirim!', 'success');
            }

            // Menutup popup modal form
            const bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) {
                bsModal.hide();
            }

            // Memuat ulang halaman agar data dan status terbaru langsung tampil
            setTimeout(() => {
                window.location.reload();
            }, 600);

        } catch (error) {
            // Penanganan jika terjadi kegagalan koneksi jaringan
            console.error('Error pengajuan:', error);
            if (generalError) {
                generalError.textContent = 'Gagal terhubung ke server. Silakan periksa koneksi Anda.';
                generalError.style.display = 'block';
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    });
});
</script>
@endsection

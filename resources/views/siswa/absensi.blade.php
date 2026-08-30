@extends('layouts.siswa')

@section('title', 'Absensi Harian')
@section('page-title', 'Absensi')

@php
    // ============================================================
    // STATUS KEHADIRAN HARI INI
    // ============================================================
    $sudahMasuk  = $absensiHariIni && !empty($absensiHariIni->jam_masuk);
    $sudahPulang = $absensiHariIni && !empty($absensiHariIni->jam_pulang);
    $statusHariIni = $absensiHariIni ? $absensiHariIni->status : null;
    $isIzinSakit = in_array($statusHariIni, ['sakit', 'izin']);

    // Badge Map Status Kehadiran
    $statusBadgeMap = [
        'hadir' => ['label' => 'Hadir', 'class' => 'sw-badge-green'],
        'sakit' => ['label' => 'Sakit', 'class' => 'sw-badge-orange'],
        'izin'  => ['label' => 'Izin',  'class' => 'sw-badge-purple'],
        'alfa'  => ['label' => 'Alfa',  'class' => 'sw-badge-red'],
    ];

    // Badge Map Status Validasi Guru
    $validasiBadgeMap = [
        'disetujui' => ['label' => 'Disetujui', 'class' => 'sw-badge-green'],
        'pending'   => ['label' => 'Pending',   'class' => 'sw-badge-orange'],
        'ditolak'   => ['label' => 'Ditolak',   'class' => 'sw-badge-red'],
    ];
@endphp

@section('styles')
<style>
    :root {
        --sw-primary:      var(--guru-primary, #3B5BFB);
        --sw-primary-dark: var(--guru-primary-dark, #2540D6);
        --sw-primary-soft: var(--guru-primary-soft, #EAEEFF);
        --sw-ink:          var(--guru-ink, #111827);
        --sw-muted:        var(--guru-muted, #6B7280);
        --sw-border:       var(--guru-border, #E5E7EB);
        --sw-radius-sm:    10px;
        --sw-radius-md:    14px;
        --sw-radius-lg:    18px;
        --sw-shadow:       0 1px 3px rgba(16, 24, 40, 0.05);
    }

    /* ================= BANNER / CARD STATUS HARI INI ================= */
    .sw-today-card {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-lg);
        padding: 1.35rem 1.75rem;
        box-shadow: var(--sw-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }

    .sw-today-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .sw-today-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .sw-today-title {
        font-size: 0.98rem;
        font-weight: 800;
        color: var(--sw-ink);
        margin-bottom: 0.2rem;
    }

    .sw-today-sub {
        font-size: 0.82rem;
        color: var(--sw-muted);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .sw-today-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .sw-btn-clock-in {
        background: var(--sw-primary);
        color: #fff;
        font-size: 0.85rem;
        font-weight: 700;
        border: none;
        border-radius: var(--sw-radius-sm);
        padding: 0.62rem 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 4px 14px -3px rgba(59, 91, 251, 0.4);
        transition: all 0.2s ease;
    }

    .sw-btn-clock-in:hover:not(:disabled) {
        background: var(--sw-primary-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .sw-btn-clock-in:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        box-shadow: none;
    }

    .sw-btn-clock-out {
        background: #FFF4E5;
        color: #D98324;
        border: 1px solid #FCD34D;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: var(--sw-radius-sm);
        padding: 0.62rem 1.25rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.2s ease;
    }

    .sw-btn-clock-out:hover:not(:disabled) {
        background: #FEEBC8;
        color: #B76E1A;
        transform: translateY(-1px);
    }

    .sw-btn-clock-out:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    /* ================= STAT RINGKAS BULANAN ================= */
    .sw-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .sw-stat-box {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-md);
        padding: 1.1rem 1.25rem;
        box-shadow: var(--sw-shadow);
    }

    .sw-stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--sw-muted);
        margin-bottom: 0.35rem;
    }

    .sw-stat-val {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--sw-ink);
        line-height: 1.1;
    }

    /* ================= TABEL RIWAYAT ================= */
    .sw-table-card {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-lg);
        padding: 1.5rem 1.75rem;
        box-shadow: var(--sw-shadow);
    }

    .sw-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.35rem;
        flex-wrap: wrap;
    }

    .sw-table-title {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--sw-muted);
        margin: 0;
    }

    .sw-table-filter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sw-table-filter select {
        padding: 0.42rem 0.85rem;
        border: 1px solid var(--sw-border);
        border-radius: 9px;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--sw-ink);
        background: #fff;
    }

    .sw-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sw-table th {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--sw-muted);
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--sw-border);
        text-align: left;
        white-space: nowrap;
        background: #FAFBFD;
    }

    .sw-table th:first-child { border-top-left-radius: 10px; }
    .sw-table th:last-child { border-top-right-radius: 10px; }

    .sw-table td {
        padding: 1rem;
        font-size: 0.86rem;
        color: var(--sw-ink);
        vertical-align: middle;
        border-bottom: 1px solid #F1F3F5;
    }

    .sw-table tr:last-child td {
        border-bottom: none;
    }

    .sw-table tr:hover td {
        background: #FAFBFD;
    }

    /* ================= BADGES ================= */
    .sw-badge {
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.28rem 0.75rem;
        border-radius: 999px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
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
    .sw-badge-purple { background: #F3E8FF; color: #7C3AED; }
    .sw-badge-red    { background: #FDEAEA; color: #DC3545; }
    .sw-badge-gray   { background: #F3F4F6; color: #6B7280; }

    /* ================= TOMBOL FOTO PILL ================= */
    .sw-photo-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.32rem 0.75rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 600;
        border: 1px solid #D0DBFF;
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .sw-photo-btn:hover {
        background: var(--sw-primary);
        color: #fff;
        border-color: var(--sw-primary);
    }

    .sw-photo-btn.outline {
        background: #fff;
        border-color: #E5E7EB;
        color: var(--sw-muted);
    }

    .sw-photo-btn.outline:hover {
        background: #F3F4F6;
        color: var(--sw-ink);
        border-color: #D1D5DB;
    }

    /* ================= MODAL STYLES ================= */
    .simmas-modal .modal-content {
        border-radius: 18px;
        border: none;
        box-shadow: 0 16px 40px -10px rgba(17, 24, 39, 0.25);
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
        border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px;
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
        font-size: 1.15rem;
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
        border-radius: 10px;
        padding: 0.65rem 0.85rem;
        font-size: 0.88rem;
        color: var(--sw-ink);
        background: #fff;
        width: 100%;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .simmas-form-control:focus {
        border-color: var(--sw-primary);
        box-shadow: 0 0 0 3px rgba(59, 91, 251, 0.15);
        outline: none;
    }

    /* ================= INPUT JAM REALTIME (READONLY / TIDAK BISA DIPILIH) ================= */
    .simmas-form-control.sw-jam-realtime {
        background: #F3F4F6;
        color: var(--sw-ink);
        font-weight: 700;
        letter-spacing: 0.03em;
        cursor: not-allowed;
        pointer-events: none; /* mencegah klik/pilih manual, termasuk di mobile */
        user-select: none;
    }

    .sw-jam-hint {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        color: var(--sw-muted);
        margin-top: 0.35rem;
    }

    /* Status Segmented Radio Buttons */
    .sw-status-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.6rem;
        margin-bottom: 1.15rem;
    }

    .sw-status-radio {
        display: none;
    }

    .sw-status-btn {
        border: 1px solid var(--sw-border);
        border-radius: 10px;
        padding: 0.65rem 0.5rem;
        text-align: center;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--sw-muted);
        background: #fff;
        cursor: pointer;
        transition: all 0.18s ease;
        display: block;
    }

    .sw-status-btn:hover {
        background: #FAFBFD;
        border-color: #CBD5E1;
    }

    .sw-status-radio:checked + .sw-status-btn {
        border-color: var(--sw-primary);
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        font-weight: 700;
        box-shadow: 0 0 0 1px var(--sw-primary);
    }

    /* Tab Switcher Kamera vs Upload */
    .sw-photo-tabs {
        display: flex;
        background: #F1F5F9;
        border-radius: 10px;
        padding: 3px;
        margin-bottom: 0.85rem;
    }

    .sw-photo-tab-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 0.45rem 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--sw-muted);
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .sw-photo-tab-btn.active {
        background: #fff;
        color: var(--sw-primary);
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    /* Live Webcam Box */
    .sw-webcam-box {
        position: relative;
        background: #0F172A;
        border-radius: 12px;
        overflow: hidden;
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .sw-webcam-video {
        width: 100%;
        max-height: 240px;
        object-fit: cover;
        transform: scaleX(-1); /* Mirror selfie */
        border-radius: 12px;
        display: none;
    }

    .sw-webcam-placeholder {
        padding: 1.5rem 1rem;
        color: #94A3B8;
    }

    .sw-webcam-actions {
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .sw-btn-snap {
        background: var(--sw-primary);
        color: #fff;
        border: none;
        border-radius: 999px;
        padding: 0.5rem 1.25rem;
        font-size: 0.84rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 4px 12px rgba(59, 91, 251, 0.4);
        transition: all 0.18s ease;
    }

    .sw-btn-snap:hover {
        background: var(--sw-primary-dark);
        color: #fff;
        transform: scale(1.02);
    }

    /* Foto Upload Dropzone */
    .sw-upload-zone {
        border: 2px dashed #CBD5E1;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        text-align: center;
        background: #F8FAFC;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .sw-upload-zone:hover {
        border-color: var(--sw-primary);
        background: #F0F4FF;
    }

    .sw-upload-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #fff;
        color: var(--sw-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 0.6rem;
    }

    .sw-upload-text {
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--sw-ink);
        margin-bottom: 0.2rem;
    }

    .sw-upload-hint {
        font-size: 0.74rem;
        color: var(--sw-muted);
    }

    .sw-preview-wrap {
        display: none;
        position: relative;
        text-align: center;
        margin-top: 0.5rem;
    }

    .sw-preview-img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--sw-border);
    }

    .sw-remove-preview {
        position: absolute;
        top: 8px;
        right: calc(50% - 90px);
        background: #DC3545;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    }

    .simmas-form-error {
        font-size: 0.74rem;
        color: #DC2626;
        margin-top: 0.3rem;
        display: none;
    }

    @media (max-width: 767.98px) {
        .sw-stat-grid { grid-template-columns: repeat(2, 1fr); }
        .sw-today-card { padding: 1.15rem 1rem; }
        .sw-table-card { padding: 1.15rem 1rem; }
    }
</style>
@endsection

@section('content')

{{-- ============================================================ --}}
{{-- 1. CARD STATUS KEHADIRAN HARI INI & ACTION CLOCK IN / OUT --}}
{{-- ============================================================ --}}
<div class="sw-today-card">

    <div class="sw-today-info">
        <div class="sw-today-icon">
            <i class="bi bi-calendar-check-fill"></i>
        </div>
        <div>
            <div class="sw-today-title">Status Kehadiran Hari Ini</div>
            <div class="sw-today-sub">
                @if(!$absensiHariIni)
                    <span class="text-muted">Belum Absen Masuk</span>
                @elseif($isIzinSakit)
                    <span class="sw-badge {{ $statusBadgeMap[$statusHariIni]['class'] }}">
                        {{ $statusBadgeMap[$statusHariIni]['label'] }}
                    </span>
                    <span class="text-muted">&bull; Jam: {{ substr($absensiHariIni->jam_masuk, 0, 5) }}</span>
                    @if($absensiHariIni->status_validasi)
                        <span class="sw-badge {{ $validasiBadgeMap[$absensiHariIni->status_validasi]['class'] }}">
                            {{ $validasiBadgeMap[$absensiHariIni->status_validasi]['label'] }} Guru
                        </span>
                    @endif
                @else
                    <span class="text-success fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Masuk: {{ substr($absensiHariIni->jam_masuk, 0, 5) }}
                    </span>
                    @if($sudahPulang)
                        <span class="text-muted">&bull;</span>
                        <span class="text-primary fw-bold">
                            Pulang: {{ substr($absensiHariIni->jam_pulang, 0, 5) }}
                        </span>
                    @else
                        <span class="text-muted">&bull; Belum Absen Pulang</span>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="sw-today-actions">
        {{-- Tombol Clock In --}}
        <button type="button"
                class="sw-btn-clock-in"
                onclick="bukaModalPresensi('masuk')"
                {{ ($sudahMasuk || $isIzinSakit) ? 'disabled title="Anda sudah melakukan presensi masuk hari ini"' : '' }}>
            <i class="bi bi-box-arrow-in-right"></i> Clock In
        </button>

        {{-- Tombol Clock Out --}}
        <button type="button"
                class="sw-btn-clock-out"
                onclick="bukaModalPresensi('pulang')"
                {{ (!$sudahMasuk || $sudahPulang || $isIzinSakit) ? 'disabled title="Clock Out hanya dapat dilakukan setelah Clock In"' : '' }}>
            <i class="bi bi-box-arrow-right"></i> Clock Out
        </button>
    </div>

</div>

{{-- ============================================================ --}}
{{-- 2. STAT RINGKAS BULANAN --}}
{{-- ============================================================ --}}
<div class="sw-stat-grid">
    <div class="sw-stat-box">
        <div class="sw-stat-label">Hadir</div>
        <div class="sw-stat-val text-success">{{ $totalHadir }} <span style="font-size: 0.8rem; font-weight: 500; color: var(--sw-muted);">hari</span></div>
    </div>
    <div class="sw-stat-box">
        <div class="sw-stat-label">Sakit</div>
        <div class="sw-stat-val text-warning">{{ $totalSakit }} <span style="font-size: 0.8rem; font-weight: 500; color: var(--sw-muted);">hari</span></div>
    </div>
    <div class="sw-stat-box">
        <div class="sw-stat-label">Izin</div>
        <div class="sw-stat-val" style="color: #7C3AED;">{{ $totalIzin }} <span style="font-size: 0.8rem; font-weight: 500; color: var(--sw-muted);">hari</span></div>
    </div>
    <div class="sw-stat-box">
        <div class="sw-stat-label">Alfa</div>
        <div class="sw-stat-val text-danger">{{ $totalAlfa }} <span style="font-size: 0.8rem; font-weight: 500; color: var(--sw-muted);">hari</span></div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- 3. TABEL RIWAYAT ABSENSI --}}
{{-- ============================================================ --}}
<div class="sw-table-card">

    <div class="sw-table-header">
        <h6 class="sw-table-title">RIWAYAT BULAN INI</h6>

        {{-- Filter Bulan --}}
        <form method="GET" action="{{ route('siswa.absensi.index') }}" class="sw-table-filter">
            <input type="month"
                   name="bulan"
                   class="form-control form-control-sm"
                   value="{{ $bulanDipilih }}"
                   onchange="this.form.submit()"
                   style="border-radius: 8px; font-size: 0.82rem; font-weight: 600;">
        </form>
    </div>

    <div class="table-responsive">
        <table class="sw-table">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>STATUS</th>
                    <th>MASUK</th>
                    <th>PULANG</th>
                    <th>FOTO</th>
                    <th>VALIDASI GURU</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatAbsensi as $item)
                    @php
                        $badgeStatus = $statusBadgeMap[$item->status] ?? ['label' => ucfirst($item->status), 'class' => 'sw-badge-gray'];
                        $badgeValidasi = $validasiBadgeMap[$item->status_validasi] ?? ['label' => ucfirst($item->status_validasi), 'class' => 'sw-badge-gray'];
                        $tglFormat = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M');
                        $tglFull   = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y');
                    @endphp
                    <tr>
                        <td class="fw-semibold">{{ $tglFormat }}</td>

                        <td>
                            <span class="sw-badge {{ $badgeStatus['class'] }}">
                                {{ $badgeStatus['label'] }}
                            </span>
                        </td>

                        <td>{{ $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-' }}</td>

                        <td>{{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) : '-' }}</td>

                        <td>
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                @if($item->photo_masuk_url)
                                    <button type="button"
                                            class="sw-photo-btn"
                                            onclick="lihatFoto('{{ asset('storage/' . $item->photo_masuk_url) }}', 'Foto Masuk - {{ $tglFull }}')">
                                        <i class="bi bi-camera"></i> Foto Masuk
                                    </button>
                                @endif

                                @if($item->photo_pulang_url)
                                    <button type="button"
                                            class="sw-photo-btn outline"
                                            onclick="lihatFoto('{{ asset('storage/' . $item->photo_pulang_url) }}', 'Foto Pulang - {{ $tglFull }}')">
                                        <i class="bi bi-camera"></i> Foto Pulang
                                    </button>
                                @endif

                                @if(!$item->photo_masuk_url && !$item->photo_pulang_url)
                                    <span class="text-muted small">-</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <span class="sw-badge {{ $badgeValidasi['class'] }}">
                                {{ $badgeValidasi['label'] }}
                            </span>
                            @if($item->catatan_guru)
                                <div class="text-muted small mt-1" style="font-size: 0.72rem;">
                                    <i class="bi bi-chat-quote me-1"></i>{{ $item->catatan_guru }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x d-block mb-2" style="font-size: 2rem; color: #CBD5E1;"></i>
                            Belum ada catatan presensi untuk bulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- ============================================================ --}}
{{-- 4. MODAL DIALOG: FORM PRESENSI HARIAN SISWA (IMAGE 2) --}}
{{-- ============================================================ --}}
<div class="modal fade simmas-modal" id="modalFormPresensi" tabindex="-1" aria-labelledby="modalFormPresensiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="formPresensiHarian" method="POST" action="{{ route('siswa.absensi.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tipe" id="presensiTipe" value="masuk">

                {{-- Modal Header --}}
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="simmas-modal-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0" id="modalFormPresensiLabel">Form Presensi Harian</h6>
                            <small class="text-muted" id="modalSubtitle">Catat kehadiran dan lampirkan bukti foto presensi.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body">

                    {{-- General Alert Error --}}
                    <div class="alert alert-danger py-2 small mb-3 simmas-form-error" id="formGeneralError"></div>

                    {{-- 1. STATUS KEHADIRAN (Segmented Radio) --}}
                    <div id="sectionStatusKehadiran" class="mb-3">
                        <label class="simmas-form-label">STATUS KEHADIRAN</label>
                        <div class="sw-status-selector">
                            <div>
                                <input type="radio" name="status" id="status_hadir" value="hadir" class="sw-status-radio" checked onchange="toggleStatusMode('hadir')">
                                <label for="status_hadir" class="sw-status-btn">Hadir</label>
                            </div>
                            <div>
                                <input type="radio" name="status" id="status_sakit" value="sakit" class="sw-status-radio" onchange="toggleStatusMode('sakit')">
                                <label for="status_sakit" class="sw-status-btn">Sakit</label>
                            </div>
                            <div>
                                <input type="radio" name="status" id="status_izin" value="izin" class="sw-status-radio" onchange="toggleStatusMode('izin')">
                                <label for="status_izin" class="sw-status-btn">Izin</label>
                            </div>
                        </div>
                        <div class="simmas-form-error" id="error_status"></div>
                    </div>

                    {{-- 2. JAM PRESENSI (REALTIME, TIDAK BISA DIPILIH) & TANGGAL --}}
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="simmas-form-label" for="jam">JAM PRESENSI (REALTIME)</label>
                            {{--
                                Input jam dibuat readonly + pointer-events:none supaya
                                user TIDAK BISA memilih/mengetik jam secara manual.
                                Nilainya di-update otomatis tiap detik lewat JS (mulaiJamRealtime())
                                mengikuti jam asli perangkat saat modal dibuka.
                            --}}
                            <input type="text"
                                   name="jam"
                                   id="jam"
                                   class="simmas-form-control sw-jam-realtime"
                                   value="{{ date('H:i:s') }}"
                                   readonly
                                   tabindex="-1"
                                   inputmode="none"
                                   autocomplete="off"
                                   required>
                            <div class="sw-jam-hint">
                                <i class="bi bi-lock-fill"></i>
                                Jam otomatis mengikuti waktu perangkat, tidak dapat diubah manual.
                            </div>
                            <div class="simmas-form-error" id="error_jam"></div>
                        </div>

                        <div class="col-6">
                            <label class="simmas-form-label" for="tanggal">TANGGAL</label>
                            <input type="date"
                                   name="tanggal"
                                   id="tanggal"
                                   class="simmas-form-control"
                                   value="{{ date('Y-m-d') }}"
                                   readonly
                                   required>
                            <div class="simmas-form-error" id="error_tanggal"></div>
                        </div>
                    </div>

                    {{-- 3. FOTO BUKTI PRESENSI (KAMERA / UPLOAD) --}}
                    <div class="mb-2">
                        <label class="simmas-form-label" id="labelFotoBukti">
                            FOTO BUKTI PRESENSI (SELFIE LOKASI)
                        </label>

                        {{-- Tab Pilihan: Kamera Langsung vs Upload File --}}
                        <div class="sw-photo-tabs" id="photoTabsWrap">
                            <button type="button" class="sw-photo-tab-btn active" id="tabBtnCamera" onclick="switchPhotoMode('camera')">
                                <i class="bi bi-camera-video-fill"></i> Kamera Langsung
                            </button>
                            <button type="button" class="sw-photo-tab-btn" id="tabBtnUpload" onclick="switchPhotoMode('upload')">
                                <i class="bi bi-cloud-arrow-up-fill"></i> Upload File / Galeri
                            </button>
                        </div>

                        {{-- Mode 1: Live Webcam Box --}}
                        <div id="cameraBox" class="mb-2">
                            <div class="sw-webcam-box" id="webcamContainer">
                                <video id="webcamVideo" class="sw-webcam-video" autoplay playsinline muted></video>
                                <canvas id="webcamCanvas" style="display: none;"></canvas>

                                <div class="sw-webcam-placeholder" id="webcamPlaceholder">
                                    <i class="bi bi-camera-video fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="small text-white mb-2">Akses kamera aktif untuk mengambil foto selfie presensi.</p>
                                    <button type="button" class="btn btn-sm btn-light fw-bold" onclick="mulaiKamera()">
                                        <i class="bi bi-play-circle me-1"></i> Buka Kamera
                                    </button>
                                </div>
                            </div>

                            <div class="sw-webcam-actions" id="webcamActions" style="display: none;">
                                <button type="button" class="sw-btn-snap" id="btnSnapPhoto" onclick="ambilFotoKamera()">
                                    <i class="bi bi-camera-fill"></i> Ambil Foto
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="hentikanKamera()">
                                    <i class="bi bi-stop-circle"></i> Tutup Kamera
                                </button>
                            </div>
                        </div>

                        {{-- Mode 2: Dropzone File Upload --}}
                        <div id="uploadBox" style="display: none;">
                            <div class="sw-upload-zone" id="uploadDropzone" onclick="document.getElementById('photoInput').click()">
                                <div class="sw-upload-icon">
                                    <i class="bi bi-cloud-arrow-up"></i>
                                </div>
                                <div class="sw-upload-text" id="uploadText">Klik untuk ambil foto atau upload file</div>
                                <div class="sw-upload-hint">Foto selfie lokasi magang / surat keterangan dokter (Maks. 4MB)</div>
                                <input type="file"
                                       name="photo"
                                       id="photoInput"
                                       accept="image/*"
                                       capture="user"
                                       style="display: none;"
                                       onchange="previewImage(this)">
                            </div>
                        </div>

                        {{-- Preview Hasil Foto (Berlaku untuk Kamera & Upload) --}}
                        <div class="sw-preview-wrap" id="previewWrap">
                            <img src="" alt="Preview Foto" class="sw-preview-img" id="previewImg">
                            <button type="button" class="sw-remove-preview" onclick="hapusPreview()" title="Hapus Foto">
                                <i class="bi bi-x"></i>
                            </button>
                            <div class="small text-success fw-bold mt-2">
                                <i class="bi bi-check-circle-fill me-1"></i> Foto berhasil siap dilampirkan
                            </div>
                        </div>

                        <div class="simmas-form-error" id="error_photo"></div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="border: 1px solid var(--sw-border); font-size: 0.84rem;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" id="btnSubmitPresensi" style="font-size: 0.84rem; background: var(--sw-primary); border-color: var(--sw-primary);">
                        <i class="bi bi-send me-1"></i> Kirim Presensi
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- 5. MODAL PREVIEW FOTO PRESENSI --}}
{{-- ============================================================ --}}
<div class="modal fade simmas-modal" id="modalLihatFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark mb-0" id="lihatFotoTitle">Foto Presensi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="lihatFotoImg" alt="Foto Presensi" class="img-fluid rounded shadow-sm" style="max-height: 480px; width: auto; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let webcamStream = null;
let currentPhotoMode = 'camera'; // 'camera' | 'upload'
let jamRealtimeInterval = null;  // handle setInterval jam realtime, supaya bisa dimatikan

// ============================================================
// JAM PRESENSI REALTIME (TIDAK BISA DIPILIH MANUAL)
// ============================================================
// Field #jam dibuat readonly + pointer-events:none di HTML,
// sehingga user hanya bisa "melihat" jam berjalan, tidak bisa mengubahnya.
// Fungsi berikut yang bertanggung jawab mengisi nilainya secara otomatis.
function mulaiJamRealtime() {
    hentikanJamRealtime(); // pastikan tidak ada interval lama yang dobel jalan

    const jamInput = document.getElementById('jam');
    if (!jamInput) return;

    const updateJamSekarang = () => {
        const now = new Date();
        const jamStr = String(now.getHours()).padStart(2, '0') + ':' +
                        String(now.getMinutes()).padStart(2, '0') + ':' +
                        String(now.getSeconds()).padStart(2, '0');
        jamInput.value = jamStr;
    };

    updateJamSekarang();                                   // langsung isi begitu modal dibuka
    jamRealtimeInterval = setInterval(updateJamSekarang, 1000); // lalu update tiap 1 detik
}

function hentikanJamRealtime() {
    if (jamRealtimeInterval) {
        clearInterval(jamRealtimeInterval);
        jamRealtimeInterval = null;
    }
}

// ============================================================
// MODAL HELPER: BUKA FORM PRESENSI (MASUK / PULANG)
// ============================================================
function bukaModalPresensi(tipe = 'masuk') {
    const modalEl = document.getElementById('modalFormPresensi');
    const modalTitle = document.getElementById('modalFormPresensiLabel');
    const modalSub = document.getElementById('modalSubtitle');
    const tipeInput = document.getElementById('presensiTipe');
    const statusSection = document.getElementById('sectionStatusKehadiran');
    const labelFoto = document.getElementById('labelFotoBukti');
    const tglInput = document.getElementById('tanggal');

    // Reset error, preview & camera
    clearPresensiErrors();
    hapusPreview();
    hentikanKamera();

    // Tanggal tetap mengikuti hari ini (readonly), jam diserahkan ke mulaiJamRealtime()
    const now = new Date();
    const tglStr = now.toISOString().split('T')[0];

    tglInput.value = tglStr;
    tipeInput.value = tipe;

    if (tipe === 'pulang') {
        modalTitle.textContent = 'Form Absen Pulang (Clock Out)';
        modalSub.textContent = 'Catat jam kepulangan dan sertakan foto bukti kepulangan.';
        statusSection.style.display = 'none';
        labelFoto.textContent = 'FOTO BUKTI PRESENSI PULANG (SELFIE LOKASI)';
        document.getElementById('status_hadir').checked = true;
    } else {
        modalTitle.textContent = 'Form Presensi Harian';
        modalSub.textContent = 'Catat kehadiran dan lampirkan bukti foto presensi.';
        statusSection.style.display = 'block';
        labelFoto.textContent = 'FOTO BUKTI PRESENSI (SELFIE LOKASI)';
    }

    switchPhotoMode('camera');

    // Mulai jam realtime: jam presensi selalu jam asli saat form dibuka/disubmit,
    // tidak bisa dipilih atau diketik manual oleh siswa.
    mulaiJamRealtime();

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();

    // Otomatis aktifkan kamera saat modal terbuka jika mode kamera
    setTimeout(() => {
        mulaiKamera();
    }, 400);
}

// ============================================================
// SWITCH PHOTO MODE (KAMERA VS UPLOAD)
// ============================================================
function switchPhotoMode(mode) {
    currentPhotoMode = mode;
    const tabCam = document.getElementById('tabBtnCamera');
    const tabUp = document.getElementById('tabBtnUpload');
    const boxCam = document.getElementById('cameraBox');
    const boxUp = document.getElementById('uploadBox');

    if (mode === 'camera') {
        tabCam.classList.add('active');
        tabUp.classList.remove('active');
        boxCam.style.display = 'block';
        boxUp.style.display = 'none';
        mulaiKamera();
    } else {
        tabUp.classList.add('active');
        tabCam.classList.remove('active');
        boxUp.style.display = 'block';
        boxCam.style.display = 'none';
        hentikanKamera();
    }
}

// Toggle label jika Sakit/Izin
function toggleStatusMode(status) {
    const labelFoto = document.getElementById('labelFotoBukti');
    if (status === 'sakit') {
        labelFoto.textContent = 'FOTO SURAT KETERANGAN DOKTER / BUKTI';
        switchPhotoMode('upload'); // Sakit/Izin default ke upload surat
    } else if (status === 'izin') {
        labelFoto.textContent = 'FOTO SURAT IZIN ORANG TUA / BUKTI';
        switchPhotoMode('upload');
    } else {
        labelFoto.textContent = 'FOTO BUKTI PRESENSI (SELFIE LOKASI)';
    }
}

// ============================================================
// WEBCAM ACCESS (HTML5 getUserMedia)
// ============================================================
async function mulaiKamera() {
    const video = document.getElementById('webcamVideo');
    const placeholder = document.getElementById('webcamPlaceholder');
    const actions = document.getElementById('webcamActions');
    const previewWrap = document.getElementById('previewWrap');

    // Jika sudah ada preview, jangan buka video dulu kecuali di-reset
    if (previewWrap.style.display === 'block') {
        return;
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        console.warn('Akses kamera tidak didukung di browser ini.');
        if (placeholder) {
            placeholder.innerHTML = `
                <i class="bi bi-exclamation-triangle text-warning fs-1 d-block mb-2"></i>
                <p class="small text-white mb-2">Kamera tidak didukung / akses diblokir.</p>
                <button type="button" class="btn btn-sm btn-light fw-bold" onclick="switchPhotoMode('upload')">
                    Gunakan Upload File
                </button>
            `;
        }
        return;
    }

    try {
        hentikanKamera(); // Bersihkan stream sebelumnya jika ada

        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'user',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        });

        webcamStream = stream;
        video.srcObject = stream;
        video.style.display = 'block';
        placeholder.style.display = 'none';
        actions.style.display = 'flex';

    } catch (err) {
        console.error('Gagal mengakses kamera:', err);
        if (placeholder) {
            placeholder.innerHTML = `
                <i class="bi bi-camera-video-off text-danger fs-1 d-block mb-2"></i>
                <p class="small text-white mb-2">Izin akses kamera ditolak atau kamera tidak ditemukan.</p>
                <button type="button" class="btn btn-sm btn-light fw-bold" onclick="switchPhotoMode('upload')">
                    Pilih File dari Perangkat
                </button>
            `;
            placeholder.style.display = 'block';
            video.style.display = 'none';
            actions.style.display = 'none';
        }
    }
}

function hentikanKamera() {
    if (webcamStream) {
        webcamStream.getTracks().forEach(track => track.stop());
        webcamStream = null;
    }
    const video = document.getElementById('webcamVideo');
    const placeholder = document.getElementById('webcamPlaceholder');
    const actions = document.getElementById('webcamActions');

    if (video) {
        video.pause();
        video.srcObject = null;
        video.style.display = 'none';
    }
    if (placeholder && document.getElementById('previewWrap').style.display !== 'block') {
        placeholder.style.display = 'block';
    }
    if (actions) {
        actions.style.display = 'none';
    }
}

// ============================================================
// AMBIL FOTO DARI KAMERA (SNAP)
// ============================================================
function ambilFotoKamera() {
    const video = document.getElementById('webcamVideo');
    const canvas = document.getElementById('webcamCanvas');
    const previewWrap = document.getElementById('previewWrap');
    const previewImg = document.getElementById('previewImg');
    const cameraBox = document.getElementById('cameraBox');

    if (!video || !webcamStream) return;

    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;

    const ctx = canvas.getContext('2d');

    // Karena video dimirror untuk preview selfie, kita mirror canvas agar hasil foto natural
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert ke Blob dan set ke input file
    canvas.toBlob(function (blob) {
        if (!blob) return;

        const file = new File([blob], `selfie_${Date.now()}.jpg`, { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('photoInput').files = dataTransfer.files;

        // Tampilkan preview
        previewImg.src = canvas.toDataURL('image/jpeg', 0.9);
        previewWrap.style.display = 'block';
        cameraBox.style.display = 'none';

        // Matikan kamera setelah foto berhasil diambil
        hentikanKamera();
    }, 'image/jpeg', 0.9);
}

// ============================================================
// PREVIEW IMAGE & REMOVE PREVIEW
// ============================================================
function previewImage(input) {
    const previewWrap = document.getElementById('previewWrap');
    const previewImg = document.getElementById('previewImg');
    const uploadBox = document.getElementById('uploadBox');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewWrap.style.display = 'block';
            uploadBox.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function hapusPreview() {
    const input = document.getElementById('photoInput');
    const previewWrap = document.getElementById('previewWrap');
    const previewImg = document.getElementById('previewImg');
    const cameraBox = document.getElementById('cameraBox');
    const uploadBox = document.getElementById('uploadBox');

    if (input) input.value = '';
    if (previewImg) previewImg.src = '';
    if (previewWrap) previewWrap.style.display = 'none';

    if (currentPhotoMode === 'camera') {
        cameraBox.style.display = 'block';
        uploadBox.style.display = 'none';
        mulaiKamera();
    } else {
        uploadBox.style.display = 'block';
        cameraBox.style.display = 'none';
    }
}

// ============================================================
// LIHAT FOTO PRESENSI DI MODAL
// ============================================================
function lihatFoto(url, title) {
    document.getElementById('lihatFotoImg').src = url;
    document.getElementById('lihatFotoTitle').textContent = title || 'Foto Presensi';
    const modal = new bootstrap.Modal(document.getElementById('modalLihatFoto'));
    modal.show();
}

function clearPresensiErrors() {
    const generalError = document.getElementById('formGeneralError');
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

// ============================================================
// EVENT LISTENERS: SHUTDOWN CAMERA & JAM REALTIME SAAT MODAL CLOSE, SUBMIT AJAX
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formPresensiHarian');
    const submitBtn = document.getElementById('btnSubmitPresensi');
    const modalEl = document.getElementById('modalFormPresensi');
    const generalError = document.getElementById('formGeneralError');

    // Pastikan kamera & jam realtime dimatikan saat modal ditutup
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            hentikanKamera();
            hentikanJamRealtime();
        });
    }

    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearPresensiErrors();

        const photoInput = document.getElementById('photoInput');
        if (!photoInput.files || photoInput.files.length === 0) {
            const errorPhoto = document.getElementById('error_photo');
            if (errorPhoto) {
                errorPhoto.textContent = 'Foto bukti presensi (selfie / surat) wajib dilampirkan.';
                errorPhoto.style.display = 'block';
            }
            return;
        }

        // Jam presensi diambil ulang tepat saat submit, tetap dari waktu asli perangkat
        // (bukan dari nilai lama), sehingga jam yang tercatat = jam saat tombol ditekan.
        const jamInput = document.getElementById('jam');
        if (jamInput) {
            const now = new Date();
            jamInput.value = String(now.getHours()).padStart(2, '0') + ':' +
                              String(now.getMinutes()).padStart(2, '0') + ':' +
                              String(now.getSeconds()).padStart(2, '0');
        }

        const formData = new FormData(form);
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (!response.ok) {
                if (response.status === 422 && data.errors) {
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
                    if (generalError) {
                        generalError.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                        generalError.style.display = 'block';
                    }
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                return;
            }

            // Berhasil
            if (typeof window.showAppToast === 'function') {
                window.showAppToast(data.message || 'Presensi berhasil dicatat!', 'success');
            }

            hentikanKamera();
            hentikanJamRealtime();

            const bsModal = bootstrap.Modal.getInstance(modalEl);
            if (bsModal) {
                bsModal.hide();
            }

            setTimeout(() => {
                window.location.reload();
            }, 600);

        } catch (error) {
            console.error('Error presensi:', error);
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
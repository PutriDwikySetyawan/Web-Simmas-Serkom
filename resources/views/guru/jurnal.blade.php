{{-- resources/views/guru/jurnal.blade.php --}}
@extends('layouts.guru')

@section('title', 'Validasi Jurnal & Absensi')
@section('page-title', 'Validasi Jurnal & Absensi')

@section('styles')
<style>
    .vj-subtitle {
        color: var(--guru-muted);
        font-size: 0.88rem;
    }

    /* ================= STAT CARDS ================= */
    .vj-stat-card {
        background: #fff;
        border: 1px solid var(--guru-border);
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }

    .vj-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .vj-stat-icon.orange { background: #fff4e5; color: #d98324; }
    .vj-stat-icon.green  { background: #e7f8ef; color: #1c9c5b; }
    .vj-stat-icon.red    { background: var(--guru-danger-soft); color: var(--guru-danger); }

    .vj-stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--guru-ink);
        line-height: 1.1;
    }

    .vj-stat-label {
        font-size: 0.78rem;
        color: var(--guru-muted);
        font-weight: 500;
    }

    /* ================= TAB NAV ================= */
    .vj-tabs {
        border-bottom: 1px solid var(--guru-border);
        margin-bottom: 1.25rem;
    }

    .vj-tabs .nav-link {
        border: none;
        color: var(--guru-muted);
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.65rem 0.25rem;
        margin-right: 1.5rem;
        border-bottom: 2px solid transparent;
        background: none;
    }

    .vj-tabs .nav-link.active {
        color: var(--guru-primary);
        border-bottom-color: var(--guru-primary);
    }

    /* ================= TABLE CARD ================= */
    .vj-table-card {
        background: #fff;
        border: 1px solid var(--guru-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .vj-table thead th {
        background: #fafbfd;
        color: var(--guru-muted);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        border-bottom: 1px solid var(--guru-border);
        padding: 0.9rem 1rem;
        white-space: nowrap;
    }

    .vj-table tbody td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid var(--guru-border);
        vertical-align: middle;
    }

    .vj-table tbody tr:last-child td {
        border-bottom: none;
    }

    .vj-table tbody tr:hover {
        background: #fafbff;
    }

    .vj-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .vj-siswa-name {
        font-weight: 600;
        color: var(--guru-ink);
        font-size: 0.9rem;
    }

    .vj-siswa-sub {
        font-size: 0.76rem;
        color: var(--guru-muted);
    }

    .vj-kegiatan-text {
        font-size: 0.85rem;
        color: var(--guru-ink);
        max-width: 320px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .vj-btn-foto {
        font-size: 0.76rem;
        font-weight: 600;
        padding: 0.3rem 0.7rem;
        border-radius: 8px;
        background: #f1f2f6;
        color: var(--guru-ink);
        border: none;
    }

    .vj-btn-foto:hover {
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
    }

    /* Badge status */
    .vj-badge-status {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        display: inline-block;
    }

    .vj-badge-status.menunggu  { background: #fff4e5; color: #d98324; }
    .vj-badge-status.disetujui { background: #e7f8ef; color: #1c9c5b; }
    .vj-badge-status.revisi,
    .vj-badge-status.ditolak   { background: var(--guru-danger-soft); color: var(--guru-danger); }

    .vj-link-validasi {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--guru-primary);
        text-decoration: none;
        background: none;
        border: none;
    }

    .vj-link-validasi:hover { text-decoration: underline; }

    .vj-link-detail {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--guru-muted);
        text-decoration: none;
        background: none;
        border: none;
    }

    /* Empty state */
    .vj-empty {
        padding: 3.5rem 1rem;
        text-align: center;
    }

    .vj-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 1rem;
    }

    .vj-empty-title {
        font-weight: 700;
        color: var(--guru-ink);
        margin-bottom: 0.25rem;
    }

    .vj-empty-desc {
        color: var(--guru-muted);
        font-size: 0.86rem;
        max-width: 340px;
        margin: 0 auto;
    }

    /* ================= MODAL PILIH KEPUTUSAN (radio custom) ================= */
    .vj-keputusan-group {
        display: flex;
        gap: 0.75rem;
    }

    .vj-keputusan-option {
        flex: 1;
        border: 1.5px solid var(--guru-border);
        border-radius: 10px;
        padding: 0.65rem 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--guru-ink);
        transition: all 0.15s ease;
    }

    .vj-keputusan-option input {
        accent-color: #1c9c5b;
    }

    .vj-keputusan-option.is-checked {
        border-color: #1c9c5b;
        background: #e7f8ef;
        color: #1c9c5b;
    }

    .vj-keputusan-option.is-checked.tolak {
        border-color: var(--guru-danger);
        background: var(--guru-danger-soft);
        color: var(--guru-danger);
    }

    /* ================= MODAL PREVIEW FOTO (JURNAL / ABSENSI) =================
       Dipakai supaya foto bukti tampil di dalam modal (mirip pratinjau di
       halaman Absensi Siswa), bukan membuka tab baru lewat window.open().
    ========================================================================= */
    #modalLihatFotoGuru .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
    }

    #modalLihatFotoGuru .modal-header {
        border-bottom: 1px solid var(--guru-border);
        padding: 1.1rem 1.4rem;
    }

    #modalLihatFotoGuru .modal-title {
        font-size: 1rem;
        color: var(--guru-ink);
    }

    #modalLihatFotoGuru .modal-body {
        background: #f8fafc;
    }

   #vjFotoModalImg {
    width: 100%;
    max-height: 65vh;
    object-fit: cover;
    display: block;
}
    @media (max-width: 575.98px) {
        .vj-stat-card { padding: .9rem 1rem; }
        .vj-tabs { overflow-x: auto; white-space: nowrap; }
        .vj-tabs .nav-link { margin-right: 1rem; }
    }
</style>
@endsection

@section('content')

<p class="vj-subtitle mb-3">
    Modul utama evaluasi guru pembimbing untuk memvalidasi laporan jurnal harian
    dan presensi absensi siswa bimbingan.
</p>

{{-- Pesan jika guru belum punya siswa bimbingan --}}
@if(isset($totalSiswaBimbingan) && $totalSiswaBimbingan === 0)
    <div class="alert alert-warning d-flex align-items-center gap-2 mb-4 rounded-3" style="font-size:0.88rem;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Belum ada siswa bimbingan.</strong>
            Data jurnal dan absensi akan muncul setelah administrator melakukan plotting siswa kepada Anda di menu Penempatan Magang.
        </div>
    </div>
@else
    <div class="d-flex align-items-center gap-2 mb-4 p-3 rounded-3 border" style="background:#f8fafc; font-size:0.85rem;">
        <i class="bi bi-people-fill text-primary fs-5"></i>
        <span class="fw-bold text-dark">{{ $totalSiswaBimbingan ?? 0 }} Siswa Bimbingan</span>
        <span class="text-muted">— Data jurnal dan absensi di bawah hanya menampilkan siswa yang Anda bimbing.</span>
    </div>
@endif

{{-- TAB NAVIGATION --}}
<ul class="nav vj-tabs" id="vjTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-jurnal-btn" data-bs-toggle="tab"
                data-bs-target="#tab-jurnal" type="button" role="tab">
            Jurnal Kegiatan
            @if(isset($statJurnal) && $statJurnal['menunggu'] > 0)
                <span class="badge bg-warning text-dark ms-1" style="font-size:0.7rem;">{{ $statJurnal['menunggu'] }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-absensi-btn" data-bs-toggle="tab"
                data-bs-target="#tab-absensi" type="button" role="tab">
            Absensi Siswa
            @if(isset($statAbsensi) && $statAbsensi['menunggu'] > 0)
                <span class="badge bg-warning text-dark ms-1" style="font-size:0.7rem;">{{ $statAbsensi['menunggu'] }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content" id="vjTabContent">

    {{-- ======================================================== --}}
    {{-- TAB 1: JURNAL KEGIATAN --}}
    {{-- ======================================================== --}}
    <div class="tab-pane fade show active" id="tab-jurnal" role="tabpanel">

        {{-- Stat card jurnal --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="vj-stat-card">
                    <div class="vj-stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="vj-stat-value">{{ $statJurnal['menunggu'] ?? 0 }}</div>
                        <div class="vj-stat-label">Menunggu Validasi</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="vj-stat-card">
                    <div class="vj-stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="vj-stat-value">{{ $statJurnal['disetujui'] ?? 0 }}</div>
                        <div class="vj-stat-label">Disetujui</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="vj-stat-card">
                    <div class="vj-stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div>
                        <div class="vj-stat-value">{{ $statJurnal['revisi'] ?? 0 }}</div>
                        <div class="vj-stat-label">Perlu Revisi</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel jurnal --}}
        <div class="vj-table-card">
            @if ($jurnalList->isEmpty())
                <div class="vj-empty">
                    <div class="vj-empty-icon"><i class="bi bi-journal-text"></i></div>
                    <div class="vj-empty-title">Belum ada jurnal masuk</div>
                    <p class="vj-empty-desc mb-0">
                        Jurnal kegiatan siswa bimbingan akan muncul di sini setelah mereka mengisi laporan harian.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table vj-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tanggal & Siswa</th>
                                <th>Kegiatan</th>
                                <th class="text-center">Foto</th>
                                <th class="text-center">Status</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jurnalList as $jurnal)
                                @php
                                    $nama = $jurnal->siswa->profile->nama ?? '-';
                                    $inisial = collect(explode(' ', trim($nama)))
                                        ->map(fn ($k) => mb_substr($k, 0, 1))
                                        ->take(2)->implode('');
                                    $tglJurnalFull = \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('l, d F Y');
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="vj-avatar">{{ strtoupper($inisial) ?: '?' }}</div>
                                            <div>
                                                <div class="vj-siswa-name">{{ $nama }}</div>
                                                <div class="vj-siswa-sub">
                                                    {{ $jurnal->siswa->penempatan->tempatMagang->nama ?? '-' }} ·
                                                    {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="vj-kegiatan-text">{{ $jurnal->kegiatan }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if ($jurnal->photo_url)
                                            {{-- Foto dibuka lewat modal preview (lihatFotoGuru),
                                                 bukan tab baru --}}
                                            <button type="button" class="vj-btn-foto"
                                                    onclick="lihatFotoGuru('{{ asset('storage/' . $jurnal->photo_url) }}', 'Foto Kegiatan - {{ $nama }} ({{ $tglJurnalFull }})')">
                                                <i class="bi bi-image me-1"></i> Foto
                                            </button>
                                        @else
                                            <span class="vj-siswa-sub">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="vj-badge-status {{ $jurnal->status_verifikasi }}">
                                            {{ match($jurnal->status_verifikasi) {
                                                'disetujui' => 'Disetujui',
                                                'revisi' => 'Perlu Revisi',
                                                default => 'Menunggu',
                                            } }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        @php
                                            $dudiNama = $jurnal->siswa->penempatan->tempatMagang->nama ?? '-';
                                        @endphp
                                        @if ($jurnal->status_verifikasi === 'menunggu')
                                            <button type="button" class="vj-link-validasi btn-validasi-jurnal"
                                                    data-jurnal-id="{{ $jurnal->id }}"
                                                    data-siswa-nama="{{ $nama }}"
                                                    data-siswa-kelas="{{ $jurnal->siswa->kelas ?? '-' }}"
                                                    data-dudi="{{ $dudiNama }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}"
                                                    data-kegiatan="{{ $jurnal->kegiatan }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalValidasiJurnal">
                                                Validasi
                                            </button>
                                        @else
                                            <button type="button" class="vj-link-detail btn-validasi-jurnal"
                                                    data-jurnal-id="{{ $jurnal->id }}"
                                                    data-siswa-nama="{{ $nama }}"
                                                    data-siswa-kelas="{{ $jurnal->siswa->kelas ?? '-' }}"
                                                    data-dudi="{{ $dudiNama }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}"
                                                    data-kegiatan="{{ $jurnal->kegiatan }}"
                                                    data-catatan="{{ $jurnal->catatan_guru }}"
                                                    data-keputusan="{{ $jurnal->status_verifikasi }}"
                                                    data-readonly="1"
                                                    data-bs-toggle="modal" data-bs-target="#modalValidasiJurnal">
                                                Lihat Detail
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 2: ABSENSI SISWA --}}
    {{-- ======================================================== --}}
    <div class="tab-pane fade" id="tab-absensi" role="tabpanel">

        {{-- Stat card absensi --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-4">
                <div class="vj-stat-card">
                    <div class="vj-stat-icon orange"><i class="bi bi-hourglass-split"></i></div>
                    <div>
                        <div class="vj-stat-value">{{ $statAbsensi['menunggu'] ?? 0 }}</div>
                        <div class="vj-stat-label">Menunggu Validasi</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="vj-stat-card">
                    <div class="vj-stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="vj-stat-value">{{ $statAbsensi['disetujui'] ?? 0 }}</div>
                        <div class="vj-stat-label">Disetujui</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="vj-stat-card">
                    <div class="vj-stat-icon red"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="vj-stat-value">{{ $statAbsensi['ditolak'] ?? 0 }}</div>
                        <div class="vj-stat-label">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel absensi --}}
        <div class="vj-table-card">
            @if ($absensiList->isEmpty())
                <div class="vj-empty">
                    <div class="vj-empty-icon"><i class="bi bi-calendar-check"></i></div>
                    <div class="vj-empty-title">Belum ada presensi masuk</div>
                    <p class="vj-empty-desc mb-0">
                        Pengajuan absensi siswa bimbingan akan muncul di sini setelah mereka melakukan presensi.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table vj-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Tanggal & Siswa</th>
                                <th>Status Kehadiran</th>
                                <th class="text-center">Foto Bukti</th>
                                <th class="text-center">Status Validasi</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absensiList as $absensi)
                                @php
                                    $nama = $absensi->siswa->profile->nama ?? '-';
                                    $inisial = collect(explode(' ', trim($nama)))
                                        ->map(fn ($k) => mb_substr($k, 0, 1))
                                        ->take(2)->implode('');
                                    $statusKehadiran = $absensi->status ?? $absensi->status_kehadiran ?? 'hadir';
                                    $kehadiranLabel = match($statusKehadiran) {
                                        'sakit' => 'Sakit (Surat Dokter)',
                                        'izin'  => 'Izin',
                                        'alfa'  => 'Tanpa Keterangan (Alfa)',
                                        default => 'Hadir',
                                    };
                                    $fotoUrl = $absensi->photo_masuk_url ?? $absensi->photo_pulang_url ?? $absensi->photo_url ?? null;
                                    $isValidasiPending = in_array($absensi->status_validasi, ['pending', 'menunggu']);
                                    $tglAbsensiFull = \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('l, d F Y');
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="vj-avatar">{{ strtoupper($inisial) ?: '?' }}</div>
                                            <div>
                                                <div class="vj-siswa-name">{{ $nama }}</div>
                                                <div class="vj-siswa-sub">
                                                    {{ $absensi->siswa->kelas ?? '-' }} ·
                                                    {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d M Y') }}
                                                    @if($absensi->jam_masuk)
                                                        ({{ substr($absensi->jam_masuk, 0, 5) }} WIB)
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="vj-siswa-name" style="font-weight:600;">{{ $kehadiranLabel }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($fotoUrl)
                                            {{-- Foto dibuka lewat modal preview (lihatFotoGuru),
                                                 bukan tab baru --}}
                                            <button type="button" class="vj-btn-foto"
                                                    onclick="lihatFotoGuru('{{ asset('storage/' . $fotoUrl) }}', 'Foto Masuk - {{ $tglAbsensiFull }}')">
                                                <i class="bi bi-image me-1"></i> Foto
                                            </button>
                                        @else
                                            <span class="vj-siswa-sub">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="vj-badge-status {{ $absensi->status_validasi }}">
                                            {{ match($absensi->status_validasi) {
                                                'disetujui' => 'Disetujui',
                                                'ditolak'   => 'Ditolak',
                                                default     => 'Menunggu Validasi',
                                            } }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        @if ($isValidasiPending)
                                            <button type="button" class="vj-link-validasi btn-validasi-absensi"
                                                    data-absensi-id="{{ $absensi->id }}"
                                                    data-siswa-nama="{{ $nama }}"
                                                    data-siswa-kelas="{{ $absensi->siswa->kelas ?? '-' }}"
                                                    data-status-diajukan="{{ $kehadiranLabel }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalValidasiAbsensi">
                                                Validasi
                                            </button>
                                        @else
                                            <span class="vj-siswa-sub text-muted">Sudah diproses</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: VALIDASI JURNAL KEGIATAN --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalValidasiJurnal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-journal-check text-primary me-1"></i>
                        Validasi Jurnal Kegiatan
                    </h5>
                    <p class="text-muted small mb-0" id="jurnalModalSubtitle">Review aktivitas siswa</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Siswa</span>
                        <span class="fw-semibold" id="jmSiswa">-</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">DUDI</span>
                        <span class="fw-semibold" id="jmDudi">-</span>
                    </div>
                    <div class="mt-2">
                        <span class="text-muted small">Kegiatan</span>
                        <p class="mb-0 mt-1" id="jmKegiatan" style="font-size: 0.88rem;">-</p>
                    </div>
                </div>

                <form id="formValidasiJurnal">
                    @csrf

                    <div class="mb-3" id="jmKeputusanWrapper">
                        <label class="form-label fw-semibold small">Pilih Keputusan</label>
                        <div class="vj-keputusan-group">
                            <label class="vj-keputusan-option" id="jmOptSetuju">
                                <input type="radio" name="keputusan" value="disetujui" checked>
                                Setujui Jurnal
                            </label>
                            <label class="vj-keputusan-option" id="jmOptRevisi">
                                <input type="radio" name="keputusan" value="revisi">
                                Minta Revisi
                            </label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="jmCatatan" class="form-label fw-semibold small">
                            Catatan untuk Siswa <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <textarea class="form-control" id="jmCatatan" name="catatan" rows="3"
                                  placeholder="Bagus, pertahankan dokumentasi kode dengan rapi..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success px-4" id="btnSimpanValidasiJurnal">
                    <span class="btn-text">Simpan Validasi</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: VALIDASI PRESENSI SISWA (SAKIT/IZIN) --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalValidasiAbsensi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-calendar-check text-primary me-1"></i>
                        Validasi Presensi Siswa
                    </h5>
                    <p class="text-muted small mb-0">Tinjau pengajuan ketidakhadiran (Sakit/Izin)</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Siswa</span>
                        <span class="fw-semibold" id="amSiswa">-</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Status Diajukan</span>
                        <span class="fw-semibold text-warning" id="amStatus">-</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Tanggal</span>
                        <span class="fw-semibold" id="amTanggal">-</span>
                    </div>
                </div>

                <form id="formValidasiAbsensi">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Keputusan Guru Pembimbing</label>
                        <div class="vj-keputusan-group">
                            <label class="vj-keputusan-option" id="amOptSetuju">
                                <input type="radio" name="keputusan" value="disetujui" checked>
                                Setujui Absensi
                            </label>
                            <label class="vj-keputusan-option tolak" id="amOptTolak">
                                <input type="radio" name="keputusan" value="ditolak">
                                Tolak Presensi
                            </label>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="amCatatan" class="form-label fw-semibold small">
                            Catatan Pembimbing <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <textarea class="form-control" id="amCatatan" name="catatan" rows="3"
                                  placeholder="Semoga lekas sembuh dan dapat beraktivitas kembali..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success px-4" id="btnSimpanValidasiAbsensi">
                    <span class="btn-text">Simpan Keputusan</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: PREVIEW FOTO BUKTI (JURNAL & ABSENSI)
     Satu modal dipakai bersama oleh tombol "Foto" di tab Jurnal
     maupun tab Absensi — isinya (judul & gambar) diisi lewat JS
     fungsi lihatFotoGuru(url, title). Menggantikan window.open()
     yang sebelumnya membuka tab baru.
     ============================================================ --}}
<div class="modal fade" id="modalLihatFotoGuru" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold mb-0" id="vjFotoModalTitle">Foto Bukti</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <div class="modal-body text-center p-2">
    <img src="" id="vjFotoModalImg" alt="Foto Bukti" class="img-fluid rounded shadow-sm">
</div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ============================================================
    // PREVIEW FOTO (JURNAL & ABSENSI) — TAMPIL DI MODAL,
    // BUKAN MEMBUKA TAB BARU
    // ============================================================
    function lihatFotoGuru(url, title) {
        document.getElementById('vjFotoModalImg').src = url;
        document.getElementById('vjFotoModalTitle').textContent = title || 'Foto Bukti';
        const modal = new bootstrap.Modal(document.getElementById('modalLihatFotoGuru'));
        modal.show();
    }
    // Diekspos ke window supaya bisa dipanggil langsung dari atribut
    // onclick="..." di dalam blade (tombol "Foto" jurnal & absensi).
    window.lihatFotoGuru = lihatFotoGuru;

    // ============================================================
    // Helper: styling radio custom (highlight opsi yang dipilih)
    // ============================================================
    function bindKeputusanGroup(groupSelector) {
        document.querySelectorAll(groupSelector + ' .vj-keputusan-option').forEach(function (label) {
            const radio = label.querySelector('input[type="radio"]');
            radio.addEventListener('change', function () {
                document.querySelectorAll(groupSelector + ' .vj-keputusan-option').forEach(function (l) {
                    l.classList.remove('is-checked');
                });
                if (radio.checked) label.classList.add('is-checked');
            });
            if (radio.checked) label.classList.add('is-checked');
        });
    }
    bindKeputusanGroup('#jmKeputusanWrapper');
    bindKeputusanGroup('#modalValidasiAbsensi');

    // ============================================================
    // MODAL VALIDASI JURNAL
    // ============================================================
    const modalJurnal   = document.getElementById('modalValidasiJurnal');
    const formJurnal     = document.getElementById('formValidasiJurnal');
    const btnSimpanJurnal = document.getElementById('btnSimpanValidasiJurnal');
    let currentJurnalId = null;

    document.querySelectorAll('.btn-validasi-jurnal').forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentJurnalId = this.dataset.jurnalId;
            const isReadonly = this.dataset.readonly === '1';

            document.getElementById('jurnalModalSubtitle').textContent =
                'Review aktivitas siswa tanggal ' + this.dataset.tanggal;
            document.getElementById('jmSiswa').textContent =
                this.dataset.siswaNama + ' (' + this.dataset.siswaKelas + ')';
            document.getElementById('jmDudi').textContent = this.dataset.dudi;
            document.getElementById('jmKegiatan').textContent = this.dataset.kegiatan;

            const keputusanWrapper = document.getElementById('jmKeputusanWrapper');
            const catatanField = document.getElementById('jmCatatan');
            const footer = btnSimpanJurnal.closest('.modal-footer');

            if (isReadonly) {
                // Mode "Lihat Detail" — form tidak bisa diubah lagi
                keputusanWrapper.style.display = 'none';
                catatanField.value = this.dataset.catatan || '';
                catatanField.setAttribute('disabled', 'disabled');
                footer.style.display = 'none';
            } else {
                keputusanWrapper.style.display = '';
                catatanField.removeAttribute('disabled');
                catatanField.value = '';
                footer.style.display = '';

                // Reset ke pilihan default "Setujui Jurnal"
                formJurnal.querySelector('input[value="disetujui"]').checked = true;
                bindKeputusanGroup('#jmKeputusanWrapper');
            }
        });
    });

    btnSimpanJurnal.addEventListener('click', function () {
        if (!currentJurnalId) return;

        const keputusan = formJurnal.querySelector('input[name="keputusan"]:checked').value;
        const catatan = document.getElementById('jmCatatan').value;

        toggleLoading(btnSimpanJurnal, true);

        fetch(`{{ url('guru/jurnal') }}/${currentJurnalId}/validasi`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ keputusan: keputusan, catatan_guru: catatan, catatan: catatan }),
        })
        .then(async (response) => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
        })
        .then((data) => {
            toggleLoading(btnSimpanJurnal, false);
            bootstrap.Modal.getInstance(modalJurnal).hide();
            showToast(data.message || 'Validasi jurnal berhasil disimpan.', 'success');
            setTimeout(() => window.location.reload(), 800);
        })
        .catch(() => {
            toggleLoading(btnSimpanJurnal, false);
            showToast('Terjadi kesalahan saat menyimpan validasi.', 'danger');
        });
    });

    // ============================================================
    // MODAL VALIDASI ABSENSI
    // ============================================================
    const modalAbsensi    = document.getElementById('modalValidasiAbsensi');
    const formAbsensi      = document.getElementById('formValidasiAbsensi');
    const btnSimpanAbsensi = document.getElementById('btnSimpanValidasiAbsensi');
    let currentAbsensiId = null;

    document.querySelectorAll('.btn-validasi-absensi').forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentAbsensiId = this.dataset.absensiId;

            document.getElementById('amSiswa').textContent =
                this.dataset.siswaNama + ' (' + this.dataset.siswaKelas + ')';
            document.getElementById('amStatus').textContent = this.dataset.statusDiajukan;
            document.getElementById('amTanggal').textContent = this.dataset.tanggal;

            document.getElementById('amCatatan').value = '';
            formAbsensi.querySelector('input[value="disetujui"]').checked = true;
            bindKeputusanGroup('#modalValidasiAbsensi');
        });
    });

    btnSimpanAbsensi.addEventListener('click', function () {
        if (!currentAbsensiId) return;

        const keputusan = formAbsensi.querySelector('input[name="keputusan"]:checked').value;
        const catatan = document.getElementById('amCatatan').value;

        toggleLoading(btnSimpanAbsensi, true);

        fetch(`{{ url('guru/absensi') }}/${currentAbsensiId}/validasi`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ keputusan: keputusan, catatan_guru: catatan, catatan: catatan }),
        })
        .then(async (response) => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
        })
        .then((data) => {
            toggleLoading(btnSimpanAbsensi, false);
            bootstrap.Modal.getInstance(modalAbsensi).hide();
            showToast(data.message || 'Keputusan presensi berhasil disimpan.', 'success');
            setTimeout(() => window.location.reload(), 800);
        })
        .catch(() => {
            toggleLoading(btnSimpanAbsensi, false);
            showToast('Terjadi kesalahan saat menyimpan keputusan.', 'danger');
        });
    });

    // ============================================================
    // HELPER UMUM
    // ============================================================
    function toggleLoading(button, isLoading) {
        button.disabled = isLoading;
        button.querySelector('.btn-text').classList.toggle('d-none', isLoading);
        button.querySelector('.spinner-border').classList.toggle('d-none', !isLoading);
    }

    function showToast(message, type = 'success') {
        if (typeof window.showAppToast === 'function') {
            window.showAppToast(message, type);
            return;
        }
        alert(message);
    }
});
</script>
@endsection

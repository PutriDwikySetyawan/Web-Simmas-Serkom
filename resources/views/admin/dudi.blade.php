{{-- ==================================================================
     HALAMAN: MANAJEMEN DUDI (/admin/dudi)
     Modul pengelolaan mitra Dunia Usaha & Dunia Industri (DUDI)
     ================================================================== --}}
@extends('layouts.admin')

@section('title', 'Manajemen DUDI')
@section('page-title', 'Manajemen DUDI')

@push('styles')
<style>
    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .dudi-page-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--simmas-ink);
    }

    /* =========================================================
       STAT CARD (4 KARTU)
       Mengikuti bahasa desain dash-stat-card di Dashboard.
    ========================================================= */

    .dudi-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;

        margin-bottom: 20px;
    }

    .dudi-stat-card {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        padding: 18px 20px;

        transition: box-shadow 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
    }

    .dudi-stat-card:hover {
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
        transform: translateY(-2px);
        border-color: #dbe3ee;
    }

    .dudi-stat-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        margin-bottom: 10px;
    }

    .dudi-stat-card__label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;

        color: var(--simmas-muted);
    }

    .dudi-stat-card__icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        font-size: 1rem;
    }

    .dudi-stat-card__value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--simmas-ink);

        margin-bottom: 2px;
    }

    .dudi-stat-card__hint {
        font-size: 0.76rem;
        color: var(--simmas-muted);
    }

    /* =========================================================
       PANEL UTAMA (search, filter, tabel)
    ========================================================= */

    .dudi-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 16px;
        padding: 22px;
    }

    /* ---------------------------------------------------
       SEARCH & FILTER
    --------------------------------------------------- */

    .dudi-panel .input-group-text {
        border-radius: 10px 0 0 10px !important;
        border-color: var(--simmas-border) !important;
        color: var(--simmas-muted);
    }

    .dudi-panel .form-control,
    .dudi-panel .form-select {
        border-color: var(--simmas-border);
        font-size: 0.86rem;
    }

    .dudi-panel .form-control {
        border-radius: 0 10px 10px 0 !important;
    }

    .dudi-panel .form-select {
        border-radius: 10px;
    }

    .dudi-panel .form-control:focus,
    .dudi-panel .form-select:focus {
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    .dudi-count-badge {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--simmas-muted);
        white-space: nowrap;
    }

    .btn-dudi-primary {
        background: var(--simmas-blue);
        border: 1px solid var(--simmas-blue);
        color: #fff;

        border-radius: 10px;
        padding: 8px 16px;

        font-size: 0.85rem;
        font-weight: 700;

        display: inline-flex;
        align-items: center;
        gap: 6px;

        transition: background 0.15s ease;
    }

    .btn-dudi-primary:hover {
        background: var(--simmas-blue-dark);
        border-color: var(--simmas-blue-dark);
        color: #fff;
    }

    /* ---------------------------------------------------
       TABEL
    --------------------------------------------------- */

    .dudi-table thead th {
        background: var(--simmas-bg);

        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        border: none;
        padding: 12px 14px;
    }

    .dudi-table thead tr th:first-child {
        border-radius: 10px 0 0 10px;
    }

    .dudi-table thead tr th:last-child {
        border-radius: 0 10px 10px 0;
    }

    .dudi-table tbody td {
        padding: 14px;
        font-size: 0.86rem;
        color: var(--simmas-ink);

        border-bottom: 1px solid var(--simmas-border);
        vertical-align: middle;
    }

    .dudi-table tbody tr:last-child td {
        border-bottom: none;
    }

    .dudi-table tbody tr {
        transition: background 0.15s ease;
    }

    .dudi-table tbody tr:hover {
        background: var(--simmas-bg);
    }

    .dudi-row-icon {
        width: 38px;
        height: 38px;
        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: var(--simmas-blue-light);
        color: var(--simmas-blue);

        font-size: 0.95rem;
    }

    .dudi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        padding: 4px 10px;

        border-radius: 999px;

        font-size: 0.74rem;
        font-weight: 700;
    }

    .dudi-badge i {
        font-size: 0.6rem;
    }

    .dudi-badge-count {
        background: var(--simmas-bg);
        color: var(--simmas-muted);
    }

    .dudi-badge-success {
        background: rgba(22, 163, 74, 0.1);
        color: #16a34a;
    }

    .dudi-badge-warning {
        background: rgba(217, 119, 6, 0.1);
        color: #d97706;
    }

    .dudi-action-btn {
        width: 32px;
        height: 32px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;
        border: 1px solid var(--simmas-border);
        background: #fff;
        color: var(--simmas-muted);

        transition: background 0.15s ease, color 0.15s ease;
    }

    .dudi-action-btn:hover {
        background: var(--simmas-bg);
        color: var(--simmas-ink);
    }

    .dudi-empty {
        text-align: center;
        padding: 48px 0;
        color: var(--simmas-muted);
        font-size: 0.86rem;
    }

    /* ---------------------------------------------------
       MODAL
    --------------------------------------------------- */

    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header {
        align-items: flex-start;
        border-bottom: 1px solid var(--simmas-border);
        padding: 20px 22px;
    }

    .modal-header .modal-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--simmas-ink);
    }

    .modal-body {
        padding: 22px;
    }

    .modal-body .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--simmas-ink);
    }

    .modal-body .form-control,
    .modal-body .form-select {
        border-radius: 10px;
        border-color: var(--simmas-border);
        font-size: 0.86rem;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    .modal-footer {
        border-top: 1px solid var(--simmas-border);
        padding: 16px 22px;
    }

    .modal-footer .btn {
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 8px 16px;
    }

    .modal .btn-primary {
        background: var(--simmas-blue);
        border-color: var(--simmas-blue);
    }

    .modal .btn-primary:hover {
        background: var(--simmas-blue-dark);
        border-color: var(--simmas-blue-dark);
    }

    /* =========================================================
       TOAST NOTIFIKASI (FALLBACK)
       Dipakai HANYA jika layout admin belum punya fungsi
       global window.showAppToast(). Kalau sudah punya,
       toast ini tidak akan pernah dipakai.
    ========================================================= */

    .dudi-toast-wrap {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1080;

        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {
        .dudi-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .dudi-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="container-fluid py-4">

    {{-- ============================================================
         ELEMEN 2: 4 STAT CARD (data realtime dari $stats)
         ============================================================ --}}
    <div class="dudi-stats">

        {{-- Card: Total Mitra DUDI --}}
        <div class="dudi-stat-card">
            <div class="dudi-stat-card__top">
                <span class="dudi-stat-card__label">Total Mitra DUDI</span>
                <span class="dudi-stat-card__icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-building"></i>
                </span>
            </div>
            <div class="dudi-stat-card__value">{{ $stats['total_mitra'] }}</div>
            <div class="dudi-stat-card__hint">perusahaan terdaftar</div>
        </div>

        {{-- Card: Terverifikasi --}}
        <div class="dudi-stat-card">
            <div class="dudi-stat-card__top">
                <span class="dudi-stat-card__label">Terverifikasi</span>
                <span class="dudi-stat-card__icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="bi bi-check-circle-fill"></i>
                </span>
            </div>
            <div class="dudi-stat-card__value">{{ $stats['terverifikasi'] }}</div>
            <div class="dudi-stat-card__hint">siap menerima siswa</div>
        </div>

        {{-- Card: Menunggu Validasi --}}
        <div class="dudi-stat-card">
            <div class="dudi-stat-card__top">
                <span class="dudi-stat-card__label">Menunggu Validasi</span>
                <span class="dudi-stat-card__icon" style="background: #fef3c7; color: #d97706;">
                    <i class="bi bi-hourglass-split"></i>
                </span>
            </div>
            <div class="dudi-stat-card__value">{{ $stats['menunggu_validasi'] }}</div>
            <div class="dudi-stat-card__hint">perlu ditinjau</div>
        </div>

        {{-- Card: Siswa Ditempatkan --}}
        <div class="dudi-stat-card">
            <div class="dudi-stat-card__top">
                <span class="dudi-stat-card__label">Siswa Ditempatkan</span>
                <span class="dudi-stat-card__icon" style="background: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-people-fill"></i>
                </span>
            </div>
            <div class="dudi-stat-card__value">{{ $stats['siswa_ditempatkan'] }}</div>
            <div class="dudi-stat-card__hint">magang aktif</div>
        </div>

    </div>

    {{-- ============================================================
         ELEMEN 3: PANEL UTAMA — SEARCH, FILTER, TOMBOL TAMBAH, TABEL
         ============================================================ --}}
    <div class="dudi-panel">

            {{-- ------------------------------------------------------
                 3a. Baris Search & Filter & Tombol Tambah
                 ------------------------------------------------------ --}}
            <form method="GET" action="{{ route('admin.dudi.index') }}" class="row g-2 mb-3 align-items-center">

                {{-- Input pencarian --}}
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            class="form-control border-start-0"
                            placeholder="Cari perusahaan, alamat, atau PIC..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                {{-- Filter status verifikasi --}}
                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="semua" {{ request('status', 'semua') == 'semua' ? 'selected' : '' }}>
                            Semua Status
                        </option>
                        <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>
                            Terverifikasi
                        </option>
                        <option value="belum_diverifikasi" {{ request('status') == 'belum_diverifikasi' ? 'selected' : '' }}>
                            Belum Diverifikasi
                        </option>
                    </select>
                </div>

                {{-- Jumlah data & tombol tambah, didorong ke kanan --}}
                <div class="col-md-4 d-flex justify-content-end align-items-center gap-2">
                    <span class="dudi-count-badge">{{ $dudiList->total() }} DUDI</span>
                    <button
                        type="button"
                        class="btn-dudi-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambahDudi"
                    >
                        <i class="bi bi-plus-lg"></i> Tambah DUDI
                    </button>
                </div>

            </form>

            {{-- ------------------------------------------------------
                 3b. Tabel Data DUDI
                 ------------------------------------------------------ --}}
            <div class="table-responsive">
                <table class="table dudi-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Perusahaan</th>
                            <th>PIC & Kontak</th>
                            <th>Bidang Usaha</th>
                            <th class="text-center">Kuota</th>
                            <th class="text-center">Siswa Aktif</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dudiList as $dudi)
                            <tr>
                                {{-- Nama perusahaan + alamat singkat --}}
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="dudi-row-icon">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $dudi->nama_perusahaan }}</div>
                                            <div class="text-muted small">{{ Str::limit($dudi->alamat, 30) }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- PIC & kontak --}}
                                <td>
                                    <div>{{ $dudi->nama_pic }}</div>
                                    <div class="text-muted small">{{ $dudi->kontak_pic }}</div>
                                </td>

                                {{-- Bidang usaha --}}
                                <td>{{ $dudi->bidang_usaha }}</td>

                               {{-- Kuota — tampilkan sisa kuota dari total, pakai accessor
                                    sisa_kuota di Model TempatMagang (otomatis terisi dari
                                    siswa_aktif_count yang sudah di-withCount() di controller) --}}
                                <td class="text-center">
                                    <span class="fw-semibold">{{ $dudi->sisa_kuota }}</span>
                                    <span class="text-muted small">/ {{ $dudi->kuota }}</span>
                                </td>
                                
                                {{-- Siswa aktif (dari withCount) --}}
                                <td class="text-center">
                                    <span class="dudi-badge dudi-badge-count">
                                        <i class="bi bi-people-fill"></i> {{ $dudi->siswa_aktif_count }}
                                    </span>
                                </td>

                                {{-- Status verifikasi --}}
                                <td>
                                    @if ($dudi->status_verifikasi === 'terverifikasi')
                                        <span class="dudi-badge dudi-badge-success">
                                            <i class="bi bi-circle-fill"></i> Terverifikasi
                                        </span>
                                    @else
                                        <span class="dudi-badge dudi-badge-warning">
                                            <i class="bi bi-circle-fill"></i> Belum Diverifikasi
                                        </span>
                                    @endif
                                </td>

                                {{-- Menu aksi --}}
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button
                                            class="dudi-action-btn"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                        >
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            {{-- Trigger Edit, data dikirim lewat data-attribute --}}
                                            <li>
                                                <a
                                                    href="#"
                                                    class="dropdown-item btn-edit-dudi"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditDudi"
                                                    data-id="{{ $dudi->id }}"
                                                    data-nama-perusahaan="{{ $dudi->nama_perusahaan }}"
                                                    data-bidang-usaha="{{ $dudi->bidang_usaha }}"
                                                    data-nama-pic="{{ $dudi->nama_pic }}"
                                                    data-kontak-pic="{{ $dudi->kontak_pic }}"
                                                    data-kuota="{{ $dudi->kuota }}"
                                                    data-alamat="{{ $dudi->alamat }}"
                                                >
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            </li>

                                            {{-- Trigger Ubah Status Verifikasi --}}
                                            <li>
                                                <a
                                                    href="#"
                                                    class="dropdown-item btn-status-dudi"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalStatusDudi"
                                                    data-id="{{ $dudi->id }}"
                                                    data-nama-perusahaan="{{ $dudi->nama_perusahaan }}"
                                                    data-status-verifikasi="{{ $dudi->status_verifikasi }}"
                                                >
                                                    <i class="bi bi-arrow-repeat"></i> Ubah Status
                                                </a>
                                            </li>

                                            <li><hr class="dropdown-divider"></li>

                                            {{-- Trigger Alert Hapus --}}
                                            <li>
                                                <a
                                                    href="#"
                                                    class="dropdown-item text-danger btn-hapus-dudi"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalHapusDudi"
                                                    data-id="{{ $dudi->id }}"
                                                    data-nama-perusahaan="{{ $dudi->nama_perusahaan }}"
                                                >
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Empty state --}}
                            <tr>
                                <td colspan="7" class="dudi-empty">
                                    Belum ada data mitra DUDI. Tekan "Tambah DUDI" untuk mulai mendaftarkan mitra.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ------------------------------------------------------
                 3c. Pagination
                 ------------------------------------------------------ --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $dudiList->links() }}
            </div>

    </div>

</div>

{{-- ==================================================================
     ELEMEN 4: MODAL TAMBAH DUDI
     ================================================================== --}}
<div class="modal fade" id="modalTambahDudi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formTambahDudi">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Tambah Mitra Industri (DUDI)</h5>
                        <p class="text-muted small mb-0">Daftarkan mitra DUDI baru sebagai tempat magang siswa.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- Alert error validasi, ditampilkan lewat JS --}}
                    <div class="alert alert-danger d-none" id="errorTambahDudi"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama PIC</label>
                            <input type="text" name="nama_pic" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kontak PIC (WA/Telp)</label>
                            <input type="text" name="kontak_pic" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kuota Magang (Siswa)</label>
                            <input type="number" name="kuota" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select" required>
                                <option value="belum_diverifikasi">Belum Diverifikasi</option>
                                <option value="terverifikasi">Terverifikasi (Aktif)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap Perusahaan</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Mitra DUDI</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ==================================================================
     ELEMEN 5: MODAL EDIT DUDI
     ================================================================== --}}
<div class="modal fade" id="modalEditDudi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formEditDudi">
                @csrf
                <input type="hidden" name="id" id="editDudiId">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Edit Mitra Industri (DUDI)</h5>
                        <p class="text-muted small mb-0">Ubah rincian profil mitra magang.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-danger d-none" id="errorEditDudi"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" id="editNamaPerusahaan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" id="editBidangUsaha" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Kontak PIC</label>
                            <input type="text" name="nama_pic" id="editNamaPic" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No. Telp/WA PIC</label>
                            <input type="text" name="kontak_pic" id="editKontakPic" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kuota Magang</label>
                            <input type="number" name="kuota" id="editKuota" class="form-control" min="1" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" id="editAlamat" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ==================================================================
     ELEMEN 6: MODAL UBAH STATUS VERIFIKASI
     ================================================================== --}}
<div class="modal fade" id="modalStatusDudi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formStatusDudi">
                @csrf
                <input type="hidden" name="id" id="statusDudiId">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Verifikasi Mitra DUDI</h5>
                        <p class="text-muted small mb-0" id="statusDudiKeterangan">Ubah status verifikasi perusahaan.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="errorStatusDudi"></div>

                    <label class="form-label">Status Verifikasi</label>
                    <select name="status_verifikasi" id="statusDudiSelect" class="form-select" required>
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="belum_diverifikasi">Belum Diverifikasi</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ==================================================================
     ELEMEN 7: ALERT DIALOG — KONFIRMASI HAPUS DUDI
     ================================================================== --}}
<div class="modal fade" id="modalHapusDudi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center py-4">

                <div class="text-danger bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 48px; height: 48px;">
                    <i class="bi bi-trash fs-5"></i>
                </div>

                <h5>Hapus Mitra Industri (DUDI)?</h5>
                <p class="text-muted">
                    Apakah Anda yakin ingin menghapus data mitra
                    <strong id="hapusDudiNama"></strong>?
                    Data mitra yang sedang memiliki siswa aktif tidak dapat dihapus.
                </p>

                <div class="alert alert-danger d-none" id="errorHapusDudi"></div>

                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btnKonfirmasiHapusDudi">Ya, Hapus DUDI</button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ==================================================================
     ELEMEN 8: WADAH TOAST NOTIFIKASI (FALLBACK)
     Kosong secara default, diisi lewat JS setiap kali ada notifikasi.
     Hanya benar-benar dipakai jika window.showAppToast belum tersedia
     di layouts.admin.
     ================================================================== --}}
<div class="dudi-toast-wrap" id="dudiToastWrap"></div>

@endsection

@push('scripts')
<script>
// ======================================================================
// SCRIPT HALAMAN MANAJEMEN DUDI
// Semua request pakai fetch() ke endpoint JSON dari DudiController
// ======================================================================

document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ------------------------------------------------------------
    // HELPER: tampilkan notifikasi sukses/gagal
    // Prioritas: pakai window.showAppToast() bawaan layout admin
    // (kalau sudah didefinisikan di layouts.admin, sama seperti
    // yang dipakai di halaman Absensi Siswa). Kalau belum ada,
    // fallback ke toast Bootstrap sederhana buatan sendiri di sini
    // supaya halaman ini tetap bisa menampilkan notifikasi tanpa
    // harus mengubah layout dulu.
    // ------------------------------------------------------------
    function tampilkanNotifikasi(pesan, tipe = 'success') {
        if (typeof window.showAppToast === 'function') {
            window.showAppToast(pesan, tipe);
            return;
        }

        const warnaBg = tipe === 'success' ? 'bg-success' : 'bg-danger';
        const icon = tipe === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white ${warnaBg} border-0`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body"><i class="bi ${icon} me-2"></i>${pesan}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;

        document.getElementById('dudiToastWrap').appendChild(toastEl);

        const bsToast = new bootstrap.Toast(toastEl, { delay: 2500 });
        bsToast.show();

        // Bersihkan elemen toast dari DOM setelah hilang, biar tidak numpuk
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    // ------------------------------------------------------------
    // HELPER: reload halaman setelah aksi sukses.
    // Diberi jeda (delay) supaya notifikasi sempat terlihat dulu
    // sebelum halaman refresh.
    // ------------------------------------------------------------
    function reloadHalaman(delay = 900) {
        setTimeout(() => window.location.reload(), delay);
    }

    // ------------------------------------------------------------
    // HELPER: tutup modal tertentu (dipanggil setelah aksi sukses)
    // ------------------------------------------------------------
    function tutupModal(modalId) {
        const modalEl = document.getElementById(modalId);
        const instance = bootstrap.Modal.getInstance(modalEl);
        if (instance) instance.hide();
    }

    // ------------------------------------------------------------
    // HELPER: tampilkan pesan error di alert box tertentu
    // ------------------------------------------------------------
    function tampilkanError(elId, pesan) {
        const el = document.getElementById(elId);
        el.textContent = pesan;
        el.classList.remove('d-none');
    }

    // ==================================================================
    // 1. SUBMIT FORM TAMBAH DUDI
    // ==================================================================
    document.getElementById('formTambahDudi').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const data = Object.fromEntries(new FormData(form).entries());

        fetch(`{{ route('admin.dudi.store') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(data),
        })
        .then(async (res) => {
            const body = await res.json();
            if (!res.ok) {
                throw body;
            }

            // Tutup modal dulu, baru tampilkan notifikasi, baru reload
            tutupModal('modalTambahDudi');
            tampilkanNotifikasi(`Mitra DUDI "${data.nama_perusahaan}" berhasil ditambahkan.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            const pesan = err.errors
                ? Object.values(err.errors).flat().join(' ')
                : (err.message || 'Terjadi kesalahan, silakan coba lagi.');
            tampilkanError('errorTambahDudi', pesan);
        });
    });

    // ==================================================================
    // 2. BUKA MODAL EDIT — isi form dari data-attribute tombol
    // ==================================================================
    document.querySelectorAll('.btn-edit-dudi').forEach((btn) => {
        btn.addEventListener('click', function () {
            document.getElementById('editDudiId').value = this.dataset.id;
            document.getElementById('editNamaPerusahaan').value = this.dataset.namaPerusahaan;
            document.getElementById('editBidangUsaha').value = this.dataset.bidangUsaha;
            document.getElementById('editNamaPic').value = this.dataset.namaPic;
            document.getElementById('editKontakPic').value = this.dataset.kontakPic;
            document.getElementById('editKuota').value = this.dataset.kuota;
            document.getElementById('editAlamat').value = this.dataset.alamat;
        });
    });

    // ==================================================================
    // 3. SUBMIT FORM EDIT DUDI
    // ==================================================================
    document.getElementById('formEditDudi').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('editDudiId').value;
        const form = e.target;
        const data = Object.fromEntries(new FormData(form).entries());
        delete data.id; // tidak perlu dikirim di body, sudah ada di URL

        fetch(`/admin/dudi/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(data),
        })
        .then(async (res) => {
            const body = await res.json();
            if (!res.ok) {
                throw body;
            }

            tutupModal('modalEditDudi');
            tampilkanNotifikasi(`Data mitra DUDI "${data.nama_perusahaan}" berhasil diperbarui.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            const pesan = err.errors
                ? Object.values(err.errors).flat().join(' ')
                : (err.message || 'Terjadi kesalahan, silakan coba lagi.');
            tampilkanError('errorEditDudi', pesan);
        });
    });

    // ==================================================================
    // 4. BUKA MODAL UBAH STATUS VERIFIKASI
    // ==================================================================
    document.querySelectorAll('.btn-status-dudi').forEach((btn) => {
        btn.addEventListener('click', function () {
            document.getElementById('statusDudiId').value = this.dataset.id;
            document.getElementById('statusDudiSelect').value = this.dataset.statusVerifikasi;
            document.getElementById('statusDudiKeterangan').textContent =
                `Perbarui status verifikasi untuk ${this.dataset.namaPerusahaan}.`;
        });
    });

    // ==================================================================
    // 5. SUBMIT FORM UBAH STATUS VERIFIKASI
    // ==================================================================
    document.getElementById('formStatusDudi').addEventListener('submit', function (e) {
        e.preventDefault();

        const id = document.getElementById('statusDudiId').value;
        const status = document.getElementById('statusDudiSelect').value;
        const namaPerusahaan = document.getElementById('statusDudiKeterangan').textContent;

        fetch(`/admin/dudi/${id}/verifikasi`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ status_verifikasi: status }),
        })
        .then(async (res) => {
            const body = await res.json();
            if (!res.ok) {
                throw body;
            }

            tutupModal('modalStatusDudi');
            tampilkanNotifikasi('Status verifikasi mitra DUDI berhasil diperbarui.', 'success');
            reloadHalaman();
        })
        .catch((err) => {
            const pesan = err.errors
                ? Object.values(err.errors).flat().join(' ')
                : (err.message || 'Terjadi kesalahan, silakan coba lagi.');
            tampilkanError('errorStatusDudi', pesan);
        });
    });

    // ==================================================================
    // 6. BUKA ALERT HAPUS — simpan id & tampilkan nama
    // ==================================================================
    let idDudiAkanDihapus = null;
    let namaDudiAkanDihapus = null;

    document.querySelectorAll('.btn-hapus-dudi').forEach((btn) => {
        btn.addEventListener('click', function () {
            idDudiAkanDihapus = this.dataset.id;
            namaDudiAkanDihapus = this.dataset.namaPerusahaan;
            document.getElementById('hapusDudiNama').textContent = this.dataset.namaPerusahaan;
            document.getElementById('errorHapusDudi').classList.add('d-none');
        });
    });

    // ==================================================================
    // 7. KONFIRMASI HAPUS DUDI
    // ==================================================================
    document.getElementById('btnKonfirmasiHapusDudi').addEventListener('click', function () {
        if (!idDudiAkanDihapus) return;

        fetch(`/admin/dudi/${idDudiAkanDihapus}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(async (res) => {
            const body = await res.json();
            if (!res.ok) {
                throw body;
            }

            tutupModal('modalHapusDudi');
            tampilkanNotifikasi(`Mitra DUDI "${namaDudiAkanDihapus}" berhasil dihapus.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            tampilkanError('errorHapusDudi', err.message || 'Mitra ini masih memiliki siswa aktif magang.');
        });
    });

});
</script>
@endpush
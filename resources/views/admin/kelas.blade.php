{{-- Menggunakan template layout utama administrator --}}
@extends('layouts.admin')

{{-- Mengatur judul pada tab browser --}}
@section('title', 'Data Kelas')

{{-- Mengatur judul pada topbar header --}}
@section('page-title', 'Manajemen Kelas')

@push('styles')
<style>
    /* =========================================================
       1. STATISTIK KARTU WIDGET
       Mengatur grid counter statistik (Total Kelas, Aktif, dll)
    ========================================================= */
    .kelas-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .kelas-stat-card {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .kelas-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
    }

    .kelas-stat-card__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .kelas-stat-card__label {
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--simmas-muted);
    }

    .kelas-stat-card__icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .kelas-stat-card__value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--simmas-ink);
        line-height: 1.2;
    }

    .kelas-stat-card__hint {
        font-size: 0.76rem;
        color: var(--simmas-muted);
        margin-top: 4px;
    }

    /* =========================================================
       PANEL UTAMA (Search, Filter, Tabel)
    ========================================================= */
    .kelas-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 16px;
        padding: 22px;
    }

    .kelas-panel .form-control,
    .kelas-panel .form-select {
        border-color: var(--simmas-border);
        font-size: 0.86rem;
        border-radius: 10px;
    }

    .kelas-panel .form-control:focus,
    .kelas-panel .form-select:focus {
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    .btn-kelas-primary {
        background: var(--simmas-blue);
        border: 1px solid var(--simmas-blue);
        color: #fff;
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s ease, transform 0.1s ease;
    }

    .btn-kelas-primary:hover {
        background: var(--simmas-blue-dark);
        border-color: var(--simmas-blue-dark);
        color: #fff;
    }

    /* =========================================================
       TABEL KELAS
    ========================================================= */
    .table-kelas {
        margin: 0;
    }

    .table-kelas thead th {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--simmas-muted);
        background: var(--simmas-bg);
        border-bottom: 1px solid var(--simmas-border);
        padding: 12px 16px;
    }

    .table-kelas tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.86rem;
    }

    .table-kelas tbody tr:hover td {
        background: #f8fafc;
    }

    .badge-tingkat {
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.72rem;
    }

    .badge-tingkat-x { background: #eff6ff; color: #1d4ed8; }
    .badge-tingkat-xi { background: #f0fdf4; color: #15803d; }
    .badge-tingkat-xii { background: #fef3c7; color: #b45309; }
    .badge-tingkat-xiii { background: #fdf2f8; color: #be185d; }

    .kelas-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .kelas-badge-status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .kelas-badge-status--aktif {
        background: #dcfce7;
        color: #16a34a;
    }

    .kelas-badge-status--nonaktif {
        background: #f1f5f9;
        color: #64748b;
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--simmas-border);
        background: #fff;
        color: var(--simmas-muted);
        transition: all 0.15s ease;
        font-size: 0.85rem;
    }

    .btn-action-icon:hover {
        background: var(--simmas-bg);
        color: var(--simmas-ink);
        border-color: #cbd5e1;
    }

    .btn-action-icon.text-danger:hover {
        background: #fee2e2;
        color: #dc2626 !important;
        border-color: #fca5a5;
    }

    @media (max-width: 992px) {
        .kelas-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .kelas-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- ============================================================
         1. STAT CARDS
         ============================================================ --}}
    <div class="kelas-stats">
        {{-- Card: Total Kelas --}}
        <div class="kelas-stat-card">
            <div class="kelas-stat-card__top">
                <span class="kelas-stat-card__label">Total Kelas</span>
                <span class="kelas-stat-card__icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-easel2-fill"></i>
                </span>
            </div>
            <div class="kelas-stat-card__value">{{ $stats['total_kelas'] }}</div>
            <div class="kelas-stat-card__hint">rombongan belajar terdata</div>
        </div>

        {{-- Card: Kelas Aktif --}}
        <div class="kelas-stat-card">
            <div class="kelas-stat-card__top">
                <span class="kelas-stat-card__label">Kelas Aktif</span>
                <span class="kelas-stat-card__icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="bi bi-check-circle-fill"></i>
                </span>
            </div>
            <div class="kelas-stat-card__value">{{ $stats['kelas_aktif'] }}</div>
            <div class="kelas-stat-card__hint">status rombel aktif</div>
        </div>

        {{-- Card: Jurusan / Konsentrasi --}}
        <div class="kelas-stat-card">
            <div class="kelas-stat-card__top">
                <span class="kelas-stat-card__label">Program Keahlian</span>
                <span class="kelas-stat-card__icon" style="background: #fef3c7; color: #d97706;">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>
            </div>
            <div class="kelas-stat-card__value">{{ $stats['total_jurusan'] }}</div>
            <div class="kelas-stat-card__hint">jurusan terdaftar</div>
        </div>

        {{-- Card: Total Siswa --}}
        <div class="kelas-stat-card">
            <div class="kelas-stat-card__top">
                <span class="kelas-stat-card__label">Total Siswa</span>
                <span class="kelas-stat-card__icon" style="background: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-people-fill"></i>
                </span>
            </div>
            <div class="kelas-stat-card__value">{{ $stats['total_siswa'] }}</div>
            <div class="kelas-stat-card__hint">siswa aktif dalam sistem</div>
        </div>
    </div>

    {{-- ============================================================
         2. PANEL UTAMA (FILTER & TABEL)
         ============================================================ --}}
    <div class="kelas-panel">

        {{-- Toolbar Filter & Action --}}
        <form method="GET" action="{{ route('admin.kelas.index') }}" class="row g-2 align-items-center mb-4">
            {{-- Search input --}}
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control border-start-0"
                           style="border-radius: 0 10px 10px 0;"
                           placeholder="Cari kelas, jurusan, wali kelas...">
                </div>
            </div>

            {{-- Filter Tingkat --}}
            <div class="col-6 col-md-2">
                <select name="tingkat" class="form-select" onchange="this.form.submit()">
                    <option value="semua">Semua Tingkat</option>
                    <option value="X" {{ request('tingkat') == 'X' ? 'selected' : '' }}>Tingkat X</option>
                    <option value="XI" {{ request('tingkat') == 'XI' ? 'selected' : '' }}>Tingkat XI</option>
                    <option value="XII" {{ request('tingkat') == 'XII' ? 'selected' : '' }}>Tingkat XII</option>
                    <option value="XIII" {{ request('tingkat') == 'XIII' ? 'selected' : '' }}>Tingkat XIII</option>
                </select>
            </div>

            {{-- Filter Jurusan --}}
            <div class="col-6 col-md-2">
                <select name="jurusan" class="form-select" onchange="this.form.submit()">
                    <option value="semua">Semua Jurusan</option>
                    @foreach ($jurusanList as $j)
                        @php $kode = is_string($j) ? $j : $j->kode_jurusan; @endphp
                        <option value="{{ $kode }}" {{ request('jurusan') == $kode ? 'selected' : '' }}>
                            {{ $kode }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status --}}
            <div class="col-6 col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="semua">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Tombol Tambah Kelas --}}
            <div class="col-6 col-md-2 text-end">
                <button type="button" class="btn btn-kelas-primary w-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Kelas</span>
                </button>
            </div>
        </form>

        {{-- Tabel Data Kelas --}}
        <div class="table-responsive">
            <table class="table table-kelas align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Kelas</th>
                        <th>Tingkat & Jurusan</th>
                        <th>Wali Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Kapasitas & Siswa</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelasList as $index => $kelas)
                        @php
                            $tingkatClass = match(strtoupper($kelas->tingkat)) {
                                'X'    => 'badge-tingkat-x',
                                'XI'   => 'badge-tingkat-xi',
                                'XII'  => 'badge-tingkat-xii',
                                'XIII' => 'badge-tingkat-xiii',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <tr>
                            <td class="text-center text-muted fw-bold">
                                {{ $kelasList->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fw-bold text-dark fs-6">{{ $kelas->nama_kelas }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge-tingkat {{ $tingkatClass }}">{{ $kelas->tingkat }}</span>
                                    <span class="text-muted fw-semibold">{{ $kelas->jurusan }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 text-dark">
                                    <i class="bi bi-person-badge text-muted me-1"></i>
                                    <span>{{ $kelas->wali_kelas ?: '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ $kelas->tahun_ajaran ?: '-' }}</span>
                            </td>
                            <td>
                                <div>
                                    <span class="fw-bold text-dark">{{ $kelas->siswa_count }}</span>
                                    <span class="text-muted">/ {{ $kelas->kapasitas }} siswa</span>
                                </div>
                                @if ($kelas->kapasitas > 0)
                                    @php
                                        $percent = min(100, round(($kelas->siswa_count / $kelas->kapasitas) * 100));
                                        $barColor = $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-primary');
                                    @endphp
                                    <div class="progress mt-1" style="height: 4px; width: 100px;">
                                        <div class="progress-bar {{ $barColor }}" style="width: {{ $percent }}%;"></div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="kelas-badge-status {{ $kelas->is_active ? 'kelas-badge-status--aktif' : 'kelas-badge-status--nonaktif' }}">
                                    {{ $kelas->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    {{-- Edit Button --}}
                                    <button type="button"
                                            class="btn-action-icon btn-edit-kelas"
                                            title="Edit Kelas"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditKelas"
                                            data-id="{{ $kelas->id }}"
                                            data-nama-kelas="{{ $kelas->nama_kelas }}"
                                            data-tingkat="{{ $kelas->tingkat }}"
                                            data-jurusan="{{ $kelas->jurusan }}"
                                            data-wali-kelas="{{ $kelas->wali_kelas }}"
                                            data-tahun-ajaran="{{ $kelas->tahun_ajaran }}"
                                            data-kapasitas="{{ $kelas->kapasitas }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- Status Toggle Button --}}
                                    <button type="button"
                                            class="btn-action-icon btn-toggle-status"
                                            title="{{ $kelas->is_active ? 'Nonaktifkan Kelas' : 'Aktifkan Kelas' }}"
                                            data-id="{{ $kelas->id }}"
                                            data-nama-kelas="{{ $kelas->nama_kelas }}"
                                            data-is-active="{{ $kelas->is_active ? '1' : '0' }}">
                                        <i class="bi {{ $kelas->is_active ? 'bi-toggle-on text-success fs-5' : 'bi-toggle-off text-muted fs-5' }}"></i>
                                    </button>

                                    {{-- Hapus Button --}}
                                    <button type="button"
                                            class="btn-action-icon text-danger btn-hapus-kelas"
                                            title="Hapus Kelas"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapusKelas"
                                            data-id="{{ $kelas->id }}"
                                            data-nama-kelas="{{ $kelas->nama_kelas }}"
                                            data-siswa-count="{{ $kelas->siswa_count }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted mb-2" style="font-size: 2.5rem;">
                                    <i class="bi bi-easel2"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Belum ada data kelas</h6>
                                <p class="text-muted small mb-3">Tidak ditemukan data kelas yang sesuai dengan kriteria pencarian.</p>
                                <button type="button" class="btn btn-sm btn-kelas-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Kelas Baru
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($kelasList->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $kelasList->firstItem() }}</strong> - <strong>{{ $kelasList->lastItem() }}</strong> dari <strong>{{ $kelasList->total() }}</strong> kelas
                </div>
                <div>
                    {{ $kelasList->links() }}
                </div>
            </div>
        @endif

    </div>

</div>

{{-- ============================================================
     3. MODAL TAMBAH KELAS
     ============================================================ --}}
<div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-labelledby="modalTambahKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold" id="modalTambahKelasLabel">
                    <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Data Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahKelas">
                <div class="modal-body px-4 py-3">
                    <div id="errorTambahKelas" class="alert alert-danger d-none py-2 small mb-3"></div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: XII PPLG 1" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Tingkat <span class="text-danger">*</span></label>
                            <select name="tingkat" class="form-select" required>
                                <option value="X">Tingkat X</option>
                                <option value="XI">Tingkat XI</option>
                                <option value="XII" selected>Tingkat XII</option>
                                <option value="XIII">Tingkat XIII</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Jurusan / Program Keahlian <span class="text-danger">*</span></label>
                            <select name="jurusan" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Jurusan --</option>
                                @foreach ($jurusanList as $j)
                                    @php
                                        $kode = is_string($j) ? $j : $j->kode_jurusan;
                                        $nama = is_string($j) ? '' : $j->nama_jurusan;
                                    @endphp
                                    <option value="{{ $kode }}">{{ $kode }}{{ $nama ? ' — ' . $nama : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Wali Kelas</label>
                        <input type="text" name="wali_kelas" class="form-control" placeholder="Nama Guru Wali Kelas" list="listWaliKelas">
                        <datalist id="listWaliKelas">
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->profile->nama ?? '' }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="Contoh: 2025/2026" value="2025/2026">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Kapasitas Maksimal</label>
                            <input type="number" name="kapasitas" class="form-control" min="1" max="100" value="36">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" id="btnSubmitTambahKelas">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerTambah"></span>
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     4. MODAL EDIT KELAS
     ============================================================ --}}
<div class="modal fade" id="modalEditKelas" tabindex="-1" aria-labelledby="modalEditKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold" id="modalEditKelasLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKelas">
                <input type="hidden" id="editKelasId">
                <div class="modal-body px-4 py-3">
                    <div id="errorEditKelas" class="alert alert-danger d-none py-2 small mb-3"></div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" id="editNamaKelas" name="nama_kelas" class="form-control" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Tingkat <span class="text-danger">*</span></label>
                            <select id="editTingkat" name="tingkat" class="form-select" required>
                                <option value="X">Tingkat X</option>
                                <option value="XI">Tingkat XI</option>
                                <option value="XII">Tingkat XII</option>
                                <option value="XIII">Tingkat XIII</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Jurusan <span class="text-danger">*</span></label>
                            <select id="editJurusan" name="jurusan" class="form-select" required>
                                <option value="" disabled>-- Pilih Jurusan --</option>
                                @foreach ($jurusanList as $j)
                                    @php
                                        $kode = is_string($j) ? $j : $j->kode_jurusan;
                                        $nama = is_string($j) ? '' : $j->nama_jurusan;
                                    @endphp
                                    <option value="{{ $kode }}">{{ $kode }}{{ $nama ? ' — ' . $nama : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Wali Kelas</label>
                        <input type="text" id="editWaliKelas" name="wali_kelas" class="form-control" list="listWaliKelas">
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Tahun Ajaran</label>
                            <input type="text" id="editTahunAjaran" name="tahun_ajaran" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Kapasitas Maksimal</label>
                            <input type="number" id="editKapasitas" name="kapasitas" class="form-control" min="1" max="100">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" id="btnSubmitEditKelas">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerEdit"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     5. MODAL HAPUS KELAS
     ============================================================ --}}
<div class="modal fade" id="modalHapusKelas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3" style="font-size: 2.5rem;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <h6 class="fw-bold mb-2">Hapus Data Kelas?</h6>
                <p class="text-muted small mb-3">
                    Apakah Anda yakin ingin menghapus kelas <strong id="hapusKelasNama" class="text-dark"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div id="errorHapusKelas" class="alert alert-danger d-none py-2 small mb-3 text-start"></div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm flex-fill fw-bold" id="btnKonfirmasiHapusKelas">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerHapus"></span>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast container jika belum tersedia secara global --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;" id="kelasToastWrap"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function tampilkanNotifikasi(pesan, tipe = 'success') {
        if (typeof window.showAppToast === 'function') {
            window.showAppToast(pesan, tipe);
            return;
        }

        const warnaBg = tipe === 'success' ? 'bg-success' : 'bg-danger';
        const icon = tipe === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-white ${warnaBg} border-0 shadow`;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body"><i class="bi ${icon} me-2"></i>${pesan}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;

        document.getElementById('kelasToastWrap').appendChild(toastEl);
        const bsToast = new bootstrap.Toast(toastEl, { delay: 2500 });
        bsToast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    function reloadHalaman(delay = 800) {
        setTimeout(() => window.location.reload(), delay);
    }

    function tutupModal(modalId) {
        const modalEl = document.getElementById(modalId);
        const instance = bootstrap.Modal.getInstance(modalEl);
        if (instance) instance.hide();
    }

    function tampilkanError(elId, pesan) {
        const el = document.getElementById(elId);
        if (el) {
            el.textContent = pesan;
            el.classList.remove('d-none');
        }
    }

    function sembunyikanError(elId) {
        const el = document.getElementById(elId);
        if (el) el.classList.add('d-none');
    }

    // ------------------------------------------------------------
    // 1. TAMBAH KELAS
    // ------------------------------------------------------------
    document.getElementById('formTambahKelas').addEventListener('submit', function (e) {
        e.preventDefault();
        sembunyikanError('errorTambahKelas');
        const spinner = document.getElementById('spinnerTambah');
        const btn = document.getElementById('btnSubmitTambahKelas');
        spinner.classList.remove('d-none');
        btn.disabled = true;

        const data = Object.fromEntries(new FormData(e.target).entries());

        fetch(`{{ route('admin.kelas.store') }}`, {
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
            if (!res.ok) throw body;
            tutupModal('modalTambahKelas');
            tampilkanNotifikasi(`Kelas "${data.nama_kelas}" berhasil ditambahkan.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            const pesan = err.errors
                ? Object.values(err.errors).flat().join(' ')
                : (err.message || 'Terjadi kesalahan saat menambahkan kelas.');
            tampilkanError('errorTambahKelas', pesan);
        })
        .finally(() => {
            spinner.classList.add('d-none');
            btn.disabled = false;
        });
    });

    // ------------------------------------------------------------
    // 2. BUKA MODAL EDIT KELAS
    // ------------------------------------------------------------
    document.querySelectorAll('.btn-edit-kelas').forEach((btn) => {
        btn.addEventListener('click', function () {
            sembunyikanError('errorEditKelas');
            document.getElementById('editKelasId').value = this.dataset.id;
            document.getElementById('editNamaKelas').value = this.dataset.namaKelas;
            document.getElementById('editTingkat').value = this.dataset.tingkat;
            document.getElementById('editJurusan').value = this.dataset.jurusan;
            document.getElementById('editWaliKelas').value = this.dataset.waliKelas || '';
            document.getElementById('editTahunAjaran').value = this.dataset.tahunAjaran || '';
            document.getElementById('editKapasitas').value = this.dataset.kapasitas || 36;
        });
    });

    // ------------------------------------------------------------
    // 3. SUBMIT EDIT KELAS
    // ------------------------------------------------------------
    document.getElementById('formEditKelas').addEventListener('submit', function (e) {
        e.preventDefault();
        sembunyikanError('errorEditKelas');
        const id = document.getElementById('editKelasId').value;
        const spinner = document.getElementById('spinnerEdit');
        const btn = document.getElementById('btnSubmitEditKelas');
        spinner.classList.remove('d-none');
        btn.disabled = true;

        const data = Object.fromEntries(new FormData(e.target).entries());

        fetch(`/admin/kelas/${id}`, {
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
            if (!res.ok) throw body;
            tutupModal('modalEditKelas');
            tampilkanNotifikasi(`Data kelas "${data.nama_kelas}" berhasil diperbarui.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            const pesan = err.errors
                ? Object.values(err.errors).flat().join(' ')
                : (err.message || 'Terjadi kesalahan saat memperbarui kelas.');
            tampilkanError('errorEditKelas', pesan);
        })
        .finally(() => {
            spinner.classList.add('d-none');
            btn.disabled = false;
        });
    });

    // ------------------------------------------------------------
    // 4. TOGGLE STATUS AKTIF / NONAKTIF
    // ------------------------------------------------------------
    document.querySelectorAll('.btn-toggle-status').forEach((btn) => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const namaKelas = this.dataset.namaKelas;
            const currentActive = this.dataset.isActive === '1';
            const newActive = !currentActive;

            fetch(`/admin/kelas/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ is_active: newActive }),
            })
            .then(async (res) => {
                const body = await res.json();
                if (!res.ok) throw body;
                tampilkanNotifikasi(body.message || `Status kelas "${namaKelas}" berhasil diperbarui.`, 'success');
                reloadHalaman(600);
            })
            .catch((err) => {
                tampilkanNotifikasi(err.message || 'Gagal mengubah status kelas.', 'danger');
            });
        });
    });

    // ------------------------------------------------------------
    // 5. BUKA MODAL HAPUS KELAS
    // ------------------------------------------------------------
    let idKelasAkanDihapus = null;
    let namaKelasAkanDihapus = null;

    document.querySelectorAll('.btn-hapus-kelas').forEach((btn) => {
        btn.addEventListener('click', function () {
            sembunyikanError('errorHapusKelas');
            idKelasAkanDihapus = this.dataset.id;
            namaKelasAkanDihapus = this.dataset.namaKelas;
            const siswaCount = parseInt(this.dataset.siswaCount || 0);

            document.getElementById('hapusKelasNama').textContent = namaKelasAkanDihapus;

            if (siswaCount > 0) {
                tampilkanError('errorHapusKelas', `Peringatan: Terdapat ${siswaCount} siswa yang saat ini terdaftar di kelas ini. Anda tidak dapat menghapus kelas ini.`);
                document.getElementById('btnKonfirmasiHapusKelas').disabled = true;
            } else {
                document.getElementById('btnKonfirmasiHapusKelas').disabled = false;
            }
        });
    });

    // ------------------------------------------------------------
    // 6. KONFIRMASI HAPUS KELAS
    // ------------------------------------------------------------
    document.getElementById('btnKonfirmasiHapusKelas').addEventListener('click', function () {
        if (!idKelasAkanDihapus) return;

        const spinner = document.getElementById('spinnerHapus');
        spinner.classList.remove('d-none');
        this.disabled = true;

        fetch(`/admin/kelas/${idKelasAkanDihapus}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(async (res) => {
            const body = await res.json();
            if (!res.ok) throw body;
            tutupModal('modalHapusKelas');
            tampilkanNotifikasi(body.message || `Kelas "${namaKelasAkanDihapus}" berhasil dihapus.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            tampilkanError('errorHapusKelas', err.message || 'Terjadi kesalahan saat menghapus kelas.');
        })
        .finally(() => {
            spinner.classList.add('d-none');
            this.disabled = false;
        });
    });
});
</script>
@endpush

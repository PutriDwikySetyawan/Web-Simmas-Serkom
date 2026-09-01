{{-- Menggunakan template layout utama administrator --}}
@extends('layouts.admin')

{{-- Mengatur judul pada tab browser --}}
@section('title', 'Data Jurusan')

{{-- Mengatur judul pada topbar header --}}
@section('page-title', 'Manajemen Jurusan')

@push('styles')
<style>
    /* =========================================================
       1. STATISTIK KARTU WIDGET JURUSAN
       Mengatur grid counter statistik (Total Jurusan, Jurusan Aktif, dll)
    ========================================================= */
    .jurusan-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .jurusan-stat-card {
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

    .jurusan-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
    }

    .jurusan-stat-card__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .jurusan-stat-card__label {
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--simmas-muted);
    }

    .jurusan-stat-card__icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .jurusan-stat-card__value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--simmas-ink);
        line-height: 1.2;
    }

    .jurusan-stat-card__hint {
        font-size: 0.76rem;
        color: var(--simmas-muted);
        margin-top: 4px;
    }

    /* =========================================================
       PANEL UTAMA (Search, Filter, Tabel)
    ========================================================= */
    .jurusan-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 16px;
        padding: 22px;
    }

    .jurusan-panel .form-control,
    .jurusan-panel .form-select {
        border-color: var(--simmas-border);
        font-size: 0.86rem;
        border-radius: 10px;
    }

    .jurusan-panel .form-control:focus,
    .jurusan-panel .form-select:focus {
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    .btn-jurusan-primary {
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

    .btn-jurusan-primary:hover {
        background: var(--simmas-blue-dark);
        border-color: var(--simmas-blue-dark);
        color: #fff;
    }

    /* =========================================================
       TABEL JURUSAN
    ========================================================= */
    .table-jurusan {
        margin: 0;
    }

    .table-jurusan thead th {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--simmas-muted);
        background: var(--simmas-bg);
        border-bottom: 1px solid var(--simmas-border);
        padding: 12px 16px;
    }

    .table-jurusan tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.86rem;
    }

    .table-jurusan tbody tr:hover td {
        background: #f8fafc;
    }

    .badge-kode-jurusan {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.76rem;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
    }

    .jurusan-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .jurusan-badge-status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .jurusan-badge-status--aktif {
        background: #dcfce7;
        color: #16a34a;
    }

    .jurusan-badge-status--nonaktif {
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
        .jurusan-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .jurusan-stats {
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
    <div class="jurusan-stats">
        {{-- Card: Total Jurusan --}}
        <div class="jurusan-stat-card">
            <div class="jurusan-stat-card__top">
                <span class="jurusan-stat-card__label">Total Jurusan</span>
                <span class="jurusan-stat-card__icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>
            </div>
            <div class="jurusan-stat-card__value">{{ $stats['total_jurusan'] }}</div>
            <div class="jurusan-stat-card__hint">program keahlian terdaftar</div>
        </div>

        {{-- Card: Jurusan Aktif --}}
        <div class="jurusan-stat-card">
            <div class="jurusan-stat-card__top">
                <span class="jurusan-stat-card__label">Jurusan Aktif</span>
                <span class="jurusan-stat-card__icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="bi bi-check-circle-fill"></i>
                </span>
            </div>
            <div class="jurusan-stat-card__value">{{ $stats['jurusan_aktif'] }}</div>
            <div class="jurusan-stat-card__hint">siap digunakan di rombel</div>
        </div>

        {{-- Card: Total Rombel / Kelas --}}
        <div class="jurusan-stat-card">
            <div class="jurusan-stat-card__top">
                <span class="jurusan-stat-card__label">Total Rombel</span>
                <span class="jurusan-stat-card__icon" style="background: #fef3c7; color: #d97706;">
                    <i class="bi bi-easel2-fill"></i>
                </span>
            </div>
            <div class="jurusan-stat-card__value">{{ $stats['total_kelas'] }}</div>
            <div class="jurusan-stat-card__hint">kelas aktif & terdata</div>
        </div>

        {{-- Card: Guru Pengampu --}}
        <div class="jurusan-stat-card">
            <div class="jurusan-stat-card__top">
                <span class="jurusan-stat-card__label">Guru Pengampu</span>
                <span class="jurusan-stat-card__icon" style="background: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-person-badge-fill"></i>
                </span>
            </div>
            <div class="jurusan-stat-card__value">{{ $stats['total_guru'] }}</div>
            <div class="jurusan-stat-card__hint">guru pembimbing produktif</div>
        </div>
    </div>

    {{-- ============================================================
         2. PANEL UTAMA (FILTER & TABEL)
         ============================================================ --}}
    <div class="jurusan-panel">

        {{-- Toolbar Filter & Action --}}
        <form method="GET" action="{{ route('admin.jurusan.index') }}" class="row g-2 align-items-center mb-4">
            {{-- Search input --}}
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control border-start-0"
                           style="border-radius: 0 10px 10px 0;"
                           placeholder="Cari kode jurusan, nama jurusan, kaprog...">
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="col-6 col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="semua">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Tombol Tambah Jurusan --}}
            <div class="col-6 col-md-3 text-end">
                <button type="button" class="btn btn-jurusan-primary w-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Jurusan</span>
                </button>
            </div>
        </form>

        {{-- Tabel Data Jurusan --}}
        <div class="table-responsive">
            <table class="table table-jurusan align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Kode & Nama Program Keahlian</th>
                        <th>Ketua Program Keahlian (Kaprog)</th>
                        <th>Deskripsi Kejuruan</th>
                        <th class="text-center">Rombel / Kelas</th>
                        <th class="text-center">Guru</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jurusanList as $index => $jurusan)
                        <tr>
                            <td class="text-center text-muted fw-bold">
                                {{ $jurusanList->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge-kode-jurusan">{{ $jurusan->kode_jurusan }}</span>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $jurusan->nama_jurusan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 text-dark">
                                    <i class="bi bi-person-badge text-muted me-1"></i>
                                    <span class="fw-semibold">{{ $jurusan->kepala_jurusan ?: '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small" title="{{ $jurusan->deskripsi }}">
                                    {{ Str::limit($jurusan->deskripsi ?: '-', 60) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                                    <i class="bi bi-easel2 text-primary me-1"></i> {{ $jurusan->kelas_count }} Kelas
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                                    <i class="bi bi-person text-success me-1"></i> {{ $jurusan->guru_count }} Guru
                                </span>
                            </td>
                            <td>
                                <span class="jurusan-badge-status {{ $jurusan->is_active ? 'jurusan-badge-status--aktif' : 'jurusan-badge-status--nonaktif' }}">
                                    {{ $jurusan->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    {{-- Edit Button --}}
                                    <button type="button"
                                            class="btn-action-icon btn-edit-jurusan"
                                            title="Edit Jurusan"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditJurusan"
                                            data-id="{{ $jurusan->id }}"
                                            data-kode-jurusan="{{ $jurusan->kode_jurusan }}"
                                            data-nama-jurusan="{{ $jurusan->nama_jurusan }}"
                                            data-kepala-jurusan="{{ $jurusan->kepala_jurusan }}"
                                            data-deskripsi="{{ $jurusan->deskripsi }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    {{-- Status Toggle Button --}}
                                    <button type="button"
                                            class="btn-action-icon btn-toggle-status-jurusan"
                                            title="{{ $jurusan->is_active ? 'Nonaktifkan Jurusan' : 'Aktifkan Jurusan' }}"
                                            data-id="{{ $jurusan->id }}"
                                            data-kode-jurusan="{{ $jurusan->kode_jurusan }}"
                                            data-is-active="{{ $jurusan->is_active ? '1' : '0' }}">
                                        <i class="bi {{ $jurusan->is_active ? 'bi-toggle-on text-success fs-5' : 'bi-toggle-off text-muted fs-5' }}"></i>
                                    </button>

                                    {{-- Hapus Button --}}
                                    <button type="button"
                                            class="btn-action-icon text-danger btn-hapus-jurusan"
                                            title="Hapus Jurusan"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalHapusJurusan"
                                            data-id="{{ $jurusan->id }}"
                                            data-kode-jurusan="{{ $jurusan->kode_jurusan }}"
                                            data-kelas-count="{{ $jurusan->kelas_count }}"
                                            data-guru-count="{{ $jurusan->guru_count }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted mb-2" style="font-size: 2.5rem;">
                                    <i class="bi bi-mortarboard"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Belum ada data jurusan</h6>
                                <p class="text-muted small mb-3">Tidak ditemukan data jurusan yang sesuai dengan kriteria pencarian.</p>
                                <button type="button" class="btn btn-sm btn-jurusan-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Jurusan Baru
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($jurusanList->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $jurusanList->firstItem() }}</strong> - <strong>{{ $jurusanList->lastItem() }}</strong> dari <strong>{{ $jurusanList->total() }}</strong> jurusan
                </div>
                <div>
                    {{ $jurusanList->links() }}
                </div>
            </div>
        @endif

    </div>

</div>

{{-- ============================================================
     3. MODAL TAMBAH JURUSAN
     ============================================================ --}}
<div class="modal fade" id="modalTambahJurusan" tabindex="-1" aria-labelledby="modalTambahJurusanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold" id="modalTambahJurusanLabel">
                    <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Program Keahlian / Jurusan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahJurusan">
                <div class="modal-body px-4 py-3">
                    <div id="errorTambahJurusan" class="alert alert-danger d-none py-2 small mb-3"></div>

                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-bold text-muted">Kode Singkatan <span class="text-danger">*</span></label>
                            <input type="text" name="kode_jurusan" class="form-control text-uppercase" placeholder="PPLG" required>
                        </div>
                        <div class="col-8">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap Jurusan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jurusan" class="form-control" placeholder="Pengembangan Perangkat Lunak dan Gim" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Ketua Program Keahlian (Kaprog)</label>
                        <input type="text" name="kepala_jurusan" class="form-control" placeholder="Nama Guru Kaprog" list="listGuruJurusan">
                        <datalist id="listGuruJurusan">
                            @foreach ($guruList as $guru)
                                <option value="{{ $guru->profile->nama ?? '' }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Deskripsi Kejuruan</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat mengenai program keahlian ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" id="btnSubmitTambahJurusan">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerTambahJurusan"></span>
                        Simpan Jurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     4. MODAL EDIT JURUSAN
     ============================================================ --}}
<div class="modal fade" id="modalEditJurusan" tabindex="-1" aria-labelledby="modalEditJurusanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold" id="modalEditJurusanLabel">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Edit Program Keahlian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditJurusan">
                <input type="hidden" id="editJurusanId">
                <div class="modal-body px-4 py-3">
                    <div id="errorEditJurusan" class="alert alert-danger d-none py-2 small mb-3"></div>

                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-bold text-muted">Kode Singkatan <span class="text-danger">*</span></label>
                            <input type="text" id="editKodeJurusan" name="kode_jurusan" class="form-control text-uppercase" required>
                        </div>
                        <div class="col-8">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap Jurusan <span class="text-danger">*</span></label>
                            <input type="text" id="editNamaJurusan" name="nama_jurusan" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Ketua Program Keahlian (Kaprog)</label>
                        <input type="text" id="editKepalaJurusan" name="kepala_jurusan" class="form-control" list="listGuruJurusan">
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Deskripsi Kejuruan</label>
                        <textarea id="editDeskripsi" name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold" id="btnSubmitEditJurusan">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerEditJurusan"></span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     5. MODAL HAPUS JURUSAN
     ============================================================ --}}
<div class="modal fade" id="modalHapusJurusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3" style="font-size: 2.5rem;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
                <h6 class="fw-bold mb-2">Hapus Program Keahlian?</h6>
                <p class="text-muted small mb-3">
                    Apakah Anda yakin ingin menghapus jurusan <strong id="hapusJurusanKode" class="text-dark"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div id="errorHapusJurusan" class="alert alert-danger d-none py-2 small mb-3 text-start"></div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm flex-fill fw-bold" id="btnKonfirmasiHapusJurusan">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="spinnerHapusJurusan"></span>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast container jika belum tersedia secara global --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;" id="jurusanToastWrap"></div>

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

        document.getElementById('jurusanToastWrap').appendChild(toastEl);
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
    // 1. TAMBAH JURUSAN
    // ------------------------------------------------------------
    document.getElementById('formTambahJurusan').addEventListener('submit', function (e) {
        e.preventDefault();
        sembunyikanError('errorTambahJurusan');
        const spinner = document.getElementById('spinnerTambahJurusan');
        const btn = document.getElementById('btnSubmitTambahJurusan');
        spinner.classList.remove('d-none');
        btn.disabled = true;

        const data = Object.fromEntries(new FormData(e.target).entries());

        fetch(`{{ route('admin.jurusan.store') }}`, {
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
            tutupModal('modalTambahJurusan');
            tampilkanNotifikasi(`Jurusan "${data.kode_jurusan}" berhasil ditambahkan.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            const pesan = err.errors
                ? Object.values(err.errors).flat().join(' ')
                : (err.message || 'Terjadi kesalahan saat menambahkan jurusan.');
            tampilkanError('errorTambahJurusan', pesan);
        })
        .finally(() => {
            spinner.classList.add('d-none');
            btn.disabled = false;
        });
    });

    // ------------------------------------------------------------
    // 2. BUKA MODAL EDIT JURUSAN
    // ------------------------------------------------------------
    document.querySelectorAll('.btn-edit-jurusan').forEach((btn) => {
        btn.addEventListener('click', function () {
            sembunyikanError('errorEditJurusan');
            document.getElementById('editJurusanId').value = this.dataset.id;
            document.getElementById('editKodeJurusan').value = this.dataset.kodeJurusan;
            document.getElementById('editNamaJurusan').value = this.dataset.namaJurusan;
            document.getElementById('editKepalaJurusan').value = this.dataset.kepalaJurusan || '';
            document.getElementById('editDeskripsi').value = this.dataset.deskripsi || '';
        });
    });

    // ------------------------------------------------------------
    // 3. SUBMIT EDIT JURUSAN
    // ------------------------------------------------------------
    document.getElementById('formEditJurusan').addEventListener('submit', function (e) {
        e.preventDefault();
        sembunyikanError('errorEditJurusan');
        const id = document.getElementById('editJurusanId').value;
        const spinner = document.getElementById('spinnerEditJurusan');
        const btn = document.getElementById('btnSubmitEditJurusan');
        spinner.classList.remove('d-none');
        btn.disabled = true;

        const data = Object.fromEntries(new FormData(e.target).entries());

        fetch(`/admin/jurusan/${id}`, {
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
            tutupModal('modalEditJurusan');
            tampilkanNotifikasi(`Data jurusan "${data.kode_jurusan}" berhasil diperbarui.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            const pesan = err.errors
                ? Object.values(err.errors).flat().join(' ')
                : (err.message || 'Terjadi kesalahan saat memperbarui jurusan.');
            tampilkanError('errorEditJurusan', pesan);
        })
        .finally(() => {
            spinner.classList.add('d-none');
            btn.disabled = false;
        });
    });

    // ------------------------------------------------------------
    // 4. TOGGLE STATUS AKTIF / NONAKTIF
    // ------------------------------------------------------------
    document.querySelectorAll('.btn-toggle-status-jurusan').forEach((btn) => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const kodeJurusan = this.dataset.kodeJurusan;
            const currentActive = this.dataset.isActive === '1';
            const newActive = !currentActive;

            fetch(`/admin/jurusan/${id}/status`, {
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
                tampilkanNotifikasi(body.message || `Status jurusan "${kodeJurusan}" berhasil diperbarui.`, 'success');
                reloadHalaman(600);
            })
            .catch((err) => {
                tampilkanNotifikasi(err.message || 'Gagal mengubah status jurusan.', 'danger');
            });
        });
    });

    // ------------------------------------------------------------
    // 5. BUKA MODAL HAPUS JURUSAN
    // ------------------------------------------------------------
    let idJurusanAkanDihapus = null;
    let kodeJurusanAkanDihapus = null;

    document.querySelectorAll('.btn-hapus-jurusan').forEach((btn) => {
        btn.addEventListener('click', function () {
            sembunyikanError('errorHapusJurusan');
            idJurusanAkanDihapus = this.dataset.id;
            kodeJurusanAkanDihapus = this.dataset.kodeJurusan;
            const kelasCount = parseInt(this.dataset.kelasCount || 0);
            const guruCount = parseInt(this.dataset.guruCount || 0);

            document.getElementById('hapusJurusanKode').textContent = kodeJurusanAkanDihapus;

            if (kelasCount > 0 || guruCount > 0) {
                const rincian = [];
                if (kelasCount > 0) rincian.push(`${kelasCount} kelas`);
                if (guruCount > 0) rincian.push(`${guruCount} guru`);
                tampilkanError('errorHapusJurusan', `Peringatan: Terdapat ${rincian.join(' dan ')} yang terhubung dengan jurusan ini. Anda tidak dapat menghapus jurusan ini.`);
                document.getElementById('btnKonfirmasiHapusJurusan').disabled = true;
            } else {
                document.getElementById('btnKonfirmasiHapusJurusan').disabled = false;
            }
        });
    });

    // ------------------------------------------------------------
    // 6. KONFIRMASI HAPUS JURUSAN
    // ------------------------------------------------------------
    document.getElementById('btnKonfirmasiHapusJurusan').addEventListener('click', function () {
        if (!idJurusanAkanDihapus) return;

        const spinner = document.getElementById('spinnerHapusJurusan');
        spinner.classList.remove('d-none');
        this.disabled = true;

        fetch(`/admin/jurusan/${idJurusanAkanDihapus}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(async (res) => {
            const body = await res.json();
            if (!res.ok) throw body;
            tutupModal('modalHapusJurusan');
            tampilkanNotifikasi(body.message || `Jurusan "${kodeJurusanAkanDihapus}" berhasil dihapus.`, 'success');
            reloadHalaman();
        })
        .catch((err) => {
            tampilkanError('errorHapusJurusan', err.message || 'Terjadi kesalahan saat menghapus jurusan.');
        })
        .finally(() => {
            spinner.classList.add('d-none');
            this.disabled = false;
        });
    });
});
</script>
@endpush

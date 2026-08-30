@extends('layouts.admin')

@section('title', 'Data Guru')
@section('page-title', 'Manajemen Guru')

@push('styles')
<style>
    /* =========================================================
       STAT CARD RINGKAS
    ========================================================= */

    .guru-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;

        margin-bottom: 20px;
    }

    .guru-stat-card {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        padding: 16px 18px;
    }

    .guru-stat-card__label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        margin-bottom: 6px;
    }

    .guru-stat-card__value {
        font-size: 1.4rem;
        font-weight: 800;
    }

    .guru-stat-card__hint {
        font-size: 0.72rem;
        color: var(--simmas-muted);
    }

    /* =========================================================
       PANEL UTAMA
    ========================================================= */

    .guru-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        padding: 20px;
    }

    /* ---------------------------------------------------
       TOOLBAR: SEARCH + FILTER + TOMBOL TAMBAH
    --------------------------------------------------- */

    .guru-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 16px;
    }

    .guru-toolbar__search {
        flex: 1;

        display: flex;
        align-items: center;
        gap: 8px;

        padding: 8px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        color: var(--simmas-muted);
    }

    .guru-toolbar__search input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 0.86rem;
    }

    .guru-toolbar__select {
        padding: 8px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        font-size: 0.86rem;
        background: #fff;
    }

    .guru-toolbar__count {
        font-size: 0.78rem;
        color: var(--simmas-muted);
        white-space: nowrap;
    }

    .guru-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 9px 16px;

        border: none;
        border-radius: 8px;

        background: var(--simmas-blue);
        color: #fff;

        font-size: 0.82rem;
        font-weight: 700;

        white-space: nowrap;
        cursor: pointer;
    }

    .guru-btn-primary:hover {
        background: var(--simmas-blue-dark);
    }

    /* ---------------------------------------------------
       TABEL DATA GURU
    --------------------------------------------------- */

    .guru-table {
        width: 100%;
        border-collapse: collapse;
    }

    .guru-table th {
        text-align: left;

        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        padding: 10px 8px;

        border-bottom: 1px solid var(--simmas-border);
        white-space: nowrap;
    }

    .guru-table td {
        padding: 12px 8px;
        font-size: 0.84rem;
        vertical-align: middle;

        border-bottom: 1px solid var(--simmas-border);
    }

    .guru-table tr:last-child td {
        border-bottom: none;
    }

    .guru-name-cell strong {
        display: block;
        font-size: 0.85rem;
    }

    .guru-name-cell span {
        font-size: 0.75rem;
        color: var(--simmas-muted);
    }

    .guru-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        padding: 3px 10px;
        border-radius: 999px;

        font-size: 0.72rem;
        font-weight: 700;
    }

    .guru-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .guru-badge--aktif {
        background: #dcfce7;
        color: #16a34a;
    }

    .guru-badge--nonaktif {
        background: #f1f5f9;
        color: #64748b;
    }

    /* ---------------------------------------------------
       DROPDOWN AKSI (Edit / Ubah Status / Hapus)
    --------------------------------------------------- */

    .guru-aksi {
        position: relative;
    }

    .guru-aksi__toggle {
        width: 28px;
        height: 28px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        background: #fff;
        cursor: pointer;
    }

    .guru-aksi__menu {
        position: absolute;
        right: 0;
        top: 34px;

        width: 160px;

        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);

        display: none;
        flex-direction: column;

        padding: 6px;

        z-index: 30;
    }

    .guru-aksi__menu.is-open {
        display: flex;
    }

    .guru-aksi__item {
        display: flex;
        align-items: center;
        gap: 8px;

        padding: 8px 10px;

        border: none;
        background: none;

        font-size: 0.8rem;
        text-align: left;

        border-radius: 6px;
        cursor: pointer;
    }

    .guru-aksi__item:hover {
        background: var(--simmas-bg);
    }

    .guru-aksi__item.is-danger {
        color: #dc2626;
    }

    /* ---------------------------------------------------
       EMPTY STATE
    --------------------------------------------------- */

    .guru-empty {
        text-align: center;
        padding: 40px 0;
        color: var(--simmas-muted);
        font-size: 0.86rem;
    }

    .guru-empty i {
        font-size: 1.8rem;
        display: block;
        margin-bottom: 8px;
    }

    /* =========================================================
       MODAL (SHARED STYLE — dipakai semua modal di halaman ini)
    ========================================================= */

    .guru-modal-overlay {
        position: fixed;
        inset: 0;

        background: rgba(15, 23, 42, 0.55);

        display: none;
        align-items: center;
        justify-content: center;

        z-index: 1000;
        padding: 16px;
    }

    .guru-modal-overlay.is-active {
        display: flex;
    }

    .guru-modal {
        width: 100%;
        max-width: 420px;

        background: #fff;
        border-radius: 16px;

        padding: 22px;
    }

    .guru-modal__header {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 18px;
    }

    .guru-modal__header-icon {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: var(--simmas-blue-light);
        color: var(--simmas-blue);
    }

    .guru-modal__title {
        font-size: 0.96rem;
        font-weight: 700;
    }

    .guru-modal__subtitle {
        font-size: 0.75rem;
        color: var(--simmas-muted);
    }

    .guru-modal__field {
        margin-bottom: 14px;
    }

    .guru-modal__field label {
        display: block;

        font-size: 0.78rem;
        font-weight: 600;

        margin-bottom: 6px;
    }

    .guru-modal__field input,
    .guru-modal__field select {
        width: 100%;

        padding: 9px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        font-size: 0.86rem;
    }

    .guru-modal__field input:focus,
    .guru-modal__field select:focus {
        outline: none;
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    .guru-modal__field input[readonly] {
        background: var(--simmas-bg);
        color: var(--simmas-muted);
    }

    .guru-modal__error {
        font-size: 0.74rem;
        color: #dc2626;
        margin-top: 4px;
        display: none;
    }

    .guru-modal__footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;

        margin-top: 18px;
    }

    .guru-modal__btn {
        padding: 9px 16px;

        border-radius: 8px;
        border: none;

        font-size: 0.82rem;
        font-weight: 700;

        cursor: pointer;
    }

    .guru-modal__btn--secondary {
        background: var(--simmas-bg);
        color: var(--simmas-ink);
    }

    .guru-modal__btn--primary {
        background: var(--simmas-blue);
        color: #fff;
    }

    .guru-modal__btn--danger {
        background: #dc2626;
        color: #fff;
    }

    /* ---------------------------------------------------
       MODAL KREDENSIAL (khusus, ada tombol copy)
    --------------------------------------------------- */

    .guru-modal__cred-icon-wrap {
        text-align: center;
        margin-bottom: 10px;
    }

    .guru-modal__cred-icon {
        width: 46px;
        height: 46px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #dcfce7;
        color: #16a34a;

        font-size: 1.2rem;
    }

    .guru-modal__cred-title {
        text-align: center;
        font-weight: 700;
        font-size: 0.95rem;

        margin-bottom: 4px;
    }

    .guru-modal__cred-subtitle {
        text-align: center;
        font-size: 0.76rem;
        color: var(--simmas-muted);

        margin-bottom: 18px;
    }

    .guru-modal__cred-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;

        padding: 10px 12px;

        background: var(--simmas-bg);
        border-radius: 8px;

        margin-bottom: 10px;
    }

    .guru-modal__cred-row div {
        overflow: hidden;
    }

    .guru-modal__cred-label {
        font-size: 0.68rem;
        color: var(--simmas-muted);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .guru-modal__cred-value {
        font-size: 0.85rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .guru-modal__cred-copy {
        border: none;
        background: none;
        color: var(--simmas-blue);
        cursor: pointer;
        flex-shrink: 0;
    }

    /* Alert box sukses/gagal sederhana (toast ringan di pojok) */
    .guru-toast {
        position: fixed;
        bottom: 20px;
        right: 20px;

        padding: 12px 18px;
        border-radius: 10px;

        background: var(--simmas-ink);
        color: #fff;

        font-size: 0.82rem;
        font-weight: 600;

        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.2);

        z-index: 2000;

        display: none;
    }

    .guru-toast.is-active {
        display: block;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {
        .guru-stats {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .guru-stats {
            grid-template-columns: 1fr;
        }

        .guru-toolbar {
            flex-wrap: wrap;
        }
    }
</style>
@endpush


@section('content')

{{-- =====================================================
     1. STAT CARD RINGKAS
     Dihitung langsung dari model karena controller saat ini
     hanya mengirim data guru terpaginasi. Untuk performa lebih
     baik di masa depan, sebaiknya nilai ini dipindah ke
     GuruController::index() sebagai variabel terpisah.
====================================================== --}}

@php
    $totalGuruKeseluruhan = \App\Models\Guru::count();
    $guruAktifKeseluruhan = \App\Models\Guru::where('is_active', true)->count();
    $totalBimbinganAktif  = \App\Models\PenempatanMagang::where('status_pengesahan', '!=', 'lulus_magang')->count();
    $rataRataBimbingan    = $totalGuruKeseluruhan > 0
        ? round($totalBimbinganAktif / $totalGuruKeseluruhan, 1)
        : 0;
@endphp

<div class="guru-stats">

    <div class="guru-stat-card">
        <p class="guru-stat-card__label">Total Guru Pembimbing</p>
        <p class="guru-stat-card__value">{{ $totalGuruKeseluruhan }}</p>
        <p class="guru-stat-card__hint">Guru terdaftar</p>
    </div>

    <div class="guru-stat-card">
        <p class="guru-stat-card__label">Guru Aktif</p>
        <p class="guru-stat-card__value">{{ $guruAktifKeseluruhan }}</p>
        <p class="guru-stat-card__hint">Siap membimbing</p>
    </div>

    <div class="guru-stat-card">
        <p class="guru-stat-card__label">Rata-Rata Bimbingan</p>
        <p class="guru-stat-card__value">{{ $rataRataBimbingan }}</p>
        <p class="guru-stat-card__hint">Siswa per guru</p>
    </div>

</div>


{{-- =====================================================
     2. PANEL UTAMA: TOOLBAR + TABEL
====================================================== --}}

<div class="guru-panel">

    {{-- ---------------------------------------------------
         2a. TOOLBAR: SEARCH + FILTER JURUSAN + TOMBOL TAMBAH
    --------------------------------------------------- --}}

    <form method="GET" action="{{ route('admin.guru.index') }}" class="guru-toolbar">

        <div class="guru-toolbar__search">
            <i class="bi bi-search"></i>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau NIP..."
                onchange="this.form.submit()"
            >
        </div>

        <select name="jurusan" class="guru-toolbar__select" onchange="this.form.submit()">
            <option value="semua" {{ request('jurusan', 'semua') === 'semua' ? 'selected' : '' }}>
                Semua Jurusan
            </option>
            <option value="RPL" {{ request('jurusan') === 'RPL' ? 'selected' : '' }}>RPL</option>
            <option value="TKJ" {{ request('jurusan') === 'TKJ' ? 'selected' : '' }}>TKJ</option>
            <option value="MM" {{ request('jurusan') === 'MM' ? 'selected' : '' }}>Multimedia</option>
        </select>

        <span class="guru-toolbar__count">
            {{ $guruList->total() }} Guru
        </span>

        {{-- Tombol Tambah Guru, membuka modal via JS --}}
        <button type="button" class="guru-btn-primary" onclick="bukaModalTambahGuru()">
            <i class="bi bi-plus-lg"></i>
            Tambah Guru
        </button>

    </form>


    {{-- ---------------------------------------------------
         2b. TABEL DATA GURU
    --------------------------------------------------- --}}

    @if ($guruList->count() > 0)

        <table class="guru-table">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama Lengkap & Email</th>
                    <th>Jurusan</th>
                    <th>Bimbingan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($guruList as $guru)
                    <tr>
                        <td>{{ $guru->nip }}</td>

                        <td>
                            <div class="guru-name-cell">
                                <strong>{{ $guru->profile->nama ?? '-' }}</strong>
                                <span>{{ $guru->profile->email ?? '-' }}</span>
                            </div>
                        </td>

                        <td>{{ $guru->jurusan }}</td>

                        <td>{{ $guru->penempatan_count }}</td>

                        <td>
                            <span class="guru-badge {{ $guru->is_active ? 'guru-badge--aktif' : 'guru-badge--nonaktif' }}">
                                {{ $guru->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>

                        {{-- Dropdown Aksi: Edit / Ubah Status / Hapus --}}
                        <td>
                            <div class="guru-aksi">

                                <button type="button" class="guru-aksi__toggle" onclick="toggleAksiMenu(this)">
                                    <i class="bi bi-three-dots"></i>
                                </button>

                                <div class="guru-aksi__menu">

                                    <button
                                        type="button"
                                        class="guru-aksi__item"
                                        onclick="bukaModalEditGuru(
                                            '{{ $guru->id }}',
                                            '{{ $guru->profile->nama }}',
                                            '{{ $guru->nip }}',
                                            '{{ $guru->jurusan }}'
                                        )"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        class="guru-aksi__item"
                                        onclick="bukaModalStatusGuru('{{ $guru->id }}', {{ $guru->is_active ? 'true' : 'false' }})"
                                    >
                                        <i class="bi bi-arrow-repeat"></i>
                                        Ubah Status
                                    </button>

                                    <button
                                        type="button"
                                        class="guru-aksi__item is-danger"
                                        onclick="bukaAlertHapusGuru('{{ $guru->id }}', '{{ $guru->profile->nama }}')"
                                    >
                                        <i class="bi bi-trash"></i>
                                        Hapus
                                    </button>

                                </div>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $guruList->links() }}
        </div>

    @else

        {{-- ---------------------------------------------------
             2c. EMPTY STATE
        --------------------------------------------------- --}}

        <div class="guru-empty">
            <i class="bi bi-inbox"></i>
            Tidak ada data guru yang cocok dengan kriteria pencarian.
        </div>

    @endif

</div>


{{-- =====================================================
     3. MODAL: FORM TAMBAH GURU PEMBIMBING
====================================================== --}}

<div class="guru-modal-overlay" id="modalTambahGuru">
    <div class="guru-modal">

        <div class="guru-modal__header">
            <span class="guru-modal__header-icon">
                <i class="bi bi-person-plus"></i>
            </span>

            <div>
                <p class="guru-modal__title">Tambah Guru Pembimbing</p>
                <p class="guru-modal__subtitle">Akun login otomatis dibuat dan email sekolah.</p>
            </div>
        </div>

        <form id="formTambahGuru">
            @csrf

            <div class="guru-modal__field">
                <label for="tambah_nama">Nama Lengkap & Gelar</label>
                <input type="text" id="tambah_nama" name="nama" placeholder="Drs. Ahmad Fauzi, M.Kom." required>
                <p class="guru-modal__error" data-error-for="nama"></p>
            </div>

            <div class="guru-modal__field">
                <label for="tambah_nip">NIP (Nomor Induk Pegawai)</label>
                <input type="text" id="tambah_nip" name="nip" maxlength="18" placeholder="18 digit angka" required>
                <p class="guru-modal__error" data-error-for="nip"></p>
            </div>

            <div class="guru-modal__field">
                <label for="tambah_jurusan">Kompetensi Keahlian / Jurusan</label>
                <select id="tambah_jurusan" name="jurusan" required>
                    <option value="">Pilih jurusan</option>
                    <option value="Rekayasa Perangkat Lunak (RPL)">Rekayasa Perangkat Lunak (RPL)</option>
                    <option value="Teknik Komputer & Jaringan (TKJ)">Teknik Komputer & Jaringan (TKJ)</option>
                    <option value="Multimedia (MM)">Multimedia (MM)</option>
                </select>
                <p class="guru-modal__error" data-error-for="jurusan"></p>
            </div>

            <div class="guru-modal__footer">
                <button type="button" class="guru-modal__btn guru-modal__btn--secondary" onclick="tutupModal('modalTambahGuru')">
                    Batal
                </button>
                <button type="submit" class="guru-modal__btn guru-modal__btn--primary">
                    Simpan Guru
                </button>
            </div>
        </form>

    </div>
</div>


{{-- =====================================================
     4. MODAL: FORM EDIT GURU PEMBIMBING
====================================================== --}}

<div class="guru-modal-overlay" id="modalEditGuru">
    <div class="guru-modal">

        <div class="guru-modal__header">
            <span class="guru-modal__header-icon">
                <i class="bi bi-pencil-square"></i>
            </span>

            <div>
                <p class="guru-modal__title">Edit Data Guru Pembimbing</p>
                <p class="guru-modal__subtitle">Perbarui informasi dasar akun guru terkait.</p>
            </div>
        </div>

        <form id="formEditGuru">
            @csrf
            @method('PUT')

            {{-- ID guru yang sedang diedit, disimpan di hidden input --}}
            <input type="hidden" id="edit_guru_id" name="guru_id">

            <div class="guru-modal__field">
                <label for="edit_nama">Nama Lengkap Beserta Gelar</label>
                <input type="text" id="edit_nama" name="nama" required>
                <p class="guru-modal__error" data-error-for="nama"></p>
            </div>

            <div class="guru-modal__field">
                <label for="edit_nip">NIP (Nomor Induk Pegawai)</label>
                {{-- NIP bersifat read-only sesuai spesifikasi --}}
                <input type="text" id="edit_nip" name="nip" readonly>
            </div>

            <div class="guru-modal__field">
                <label for="edit_jurusan">Jurusan / Kompetensi Keahlian</label>
                <select id="edit_jurusan" name="jurusan" required>
                    <option value="Rekayasa Perangkat Lunak (RPL)">Rekayasa Perangkat Lunak (RPL)</option>
                    <option value="Teknik Komputer & Jaringan (TKJ)">Teknik Komputer & Jaringan (TKJ)</option>
                    <option value="Multimedia (MM)">Multimedia (MM)</option>
                </select>
                <p class="guru-modal__error" data-error-for="jurusan"></p>
            </div>

            <div class="guru-modal__footer">
                <button type="button" class="guru-modal__btn guru-modal__btn--secondary" onclick="tutupModal('modalEditGuru')">
                    Batal
                </button>
                <button type="submit" class="guru-modal__btn guru-modal__btn--primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>


{{-- =====================================================
     5. MODAL: KREDENSIAL AKUN GURU BARU
     Ditampilkan otomatis setelah "Tambah Guru" berhasil.
====================================================== --}}

<div class="guru-modal-overlay" id="modalKredensialGuru">
    <div class="guru-modal">

        <div class="guru-modal__cred-icon-wrap">
            <span class="guru-modal__cred-icon">
                <i class="bi bi-key-fill"></i>
            </span>
        </div>

        <p class="guru-modal__cred-title">Akun Guru Berhasil Dibuat</p>
        <p class="guru-modal__cred-subtitle">
            Catat kredensial ini dan sampaikan ke guru yang bersangkutan secara aman.
        </p>

        <div class="guru-modal__cred-row">
            <div>
                <p class="guru-modal__cred-label">Email Akun</p>
                <p class="guru-modal__cred-value" id="credEmail">-</p>
            </div>
            <button type="button" class="guru-modal__cred-copy" onclick="copyToClipboard('credEmail')">
                <i class="bi bi-clipboard"></i>
            </button>
        </div>

        <div class="guru-modal__cred-row">
            <div>
                <p class="guru-modal__cred-label">Password Sementara</p>
                <p class="guru-modal__cred-value" id="credPassword">-</p>
            </div>
            <button type="button" class="guru-modal__cred-copy" onclick="copyToClipboard('credPassword')">
                <i class="bi bi-clipboard"></i>
            </button>
        </div>

        <div class="guru-modal__footer" style="margin-top: 16px;">
            <button type="button" class="guru-modal__btn guru-modal__btn--primary" style="width: 100%;" onclick="selesaiKredensial()">
                Selesai
            </button>
        </div>

    </div>
</div>


{{-- =====================================================
     6. MODAL: UBAH STATUS GURU
====================================================== --}}

<div class="guru-modal-overlay" id="modalStatusGuru">
    <div class="guru-modal">

        <div class="guru-modal__header">
            <span class="guru-modal__header-icon">
                <i class="bi bi-arrow-repeat"></i>
            </span>

            <div>
                <p class="guru-modal__title">Ubah Status Guru</p>
                <p class="guru-modal__subtitle">Perbarui status aktif untuk 1 guru.</p>
            </div>
        </div>

        <form id="formStatusGuru">
            @csrf
            @method('PATCH')

            <input type="hidden" id="status_guru_id" name="guru_id">

            <div class="guru-modal__field">
                <label for="status_is_active">Status Akun</label>
                <select id="status_is_active" name="is_active" required>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="guru-modal__footer">
                <button type="button" class="guru-modal__btn guru-modal__btn--secondary" onclick="tutupModal('modalStatusGuru')">
                    Batal
                </button>
                <button type="submit" class="guru-modal__btn guru-modal__btn--primary">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>


{{-- =====================================================
     7. ALERT DIALOG: KONFIRMASI HAPUS DATA GURU
====================================================== --}}

<div class="guru-modal-overlay" id="alertHapusGuru">
    <div class="guru-modal" style="max-width: 380px;">

        <div class="guru-modal__header">
            <span class="guru-modal__header-icon" style="background: #fee2e2; color: #dc2626;">
                <i class="bi bi-trash"></i>
            </span>

            <div>
                <p class="guru-modal__title">Hapus Data Guru Pembimbing?</p>
            </div>
        </div>

        <p style="font-size: 0.84rem; color: var(--simmas-muted); margin-bottom: 18px;">
            Apakah Anda yakin ingin menghapus data
            <strong id="hapusGuruNama">-</strong>?
            Tindakan ini akan menghapus akun login terkait dan tidak dapat dibatalkan.
        </p>

        <div class="guru-modal__footer">
            <button type="button" class="guru-modal__btn guru-modal__btn--secondary" onclick="tutupModal('alertHapusGuru')">
                Batal
            </button>
            <button type="button" class="guru-modal__btn guru-modal__btn--danger" onclick="konfirmasiHapusGuru()">
                Ya, Hapus Guru
            </button>
        </div>

    </div>
</div>


{{-- =====================================================
     8. TOAST NOTIFIKASI SUKSES/GAGAL
====================================================== --}}

<div class="guru-toast" id="guruToast"></div>

@endsection


@push('scripts')
<script>

/* =========================================================
   HELPER UMUM: CSRF TOKEN, TOAST, BUKA/TUTUP MODAL
   ========================================================= */

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

/**
 * Tampilkan notifikasi kecil di pojok kanan bawah selama 3 detik.
 */
function tampilkanToast(pesan) {
    const toast = document.getElementById('guruToast');
    toast.textContent = pesan;
    toast.classList.add('is-active');

    setTimeout(() => {
        toast.classList.remove('is-active');
    }, 3000);
}

/**
 * Buka overlay modal berdasarkan ID elemen.
 */
function bukaModal(modalId) {
    document.getElementById(modalId).classList.add('is-active');
}

/**
 * Tutup overlay modal berdasarkan ID elemen.
 */
function tutupModal(modalId) {
    document.getElementById(modalId).classList.remove('is-active');
}

/**
 * Toggle dropdown menu aksi (Edit/Status/Hapus) di tiap baris tabel.
 * Menutup dropdown lain yang sedang terbuka supaya tidak menumpuk.
 */
function toggleAksiMenu(button) {
    const menu = button.nextElementSibling;
    const semuaMenu = document.querySelectorAll('.guru-aksi__menu');

    semuaMenu.forEach((m) => {
        if (m !== menu) m.classList.remove('is-open');
    });

    menu.classList.toggle('is-open');
}

// Tutup semua dropdown aksi saat klik di luar area dropdown
document.addEventListener('click', function (event) {
    if (!event.target.closest('.guru-aksi')) {
        document.querySelectorAll('.guru-aksi__menu').forEach((m) => m.classList.remove('is-open'));
    }
});

/**
 * Reset semua pesan error validasi di dalam sebuah form modal.
 */
function resetError(formId) {
    document.querySelectorAll(`#${formId} .guru-modal__error`).forEach((el) => {
        el.style.display = 'none';
        el.textContent = '';
    });
}

/**
 * Tampilkan pesan error validasi (422) di bawah masing-masing field.
 */
function tampilkanError(formId, errors) {
    Object.keys(errors).forEach((field) => {
        const errorEl = document.querySelector(`#${formId} [data-error-for="${field}"]`);
        if (errorEl) {
            errorEl.textContent = errors[field][0];
            errorEl.style.display = 'block';
        }
    });
}


/* =========================================================
   3. TAMBAH GURU
   ========================================================= */

function bukaModalTambahGuru() {
    document.getElementById('formTambahGuru').reset();
    resetError('formTambahGuru');
    bukaModal('modalTambahGuru');
}

document.getElementById('formTambahGuru').addEventListener('submit', async function (event) {
    event.preventDefault();
    resetError('formTambahGuru');

    const formData = new FormData(event.target);

    try {
        const response = await fetch("{{ route('admin.guru.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (response.status === 422) {
            tampilkanError('formTambahGuru', result.errors);
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Gagal menambahkan guru.');
        }

        // Tutup modal tambah, langsung tampilkan modal kredensial
        tutupModal('modalTambahGuru');

        document.getElementById('credEmail').textContent = result.kredensial.email;
        document.getElementById('credPassword').textContent = result.kredensial.password;

        bukaModal('modalKredensialGuru');

    } catch (error) {
        tampilkanToast(error.message);
    }
});

/**
 * Tombol "Selesai" di modal kredensial: tutup modal lalu
 * reload halaman supaya tabel menampilkan data guru baru.
 */
function selesaiKredensial() {
    tutupModal('modalKredensialGuru');
    window.location.reload();
}

/**
 * Salin isi elemen (email/password) ke clipboard.
 */
function copyToClipboard(elementId) {
    const text = document.getElementById(elementId).textContent;
    navigator.clipboard.writeText(text);
    tampilkanToast('Berhasil disalin ke clipboard.');
}


/* =========================================================
   4. EDIT GURU
   ========================================================= */

/**
 * Buka modal edit dan pre-fill semua field dengan data guru
 * yang diklik (dikirim langsung dari atribut tombol di tabel).
 */
function bukaModalEditGuru(id, nama, nip, jurusan) {
    resetError('formEditGuru');

    document.getElementById('edit_guru_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_nip').value = nip;
    document.getElementById('edit_jurusan').value = jurusan;

    bukaModal('modalEditGuru');
}

document.getElementById('formEditGuru').addEventListener('submit', async function (event) {
    event.preventDefault();
    resetError('formEditGuru');

    const guruId = document.getElementById('edit_guru_id').value;
    const formData = new FormData(event.target);

    try {
        const response = await fetch(`/admin/guru/${guruId}`, {
            method: 'POST', // dikirim POST + _method PUT (method spoofing Laravel)
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (response.status === 422) {
            tampilkanError('formEditGuru', result.errors);
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Gagal memperbarui data guru.');
        }

        tutupModal('modalEditGuru');
        tampilkanToast(result.message);

        setTimeout(() => window.location.reload(), 800);

    } catch (error) {
        tampilkanToast(error.message);
    }
});


/* =========================================================
   6. UBAH STATUS GURU
   ========================================================= */

function bukaModalStatusGuru(id, isActive) {
    document.getElementById('status_guru_id').value = id;
    document.getElementById('status_is_active').value = isActive ? '1' : '0';
    bukaModal('modalStatusGuru');
}

document.getElementById('formStatusGuru').addEventListener('submit', async function (event) {
    event.preventDefault();

    const guruId = document.getElementById('status_guru_id').value;
    const isActive = document.getElementById('status_is_active').value;

    try {
        const response = await fetch(`/admin/guru/${guruId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ is_active: isActive }),
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal mengubah status guru.');
        }

        tutupModal('modalStatusGuru');
        tampilkanToast(result.message);

        setTimeout(() => window.location.reload(), 800);

    } catch (error) {
        tampilkanToast(error.message);
    }
});


/* =========================================================
   7. HAPUS GURU
   ========================================================= */

// Menyimpan sementara ID guru yang akan dihapus
let guruIdAkanDihapus = null;

function bukaAlertHapusGuru(id, nama) {
    guruIdAkanDihapus = id;
    document.getElementById('hapusGuruNama').textContent = nama;
    bukaModal('alertHapusGuru');
}

async function konfirmasiHapusGuru() {
    if (!guruIdAkanDihapus) return;

    try {
        const response = await fetch(`/admin/guru/${guruIdAkanDihapus}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        // Backend menolak hapus (422) jika guru masih membimbing siswa aktif
        if (!response.ok) {
            throw new Error(result.message || 'Gagal menghapus data guru.');
        }

        tutupModal('alertHapusGuru');
        tampilkanToast(result.message);

        setTimeout(() => window.location.reload(), 800);

    } catch (error) {
        tutupModal('alertHapusGuru');
        tampilkanToast(error.message);
    }
}

</script>
@endpush
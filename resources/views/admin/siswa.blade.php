@extends('layouts.admin')

@section('title', 'Data Siswa')
@section('page-title', 'Manajemen Siswa')

@push('styles')
<style>
    .admin-main .admin-content {
        width: 100%;
        max-width: none;
        margin: 0;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    /* =========================================================
       STAT CARD RINGKAS
    ========================================================= */

    .siswa-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;

        margin-bottom: 20px;
    }

    .siswa-stat-card {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        padding: 16px 18px;
    }

    .siswa-stat-card__label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        margin-bottom: 6px;
    }

    .siswa-stat-card__value {
        font-size: 1.4rem;
        font-weight: 800;
    }

    .siswa-stat-card__hint {
        font-size: 0.72rem;
        color: var(--simmas-muted);
    }

    /* =========================================================
       PANEL UTAMA
    ========================================================= */

    .siswa-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        overflow: hidden;
        padding: 0;
    }

    /* ---------------------------------------------------
       TOOLBAR
    --------------------------------------------------- */

    .siswa-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;

        margin: 0;
        padding: 20px;
    }

    .siswa-toolbar__search {
        flex: 1;
        min-width: 180px;

        display: flex;
        align-items: center;
        gap: 8px;

        padding: 8px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        color: var(--simmas-muted);
    }

    .siswa-toolbar__search input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 0.86rem;
    }

    .siswa-toolbar__select {
        padding: 8px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        font-size: 0.86rem;
        background: #fff;
    }

    .siswa-toolbar__count {
        font-size: 0.78rem;
        color: var(--simmas-muted);
        white-space: nowrap;
    }

    .siswa-btn-primary {
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

    .siswa-btn-primary:hover {
        background: var(--simmas-blue-dark);
    }

    /* ---------------------------------------------------
       TABEL DATA SISWA
    --------------------------------------------------- */

    .siswa-table {
        width: 100%;
        min-width: 960px;
        display: table !important;
        border-collapse: collapse;
    }

    .siswa-table-wrap {
        width: calc(100% - 24px);
        margin: 0 12px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .siswa-pagination { padding: 0 20px 20px; }

    .siswa-table th {
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

    .siswa-table td {
        padding: 12px 8px;
        font-size: 0.84rem;
        vertical-align: middle;

        border-bottom: 1px solid var(--simmas-border);
    }

    .siswa-table tr:last-child td {
        border-bottom: none;
    }

    .siswa-name-cell strong {
        display: block;
        font-size: 0.85rem;
    }

    .siswa-name-cell span {
        font-size: 0.75rem;
        color: var(--simmas-muted);
    }

    .siswa-kelas-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;

        background: var(--simmas-blue-light);
        color: var(--simmas-blue);

        font-size: 0.74rem;
        font-weight: 700;
    }

    .siswa-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        padding: 3px 10px;
        border-radius: 999px;

        font-size: 0.72rem;
        font-weight: 700;
    }

    .siswa-status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .siswa-status-badge--belum   { background: #f1f5f9; color: #64748b; }
    .siswa-status-badge--pengajuan { background: #fef3c7; color: #d97706; }
    .siswa-status-badge--sedang  { background: #dbeafe; color: #2563eb; }
    .siswa-status-badge--lulus   { background: #dcfce7; color: #16a34a; }

    .siswa-industri-cell {
        font-size: 0.8rem;
        color: var(--simmas-muted);
    }

    /* ---------------------------------------------------
       DROPDOWN AKSI
    --------------------------------------------------- */

    .siswa-aksi {
        position: relative;
    }

    .siswa-aksi__toggle {
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

    .siswa-aksi__menu {
        position: absolute;
        right: 0;
        top: 34px;

        width: 180px;

        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 10px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);

        display: none;
        flex-direction: column;

        padding: 6px;

        z-index: 30;
    }

    .siswa-aksi__menu.is-open {
        display: flex;
    }

    .siswa-aksi__item {
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

    .siswa-aksi__item:hover {
        background: var(--simmas-bg);
    }

    .siswa-aksi__item.is-danger {
        color: #dc2626;
    }

    /* ---------------------------------------------------
       EMPTY STATE
    --------------------------------------------------- */

    .siswa-empty {
        text-align: center;
        padding: 40px 0;
        color: var(--simmas-muted);
        font-size: 0.86rem;
    }

    .siswa-empty i {
        font-size: 1.8rem;
        display: block;
        margin-bottom: 8px;
    }

    /* =========================================================
       MODAL (SHARED STYLE)
    ========================================================= */

    .siswa-modal-overlay {
        position: fixed;
        inset: 0;

        background: rgba(15, 23, 42, 0.55);

        display: none;
        align-items: center;
        justify-content: center;

        z-index: 1000;
        padding: 16px;
    }

    .siswa-modal-overlay.is-active {
        display: flex;
    }

    .siswa-modal {
        width: 100%;
        max-width: 420px;

        background: #fff;
        border-radius: 16px;

        padding: 22px;
    }

    .siswa-modal__header {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 18px;
    }

    .siswa-modal__header-icon {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: var(--simmas-blue-light);
        color: var(--simmas-blue);
    }

    .siswa-modal__header-icon.is-purple {
        background: #ede9fe;
        color: #7c3aed;
    }

    .siswa-modal__title {
        font-size: 0.96rem;
        font-weight: 700;
    }

    .siswa-modal__subtitle {
        font-size: 0.75rem;
        color: var(--simmas-muted);
    }

    .siswa-modal__field {
        margin-bottom: 14px;
    }

    .siswa-modal__field label {
        display: block;

        font-size: 0.78rem;
        font-weight: 600;

        margin-bottom: 6px;
    }

    .siswa-modal__field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .siswa-modal__field input,
    .siswa-modal__field select {
        width: 100%;

        padding: 9px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        font-size: 0.86rem;
    }

    .siswa-modal__field input:focus,
    .siswa-modal__field select:focus {
        outline: none;
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    .siswa-modal__field input[readonly] {
        background: var(--simmas-bg);
        color: var(--simmas-muted);
    }

    .siswa-modal__error {
        font-size: 0.74rem;
        color: #dc2626;
        margin-top: 4px;
        display: none;
    }

    .siswa-modal__footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;

        margin-top: 18px;
    }

    .siswa-modal__btn {
        padding: 9px 16px;

        border-radius: 8px;
        border: none;

        font-size: 0.82rem;
        font-weight: 700;

        cursor: pointer;
    }

    .siswa-modal__btn--secondary {
        background: var(--simmas-bg);
        color: var(--simmas-ink);
    }

    .siswa-modal__btn--primary {
        background: var(--simmas-blue);
        color: #fff;
    }

    .siswa-modal__btn--purple {
        background: #7c3aed;
        color: #fff;
    }

    .siswa-modal__btn--danger {
        background: #dc2626;
        color: #fff;
    }

    /* ---------------------------------------------------
       MODAL KREDENSIAL
    --------------------------------------------------- */

    .siswa-modal__cred-icon-wrap {
        text-align: center;
        margin-bottom: 10px;
    }

    .siswa-modal__cred-icon {
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

    .siswa-modal__cred-title {
        text-align: center;
        font-weight: 700;
        font-size: 0.95rem;

        margin-bottom: 4px;
    }

    .siswa-modal__cred-subtitle {
        text-align: center;
        font-size: 0.76rem;
        color: var(--simmas-muted);

        margin-bottom: 18px;
    }

    .siswa-modal__cred-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;

        padding: 10px 12px;

        background: var(--simmas-bg);
        border-radius: 8px;

        margin-bottom: 10px;
    }

    .siswa-modal__cred-row div {
        overflow: hidden;
    }

    .siswa-modal__cred-label {
        font-size: 0.68rem;
        color: var(--simmas-muted);
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .siswa-modal__cred-value {
        font-size: 0.85rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .siswa-modal__cred-copy {
        border: none;
        background: none;
        color: var(--simmas-blue);
        cursor: pointer;
        flex-shrink: 0;
    }

    /* Toast notifikasi */
    .siswa-toast {
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

    .siswa-toast.is-active {
        display: block;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {
        .admin-main .admin-content { padding-left: 20px !important; padding-right: 20px !important; }
        .siswa-stats {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .admin-main .admin-content { padding-left: 12px !important; padding-right: 12px !important; }
        .siswa-stats {
            grid-template-columns: 1fr;
        }

        .siswa-modal__field-row {
            grid-template-columns: 1fr;
        }

        .siswa-table-wrap { width: calc(100% - 16px); margin: 0 8px; }
    }
</style>
@endpush


@section('content')

{{-- =====================================================
     1. STAT CARD RINGKAS
====================================================== --}}

@php
    $totalSiswaKeseluruhan   = \App\Models\Siswa::count();
    $siswaSedangMagang       = \App\Models\Siswa::where('status', 'sedang_magang')->count();
    $siswaBelumMagang        = \App\Models\Siswa::where('status', 'belum_magang')->count();
    $siswaLulusMagang        = \App\Models\Siswa::where('status', 'lulus')->count();
@endphp

<div class="siswa-stats">

    <div class="siswa-stat-card">
        <p class="siswa-stat-card__label">Total Siswa</p>
        <p class="siswa-stat-card__value">{{ $totalSiswaKeseluruhan }}</p>
        <p class="siswa-stat-card__hint">Siswa terdaftar</p>
    </div>

    <div class="siswa-stat-card">
        <p class="siswa-stat-card__label">Sedang Magang</p>
        <p class="siswa-stat-card__value">{{ $siswaSedangMagang }}</p>
        <p class="siswa-stat-card__hint">Aktif di industri</p>
    </div>

    <div class="siswa-stat-card">
        <p class="siswa-stat-card__label">Belum Magang</p>
        <p class="siswa-stat-card__value">{{ $siswaBelumMagang }}</p>
        <p class="siswa-stat-card__hint">Perlu ditempatkan</p>
    </div>

    <div class="siswa-stat-card">
        <p class="siswa-stat-card__label">Lulus Magang</p>
        <p class="siswa-stat-card__value">{{ $siswaLulusMagang }}</p>
        <p class="siswa-stat-card__hint">Selesai program</p>
    </div>

</div>


{{-- =====================================================
     2. PANEL UTAMA: TOOLBAR + TABEL
====================================================== --}}

<div class="siswa-panel">

    {{-- ---------------------------------------------------
         2a. TOOLBAR: SEARCH + FILTER KELAS + FILTER STATUS
    --------------------------------------------------- --}}

    <form method="GET" action="{{ route('admin.siswa.index') }}" class="siswa-toolbar">

        <div class="siswa-toolbar__search">
            <i class="bi bi-search"></i>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, NIS, atau kelas..."
                onchange="this.form.submit()"
            >
        </div>

        <select name="kelas" class="siswa-toolbar__select" onchange="this.form.submit()">
            <option value="semua" {{ request('kelas', 'semua') === 'semua' ? 'selected' : '' }}>Semua Kelas</option>
            @foreach (\App\Models\Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas') as $kelasOpsi)
                <option value="{{ $kelasOpsi }}" {{ request('kelas') === $kelasOpsi ? 'selected' : '' }}>
                    {{ $kelasOpsi }}
                </option>
            @endforeach
        </select>

        <select name="status" class="siswa-toolbar__select" onchange="this.form.submit()">
            <option value="semua" {{ request('status', 'semua') === 'semua' ? 'selected' : '' }}>Semua Status</option>
            <option value="belum_magang" {{ request('status') === 'belum_magang' ? 'selected' : '' }}>Belum Magang</option>
            <option value="pengajuan" {{ request('status') === 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
            <option value="sedang_magang" {{ request('status') === 'sedang_magang' ? 'selected' : '' }}>Sedang Magang</option>
            <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
        </select>

        <span class="siswa-toolbar__count">
            {{ $siswaList->total() }} Siswa
        </span>

        <button type="button" class="siswa-btn-primary" onclick="bukaModalTambahSiswa()">
            <i class="bi bi-plus-lg"></i>
            Tambah Siswa
        </button>

    </form>


    {{-- ---------------------------------------------------
         2b. TABEL DATA SISWA
    --------------------------------------------------- --}}

    @if ($siswaList->count() > 0)

        <div class="siswa-table-wrap">
        <table class="siswa-table">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Siswa & Email</th>
                    <th>Kelas</th>
                    <th>Status Magang</th>
                    <th>Industri</th>
                    <th>Guru Pembimbing</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($siswaList as $siswa)
                    @php
                        $statusBadgeClass = match ($siswa->status) {
                            'belum_magang'  => 'siswa-status-badge--belum',
                            'pengajuan'     => 'siswa-status-badge--pengajuan',
                            'sedang_magang' => 'siswa-status-badge--sedang',
                            'lulus'         => 'siswa-status-badge--lulus',
                            default         => 'siswa-status-badge--belum',
                        };

                        $statusLabel = match ($siswa->status) {
                            'belum_magang'  => 'Belum Magang',
                            'pengajuan'     => 'Pengajuan',
                            'sedang_magang' => 'Sedang Magang',
                            'lulus'         => 'Lulus',
                            default         => '-',
                        };
                    @endphp

                    <tr>
                        <td>{{ $siswa->nis }}</td>

                        <td>
                            <div class="siswa-name-cell">
                                <strong>{{ $siswa->profile->nama ?? '-' }}</strong>
                                <span>{{ $siswa->profile->email ?? '-' }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="siswa-kelas-badge">{{ $siswa->kelas }}</span>
                        </td>

                        <td>
                            <span class="siswa-status-badge {{ $statusBadgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>

                        <td class="siswa-industri-cell">
                            {{ $siswa->penempatan->tempatMagang->nama_perusahaan ?? '-' }}
                        </td>

                        <td class="siswa-industri-cell">
                            {{ $siswa->penempatan->guru->profile->nama ?? '-' }}
                        </td>

                        {{-- Dropdown Aksi --}}
                        <td>
                            <div class="siswa-aksi">

                                <button type="button" class="siswa-aksi__toggle" onclick="toggleAksiMenu(this)">
                                    <i class="bi bi-three-dots"></i>
                                </button>

                                <div class="siswa-aksi__menu">

                                    <button
                                        type="button"
                                        class="siswa-aksi__item"
                                        onclick="bukaModalEditSiswa(
                                            '{{ $siswa->id }}',
                                            '{{ $siswa->profile->nama }}',
                                            '{{ $siswa->kelas }}'
                                        )"
                                    >
                                        <i class="bi bi-pencil"></i>
                                        Edit
                                    </button>

                                    {{-- Plotting hanya relevan untuk siswa yang belum ditempatkan --}}
                                    @if ($siswa->status === 'belum_magang')
                                        <button
                                            type="button"
                                            class="siswa-aksi__item"
                                            onclick="bukaModalPlotting('{{ $siswa->id }}', '{{ $siswa->profile->nama }}')"
                                        >
                                            <i class="bi bi-diagram-3"></i>
                                            Plot Pembimbing
                                        </button>
                                    @endif

                                    <button
                                        type="button"
                                        class="siswa-aksi__item"
                                        onclick="bukaModalStatusSiswa('{{ $siswa->id }}', {{ $siswa->is_active ? 'true' : 'false' }})"
                                    >
                                        <i class="bi bi-arrow-repeat"></i>
                                        Ubah Status
                                    </button>

                                    <button
                                        type="button"
                                        class="siswa-aksi__item is-danger"
                                        onclick="bukaAlertHapusSiswa('{{ $siswa->id }}', '{{ $siswa->profile->nama }}', '{{ $siswa->nis }}')"
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
        </div>

        <div class="siswa-pagination mt-3">
            {{ $siswaList->links() }}
        </div>

    @else

        <div class="siswa-empty">
            <i class="bi bi-inbox"></i>
            Tidak ada data siswa yang cocok dengan kriteria pencarian.
        </div>

    @endif

</div>


{{-- =====================================================
     3. MODAL: FORM TAMBAH SISWA BARU
====================================================== --}}

<div class="siswa-modal-overlay" id="modalTambahSiswa">
    <div class="siswa-modal">

        <div class="siswa-modal__header">
            <span class="siswa-modal__header-icon">
                <i class="bi bi-person-plus"></i>
            </span>

            <div>
                <p class="siswa-modal__title">Tambah Siswa Baru</p>
                <p class="siswa-modal__subtitle">Akun login siswa akan dibuat otomatis dengan password default.</p>
            </div>
        </div>

        <form id="formTambahSiswa">
            @csrf

            <div class="siswa-modal__field">
                <label for="tambah_nama">Nama Lengkap</label>
                <input type="text" id="tambah_nama" name="nama" placeholder="Nama lengkap siswa" required>
                <p class="siswa-modal__error" data-error-for="nama"></p>
            </div>

            <div class="siswa-modal__field-row">
                <div class="siswa-modal__field">
                    <label for="tambah_nis">NIS</label>
                    <input type="text" id="tambah_nis" name="nis" placeholder="Nomor Induk Siswa" required>
                    <p class="siswa-modal__error" data-error-for="nis"></p>
                </div>

                <div class="siswa-modal__field">
                    <label for="tambah_kelas">Kelas</label>
                    <select id="tambah_kelas" name="kelas" required>
                        <option value="">Pilih kelas</option>
                        <option value="XI RPL 1">XI RPL 1</option>
                        <option value="XI RPL 2">XI RPL 2</option>
                        <option value="XI RPL 3">XI RPL 3</option>
                        <option value="XII RPL A">XII RPL A</option>
                        <option value="XII RPL B">XII RPL B</option>
                    </select>
                    <p class="siswa-modal__error" data-error-for="kelas"></p>
                </div>
            </div>

            <div class="siswa-modal__footer">
                <button type="button" class="siswa-modal__btn siswa-modal__btn--secondary" onclick="tutupModal('modalTambahSiswa')">
                    Batal
                </button>
                <button type="submit" class="siswa-modal__btn siswa-modal__btn--primary">
                    Buat Akun Siswa
                </button>
            </div>
        </form>

    </div>
</div>


{{-- =====================================================
     4. MODAL: FORM EDIT DATA SISWA
====================================================== --}}

<div class="siswa-modal-overlay" id="modalEditSiswa">
    <div class="siswa-modal">

        <div class="siswa-modal__header">
            <span class="siswa-modal__header-icon">
                <i class="bi bi-pencil-square"></i>
            </span>

            <div>
                <p class="siswa-modal__title">Edit Data Siswa</p>
                <p class="siswa-modal__subtitle">Ubah informasi identitas dan kelas siswa.</p>
            </div>
        </div>

        <form id="formEditSiswa">
            @csrf
            @method('PUT')

            <input type="hidden" id="edit_siswa_id" name="siswa_id">

            <div class="siswa-modal__field">
                <label for="edit_nama_siswa">Nama Lengkap Siswa</label>
                <input type="text" id="edit_nama_siswa" name="nama" required>
                <p class="siswa-modal__error" data-error-for="nama"></p>
            </div>

            <div class="siswa-modal__field">
                <label for="edit_kelas_siswa">Kelas</label>
                <select id="edit_kelas_siswa" name="kelas" required>
                    <option value="XI RPL 1">XI RPL 1</option>
                    <option value="XI RPL 2">XI RPL 2</option>
                    <option value="XI RPL 3">XI RPL 3</option>
                    <option value="XII RPL A">XII RPL A</option>
                    <option value="XII RPL B">XII RPL B</option>
                </select>
                <p class="siswa-modal__error" data-error-for="kelas"></p>
            </div>

            <div class="siswa-modal__footer">
                <button type="button" class="siswa-modal__btn siswa-modal__btn--secondary" onclick="tutupModal('modalEditSiswa')">
                    Batal
                </button>
                <button type="submit" class="siswa-modal__btn siswa-modal__btn--primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>


{{-- =====================================================
     5. MODAL: KREDENSIAL AKUN SISWA BARU
====================================================== --}}

<div class="siswa-modal-overlay" id="modalKredensialSiswa">
    <div class="siswa-modal">

        <div class="siswa-modal__cred-icon-wrap">
            <span class="siswa-modal__cred-icon">
                <i class="bi bi-key-fill"></i>
            </span>
        </div>

        <p class="siswa-modal__cred-title">Akun Siswa Berhasil Dibuat</p>
        <p class="siswa-modal__cred-subtitle">
            Berikan kredensial ini kepada siswa untuk login portal magang.
        </p>

        <div class="siswa-modal__cred-row">
            <div>
                <p class="siswa-modal__cred-label">Email Akun</p>
                <p class="siswa-modal__cred-value" id="credSiswaEmail">-</p>
            </div>
            <button type="button" class="siswa-modal__cred-copy" onclick="copyToClipboard('credSiswaEmail')">
                <i class="bi bi-clipboard"></i>
            </button>
        </div>

        <div class="siswa-modal__cred-row">
            <div>
                <p class="siswa-modal__cred-label">Password Sementara</p>
                <p class="siswa-modal__cred-value" id="credSiswaPassword">-</p>
            </div>
            <button type="button" class="siswa-modal__cred-copy" onclick="copyToClipboard('credSiswaPassword')">
                <i class="bi bi-clipboard"></i>
            </button>
        </div>

        <div class="siswa-modal__footer" style="margin-top: 16px;">
            <button type="button" class="siswa-modal__btn siswa-modal__btn--primary" style="width: 100%;" onclick="selesaiKredensialSiswa()">
                Selesai
            </button>
        </div>

    </div>
</div>


{{-- =====================================================
     6. MODAL: PLOTTING GURU PEMBIMBING & DUDI
====================================================== --}}

<div class="siswa-modal-overlay" id="modalPlotting">
    <div class="siswa-modal">

        <div class="siswa-modal__header">
            <span class="siswa-modal__header-icon is-purple">
                <i class="bi bi-diagram-3"></i>
            </span>

            <div>
                <p class="siswa-modal__title">Plot Guru Pembimbing</p>
                <p class="siswa-modal__subtitle">
                    Tetapkan guru pembimbing dan DUDI untuk
                    <strong id="plottingNamaSiswa">-</strong>.
                </p>
            </div>
        </div>

        <form id="formPlotting">
            @csrf

            <input type="hidden" id="plotting_siswa_id" name="siswa_id">

            <div class="siswa-modal__field">
                <label for="plotting_guru">Guru Pembimbing</label>
                <select id="plotting_guru" name="guru_id" required>
                    <option value="">Pilih guru pembimbing</option>
                    @foreach ($guruList as $guru)
                        <option value="{{ $guru->id }}">
                            {{ $guru->profile->nama ?? '-' }} (NIP: {{ $guru->nip }})
                        </option>
                    @endforeach
                </select>
                <p class="siswa-modal__error" data-error-for="guru_id"></p>
            </div>

            <div class="siswa-modal__field">
                <label for="plotting_dudi">Tempat Magang (Industri)</label>
                <select id="plotting_dudi" name="tempat_magang_id" required>
                    <option value="">Pilih tempat magang</option>
                    @foreach ($dudiList as $dudi)
                        {{-- sisa_kuota diakses sebagai properti (accessor),
                            bukan method — sesuai getSisaKuotaAttribute() di Model TempatMagang --}}
                        <option value="{{ $dudi->id }}">
                            {{ $dudi->nama_perusahaan }} (Sisa Kuota: {{ $dudi->sisa_kuota }})
                        </option>
                    @endforeach
                </select>
                <p class="siswa-modal__error" data-error-for="tempat_magang_id"></p>
            </div>

            <div class="siswa-modal__field-row">
                <div class="siswa-modal__field">
                    <label for="plotting_mulai">Tanggal Mulai</label>
                    <input type="date" id="plotting_mulai" name="tanggal_mulai" required>
                    <p class="siswa-modal__error" data-error-for="tanggal_mulai"></p>
                </div>

                <div class="siswa-modal__field">
                    <label for="plotting_selesai">Tanggal Selesai</label>
                    <input type="date" id="plotting_selesai" name="tanggal_selesai" required>
                    <p class="siswa-modal__error" data-error-for="tanggal_selesai"></p>
                </div>
            </div>

            <div class="siswa-modal__footer">
                <button type="button" class="siswa-modal__btn siswa-modal__btn--secondary" onclick="tutupModal('modalPlotting')">
                    Batal
                </button>
                <button type="submit" class="siswa-modal__btn siswa-modal__btn--purple">
                    Simpan Plotting
                </button>
            </div>
        </form>

    </div>
</div>


{{-- =====================================================
     7. MODAL: UBAH STATUS SISWA
====================================================== --}}

<div class="siswa-modal-overlay" id="modalStatusSiswa">
    <div class="siswa-modal">

        <div class="siswa-modal__header">
            <span class="siswa-modal__header-icon">
                <i class="bi bi-arrow-repeat"></i>
            </span>

            <div>
                <p class="siswa-modal__title">Ubah Status Siswa</p>
                <p class="siswa-modal__subtitle">Perbarui status aktif untuk siswa magang.</p>
            </div>
        </div>

        <form id="formStatusSiswa">
            @csrf
            @method('PATCH')

            <input type="hidden" id="status_siswa_id" name="siswa_id">

            <div class="siswa-modal__field">
                <label for="status_siswa_is_active">Status Akun</label>
                <select id="status_siswa_is_active" name="is_active" required>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="siswa-modal__footer">
                <button type="button" class="siswa-modal__btn siswa-modal__btn--secondary" onclick="tutupModal('modalStatusSiswa')">
                    Batal
                </button>
                <button type="submit" class="siswa-modal__btn siswa-modal__btn--primary">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>


{{-- =====================================================
     8. ALERT DIALOG: KONFIRMASI HAPUS DATA SISWA
====================================================== --}}

<div class="siswa-modal-overlay" id="alertHapusSiswa">
    <div class="siswa-modal" style="max-width: 380px;">

        <div class="siswa-modal__header">
            <span class="siswa-modal__header-icon" style="background: #fee2e2; color: #dc2626;">
                <i class="bi bi-trash"></i>
            </span>

            <div>
                <p class="siswa-modal__title">Hapus Data Siswa?</p>
            </div>
        </div>

        <p style="font-size: 0.84rem; color: var(--simmas-muted); margin-bottom: 18px;">
            Apakah Anda yakin ingin menghapus siswa
            <strong id="hapusSiswaNama">-</strong>
            (NIS: <strong id="hapusSiswaNis">-</strong>)?
            Seluruh data riwayat presensi, jurnal, dan penempatan terkait akan ikut dihapus.
        </p>

        <div class="siswa-modal__footer">
            <button type="button" class="siswa-modal__btn siswa-modal__btn--secondary" onclick="tutupModal('alertHapusSiswa')">
                Batal
            </button>
            <button type="button" class="siswa-modal__btn siswa-modal__btn--danger" onclick="konfirmasiHapusSiswa()">
                Ya, Hapus Siswa
            </button>
        </div>

    </div>
</div>


{{-- =====================================================
     9. TOAST NOTIFIKASI
====================================================== --}}

<div class="siswa-toast" id="siswaToast"></div>

@endsection


@push('scripts')
<script>

/* =========================================================
   HELPER UMUM
   ========================================================= */

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function tampilkanToast(pesan) {
    const toast = document.getElementById('siswaToast');
    toast.textContent = pesan;
    toast.classList.add('is-active');

    setTimeout(() => {
        toast.classList.remove('is-active');
    }, 3000);
}

function bukaModal(modalId) {
    document.getElementById(modalId).classList.add('is-active');
}

function tutupModal(modalId) {
    document.getElementById(modalId).classList.remove('is-active');
}

function toggleAksiMenu(button) {
    const menu = button.nextElementSibling;
    const semuaMenu = document.querySelectorAll('.siswa-aksi__menu');

    semuaMenu.forEach((m) => {
        if (m !== menu) m.classList.remove('is-open');
    });

    menu.classList.toggle('is-open');
}

document.addEventListener('click', function (event) {
    if (!event.target.closest('.siswa-aksi')) {
        document.querySelectorAll('.siswa-aksi__menu').forEach((m) => m.classList.remove('is-open'));
    }
});

function resetError(formId) {
    document.querySelectorAll(`#${formId} .siswa-modal__error`).forEach((el) => {
        el.style.display = 'none';
        el.textContent = '';
    });
}

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
   3. TAMBAH SISWA
   ========================================================= */

function bukaModalTambahSiswa() {
    document.getElementById('formTambahSiswa').reset();
    resetError('formTambahSiswa');
    bukaModal('modalTambahSiswa');
}

document.getElementById('formTambahSiswa').addEventListener('submit', async function (event) {
    event.preventDefault();
    resetError('formTambahSiswa');

    const formData = new FormData(event.target);

    try {
        const response = await fetch("{{ route('admin.siswa.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (response.status === 422) {
            tampilkanError('formTambahSiswa', result.errors);
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Gagal menambahkan siswa.');
        }

        tutupModal('modalTambahSiswa');

        document.getElementById('credSiswaEmail').textContent = result.kredensial.email;
        document.getElementById('credSiswaPassword').textContent = result.kredensial.password;

        bukaModal('modalKredensialSiswa');

    } catch (error) {
        tampilkanToast(error.message);
    }
});

function selesaiKredensialSiswa() {
    tutupModal('modalKredensialSiswa');
    window.location.reload();
}

function copyToClipboard(elementId) {
    const text = document.getElementById(elementId).textContent;
    navigator.clipboard.writeText(text);
    tampilkanToast('Berhasil disalin ke clipboard.');
}


/* =========================================================
   4. EDIT SISWA
   ========================================================= */

function bukaModalEditSiswa(id, nama, kelas) {
    resetError('formEditSiswa');

    document.getElementById('edit_siswa_id').value = id;
    document.getElementById('edit_nama_siswa').value = nama;
    document.getElementById('edit_kelas_siswa').value = kelas;

    bukaModal('modalEditSiswa');
}

document.getElementById('formEditSiswa').addEventListener('submit', async function (event) {
    event.preventDefault();
    resetError('formEditSiswa');

    const siswaId = document.getElementById('edit_siswa_id').value;
    const formData = new FormData(event.target);

    try {
        const response = await fetch(`/admin/siswa/${siswaId}`, {
            method: 'POST', // method spoofing PUT
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (response.status === 422) {
            tampilkanError('formEditSiswa', result.errors);
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Gagal memperbarui data siswa.');
        }

        tutupModal('modalEditSiswa');
        tampilkanToast(result.message);

        setTimeout(() => window.location.reload(), 800);

    } catch (error) {
        tampilkanToast(error.message);
    }
});


/* =========================================================
   6. PLOTTING GURU PEMBIMBING & DUDI
   ========================================================= */

function bukaModalPlotting(id, nama) {
    resetError('formPlotting');
    document.getElementById('formPlotting').reset();

    document.getElementById('plotting_siswa_id').value = id;
    document.getElementById('plottingNamaSiswa').textContent = nama;

    bukaModal('modalPlotting');
}

document.getElementById('formPlotting').addEventListener('submit', async function (event) {
    event.preventDefault();
    resetError('formPlotting');

    const siswaId = document.getElementById('plotting_siswa_id').value;
    const formData = new FormData(event.target);

    try {
        const response = await fetch(`/admin/siswa/${siswaId}/plotting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        // 422 dipakai juga untuk kasus "kuota penuh" dari backend
        if (response.status === 422) {
            if (result.errors) {
                tampilkanError('formPlotting', result.errors);
            } else {
                tampilkanToast(result.message);
            }
            return;
        }

        if (!response.ok) {
            throw new Error(result.message || 'Gagal menyimpan plotting.');
        }

        tutupModal('modalPlotting');
        tampilkanToast(result.message);

        setTimeout(() => window.location.reload(), 800);

    } catch (error) {
        tampilkanToast(error.message);
    }
});


/* =========================================================
   7. UBAH STATUS SISWA
   ========================================================= */

function bukaModalStatusSiswa(id, isActive) {
    document.getElementById('status_siswa_id').value = id;
    document.getElementById('status_siswa_is_active').value = isActive ? '1' : '0';
    bukaModal('modalStatusSiswa');
}

document.getElementById('formStatusSiswa').addEventListener('submit', async function (event) {
    event.preventDefault();

    const siswaId = document.getElementById('status_siswa_id').value;
    const isActive = document.getElementById('status_siswa_is_active').value;

    try {
        const response = await fetch(`/admin/siswa/${siswaId}/status`, {
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
            throw new Error(result.message || 'Gagal mengubah status siswa.');
        }

        tutupModal('modalStatusSiswa');
        tampilkanToast(result.message);

        setTimeout(() => window.location.reload(), 800);

    } catch (error) {
        tampilkanToast(error.message);
    }
});


/* =========================================================
   8. HAPUS SISWA
   ========================================================= */

let siswaIdAkanDihapus = null;

function bukaAlertHapusSiswa(id, nama, nis) {
    siswaIdAkanDihapus = id;
    document.getElementById('hapusSiswaNama').textContent = nama;
    document.getElementById('hapusSiswaNis').textContent = nis;
    bukaModal('alertHapusSiswa');
}

async function konfirmasiHapusSiswa() {
    if (!siswaIdAkanDihapus) return;

    try {
        const response = await fetch(`/admin/siswa/${siswaIdAkanDihapus}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Gagal menghapus data siswa.');
        }

        tutupModal('alertHapusSiswa');
        tampilkanToast(result.message);

        setTimeout(() => window.location.reload(), 800);

    } catch (error) {
        tutupModal('alertHapusSiswa');
        tampilkanToast(error.message);
    }
}

</script>
@endpush

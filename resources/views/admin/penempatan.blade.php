    {{-- ============================================================
        PENEMPATAN MAGANG — Portal Administrator
        Modul: manajemen penempatan resmi siswa ke DUDI + penugasan
        guru pembimbing
        ============================================================ --}}
    @extends('layouts.admin')

    @section('title', 'Penempatan Magang - SIMMAS Administrator')

    {{-- Judul di topbar layouts.admin (@yield('page-title', 'Dashboard')) --}}
    @section('page-title', 'Penempatan Magang')

    {{-- ============================================================
        CSS KHUSUS HALAMAN PENEMPATAN
        ============================================================ --}}
    @push('styles')
    <style>

        /* ===== 1. HEADER HALAMAN — judul+breadcrumb kiri,
            tombol "Tambah Penempatan" kanan, sejajar ===== */
        .penempatan-header {
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            justify-content: flex-end;
            gap: 16px;
            flex-wrap: wrap;
        }
        .penempatan-header__title {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--simmas-ink);
            margin-bottom: 2px;
        }
        .penempatan-header__breadcrumb {
            font-size: 0.85rem;
            color: var(--simmas-muted);
        }

        /* ===== 2. KARTU STATISTIK RINGKAS (4 kartu atas) ===== */
        .penempatan-stat {
            background: #fff;
            border: 1px solid var(--simmas-border);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            height: 100%;
        }
        .penempatan-stat__label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--simmas-muted);
            margin-bottom: 6px;
        }
        .penempatan-stat__value {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--simmas-ink);
            margin-bottom: 2px;
        }
        .penempatan-stat__desc { font-size: 0.72rem; color: var(--simmas-muted); }
        .penempatan-stat__icon {
            width: 44px; height: 44px;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            font-size: 1.1rem;
        }
        .penempatan-stat__icon--total       { background: var(--simmas-blue-light); color: var(--simmas-blue); }
        .penempatan-stat__icon--berlangsung { background: var(--simmas-amber-light); color: var(--simmas-amber); }
        .penempatan-stat__icon--selesai     { background: var(--simmas-green-light); color: var(--simmas-green); }
        .penempatan-stat__icon--dudi        { background: #F1F3F9; color: var(--simmas-ink); }

        /* ===== 3. TOOLBAR — search + filter dropdown.
            Tombol Tambah sudah dipindah ke .penempatan-header,
            jadi grup kanan di sini cuma jumlah data ===== */
        .penempatan-toolbar {
            background: #fff;
            border: 1px solid var(--simmas-border);
            border-radius: 14px 14px 0 0;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .penempatan-toolbar__left {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            flex: 1 1 auto;
            min-width: 0;
        }

        /* Grup kanan toolbar: cuma jumlah data (tombol Tambah sudah
        dipindah ke header, sejajar judul halaman) */
        .penempatan-toolbar__right {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .penempatan-toolbar__search {
            position: relative;
            width: 240px;
            flex: 0 0 auto;
        }
        .penempatan-toolbar__search .bi-search {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            font-size: 0.8rem;
            color: var(--simmas-muted);
            pointer-events: none;
        }
        .penempatan-toolbar__search input {
            width: 100%;
            border-radius: 999px;
            padding-left: 34px;
            border-color: var(--simmas-border);
        }
        .penempatan-toolbar__search input:focus {
            border-color: var(--simmas-blue);
            box-shadow: 0 0 0 3px var(--simmas-blue-light);
        }

        .penempatan-toolbar__filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .penempatan-toolbar__filters .form-select {
            width: auto;
            flex: 0 0 auto;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 999px;
            border-color: var(--simmas-border);
            color: var(--simmas-ink);
            min-width: 130px;
            padding: 6px 30px 6px 14px;
        }
        .penempatan-toolbar__filters .form-select:focus {
            border-color: var(--simmas-blue);
            box-shadow: 0 0 0 3px var(--simmas-blue-light);
        }

        .penempatan-toolbar__count {
            font-size: 0.8rem;
            color: var(--simmas-muted);
            white-space: nowrap;
        }

        /* ===== 4. TABEL DATA PENEMPATAN ===== */
        .penempatan-table-wrap {
            background: #fff;
            border: 1px solid var(--simmas-border);
            border-top: none;
            border-radius: 0 0 14px 14px;
            overflow-x: auto;
        }
        .penempatan-table { width: 100%; margin-bottom: 0; font-size: 0.85rem; }
        .penempatan-table thead th {
            background: var(--simmas-paper);
            color: var(--simmas-muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 1px solid var(--simmas-border);
            padding: 12px 16px;
            white-space: nowrap;
        }
        .penempatan-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--simmas-border);
        }
        .penempatan-table tbody tr:last-child td { border-bottom: none; }

        .penempatan-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .penempatan-avatar--1 { background: var(--simmas-blue-light); color: var(--simmas-blue); }
        .penempatan-avatar--2 { background: var(--simmas-green-light); color: var(--simmas-green); }
        .penempatan-avatar--3 { background: var(--simmas-amber-light); color: var(--simmas-amber); }

        .penempatan-table__nama { font-weight: 600; color: var(--simmas-ink); }
        .penempatan-table__nis { font-size: 0.72rem; color: var(--simmas-muted); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .status-badge__dot { width: 6px; height: 6px; border-radius: 50%; }
        .status-badge--disahkan { background: var(--simmas-green-light); color: var(--simmas-green); }
        .status-badge--disahkan .status-badge__dot { background: var(--simmas-green); }
        .status-badge--lulus { background: var(--simmas-blue-light); color: var(--simmas-blue); }
        .status-badge--lulus .status-badge__dot { background: var(--simmas-blue); }
        .status-badge--belum { background: #F1F3F9; color: var(--simmas-muted); }
        .status-badge--belum .status-badge__dot { background: var(--simmas-muted); }

        .penempatan-action-btn {
            width: 32px; height: 32px;
            border: none;
            border-radius: 50%;
            background: transparent;
            display: inline-flex; align-items: center; justify-content: center;
            color: var(--simmas-muted);
            font-size: 1.05rem;
            transition: background .15s ease, color .15s ease;
        }
        .penempatan-action-btn:hover,
        .penempatan-action-btn:focus {
            background: var(--simmas-blue-light);
            color: var(--simmas-blue);
        }

        .penempatan-table .dropdown-menu {
            border-radius: 12px;
            border: 1px solid var(--simmas-border);
            box-shadow: 0 12px 28px -10px rgba(19, 27, 58, 0.18);
            padding: 6px;
            font-size: 0.85rem;
        }
        .penempatan-table .dropdown-item {
            border-radius: 8px;
            padding: 8px 10px;
        }
        .penempatan-table .dropdown-item:hover { background: var(--simmas-paper); }
        .penempatan-table .dropdown-item.text-danger:hover { background: #FEF2F2; }

        /* ===== 5. PAGINATION FOOTER TABEL ===== */
        .penempatan-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid var(--simmas-border);
            font-size: 0.8rem;
            color: var(--simmas-muted);
            flex-wrap: wrap;
            gap: 10px;
        }
        .penempatan-pagination .pagination { margin: 0; gap: 4px; }
        .penempatan-pagination .page-link {
            width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            border: 1px solid var(--simmas-border);
            color: var(--simmas-ink);
            font-size: 0.78rem;
            padding: 0;
        }
        .penempatan-pagination .page-item.active .page-link {
            background: var(--simmas-blue);
            border-color: var(--simmas-blue);
            color: #fff;
        }
        .penempatan-pagination .page-item.disabled .page-link {
            color: var(--simmas-muted);
            background: transparent;
        }

        /* ===== 6. MODAL — style dasar dipakai semua modal di halaman ini ===== */
        .simmas-modal .modal-content { border-radius: 16px; border: none; }
        .simmas-modal .modal-header { border-bottom: 1px solid var(--simmas-border); padding: 20px 24px; }
        .simmas-modal .modal-body { padding: 22px 24px; }
        .simmas-modal .modal-footer { border-top: 1px solid var(--simmas-border); padding: 16px 24px; }
        .simmas-modal__icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            background: var(--simmas-blue-light);
            color: var(--simmas-blue);
            font-size: 1rem;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .simmas-modal__title { font-weight: 700; font-size: 1rem; margin-bottom: 2px; }
        .simmas-modal__subtitle { font-size: 0.78rem; color: var(--simmas-muted); }

        .simmas-modal .form-select,
        .simmas-modal .form-control {
            border-radius: 10px;
            border-color: var(--simmas-border);
        }
        .simmas-modal .form-select:focus,
        .simmas-modal .form-control:focus {
            border-color: var(--simmas-blue);
            box-shadow: 0 0 0 3px var(--simmas-blue-light);
        }

        .simmas-alert__icon {
            width: 46px; height: 46px;
            border-radius: 50%;
            background: var(--simmas-amber-light);
            color: var(--simmas-amber);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 14px;
        }

        .simmas-form-error {
            font-size: 0.75rem;
            color: #DC2626;
            margin-top: 4px;
            display: none;
        }

        /* ===== 7. TAB SWITCHER (Penempatan / Pengajuan) ===== */
        .penempatan-tabs {
            display: flex;
            gap: 4px;
            background: #F1F3F9;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 20px;
            width: fit-content;
        }
        .penempatan-tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 20px;
            border: none;
            border-radius: 9px;
            background: transparent;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--simmas-muted);
            cursor: pointer;
            transition: all .15s ease;
            white-space: nowrap;
        }
        .penempatan-tab-btn.active {
            background: #fff;
            color: var(--simmas-blue);
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
        }
        .penempatan-tab-btn .tab-badge {
            background: #DC2626;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            line-height: 1;
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* ===== 8. TABEL PENGAJUAN (reuse style penempatan-table) ===== */
        .status-badge--menunggu  { background: #FEF3E2; color: #B45309; }
        .status-badge--menunggu  .status-badge__dot { background: #F59E0B; }
        .status-badge--disetujui { background: var(--simmas-green-light); color: var(--simmas-green); }
        .status-badge--disetujui .status-badge__dot { background: var(--simmas-green); }
        .status-badge--ditolak   { background: #FEF2F2; color: #DC2626; }
        .status-badge--ditolak   .status-badge__dot { background: #DC2626; }

        .pengajuan-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 13px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s ease;
            background: none;
        }
        .pengajuan-action-btn--setuju {
            background: var(--simmas-green-light);
            color: var(--simmas-green);
            border-color: #BBF7D0;
        }
        .pengajuan-action-btn--setuju:hover { background: var(--simmas-green); color: #fff; }
        .pengajuan-action-btn--tolak {
            background: #FEF2F2;
            color: #DC2626;
            border-color: #FECACA;
        }
        .pengajuan-action-btn--tolak:hover { background: #DC2626; color: #fff; }
        .pengajuan-action-btn--detail {
            background: var(--simmas-blue-light);
            color: var(--simmas-blue);
            border-color: #BFDBFE;
        }
        .pengajuan-action-btn--detail:hover { background: var(--simmas-blue); color: #fff; }
    </style>
    @endpush

    @section('content')

    {{-- Judul & breadcrumb dihapus — topbar (layouts.admin) sudah
        menampilkan "Penempatan Magang" lewat @section('page-title', ...),
        jadi tidak perlu diulang di sini. Cukup tombolnya saja. --}}
            <div class="penempatan-header">
                <button type="button" class="btn simmas-btn-primary-sm"
                        data-bs-toggle="modal" data-bs-target="#modalTambahPenempatan">
                    <i class="bi bi-plus-lg"></i> Tambah Penempatan
                </button>
            </div>

        {{-- ============================================================
            2. KARTU STATISTIK RINGKAS (4 kartu)
            ============================================================ --}}
        <div class="row g-3 mb-4">

            <div class="col-6 col-lg-3">
                <div class="penempatan-stat">
                    <div>
                        <p class="penempatan-stat__label">Total Penempatan</p>
                        <p class="penempatan-stat__value">{{ $totalPenempatan }}</p>
                        <p class="penempatan-stat__desc">Siswa dipenempatan</p>
                    </div>
                    <span class="penempatan-stat__icon penempatan-stat__icon--total">
                        <i class="bi bi-diagram-3-fill"></i>
                    </span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="penempatan-stat">
                    <div>
                        <p class="penempatan-stat__label">Sedang Berlangsung</p>
                        <p class="penempatan-stat__value">{{ $sedangBerlangsung }}</p>
                        <p class="penempatan-stat__desc">Siswa magang aktif</p>
                    </div>
                    <span class="penempatan-stat__icon penempatan-stat__icon--berlangsung">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="penempatan-stat">
                    <div>
                        <p class="penempatan-stat__label">Selesai Magang</p>
                        <p class="penempatan-stat__value">{{ $lulusMagang }}</p>
                        <p class="penempatan-stat__desc">Program selesai</p>
                    </div>
                    <span class="penempatan-stat__icon penempatan-stat__icon--selesai">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="penempatan-stat">
                    <div>
                        <p class="penempatan-stat__label">DUDI Terlibat</p>
                        <p class="penempatan-stat__value">{{ $dudiTerlibat }}</p>
                        <p class="penempatan-stat__desc">Mitra aktif</p>
                    </div>
                    <span class="penempatan-stat__icon penempatan-stat__icon--dudi">
                        <i class="bi bi-building-fill"></i>
                    </span>
                </div>
            </div>

        </div>

        {{-- ============================================================
            4. TOOLBAR — search + 3 filter, satu baris
            (tombol Tambah sudah pindah ke header di atas)
            ============================================================ --}}
        <form action="{{ url('/admin/penempatan') }}" method="GET">
            <div class="penempatan-toolbar">

                <div class="penempatan-toolbar__left">

                    <div class="penempatan-toolbar__search">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm"
                            placeholder="Cari siswa, NIS, atau kelas...">
                    </div>

                    <div class="penempatan-toolbar__filters">

                        {{-- Filter Kelas — isinya dari Siswa::distinct()->pluck('kelas') --}}
                        <select name="kelas" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="semua">Semua Kelas</option>
                            @foreach ($daftarKelas as $kelas)
                                <option value="{{ $kelas }}" @selected(request('kelas') == $kelas)>{{ $kelas }}</option>
                            @endforeach
                        </select>

                        {{-- Filter DUDI — isinya dari TempatMagang::where('status_verifikasi', 'terverifikasi') --}}
                        <select name="dudi" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="semua">Semua DUDI</option>
                            @foreach ($dudiList as $dudi)
                                <option value="{{ $dudi->id }}" @selected(request('dudi') == $dudi->id)>{{ $dudi->nama_perusahaan }}</option>
                            @endforeach
                        </select>

                        {{-- Filter Status --}}
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="semua">Semua Status</option>
                            <option value="disahkan" @selected(request('status') == 'disahkan')>Disahkan (Sedang Magang)</option>
                            <option value="lulus_magang" @selected(request('status') == 'lulus_magang')>Lulus Magang</option>
                            <option value="belum_disahkan" @selected(request('status') == 'belum_disahkan')>Belum Disahkan</option>
                            <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu Validasi</option>
                            <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                        </select>
                    </div>
                </div>

                {{-- Grup kanan toolbar: cuma jumlah data —
                    tombol Tambah sudah dipindah ke header di atas --}}
                <div class="penempatan-toolbar__right">
                    <span class="penempatan-toolbar__count">{{ $penempatanList->total() }} Penempatan</span>
                </div>

            </div>
        </form>

        {{-- ============================================================
            4. TABEL DATA PENEMPATAN
            ============================================================ --}}
        <div class="penempatan-table-wrap">
            <table class="table penempatan-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Tempat Magang</th>
                        <th>Guru Pembimbing</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penempatanList as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="penempatan-avatar penempatan-avatar--{{ ($loop->iteration % 3) + 1 }}">
                                        {{ strtoupper(substr($item->siswa->profile->nama ?? '-', 0, 2)) }}
                                    </span>
                                    <div>
                                        <p class="penempatan-table__nama mb-0">{{ $item->siswa->profile->nama ?? '-' }}</p>
                                        <p class="penempatan-table__nis mb-0">{{ $item->siswa->nis }}</p>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $item->siswa->kelas }}</td>

                            <td>{{ $item->tempatMagang->nama_perusahaan }}</td>

                            <td>{{ $item->guru->profile->nama ?? '-' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                <br>
                                <span class="text-muted small">s.d. {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</span>
                            </td>

                            <td>
                                @if ($item->status_pengesahan === 'menunggu')
                                    <span class="status-badge status-badge--menunggu">
                                        <span class="status-badge__dot"></span> Menunggu Validasi
                                    </span>
                                @elseif ($item->status_pengesahan === 'disahkan')
                                    <span class="status-badge status-badge--disahkan">
                                        <span class="status-badge__dot"></span> Berlangsung
                                    </span>
                                @elseif ($item->status_pengesahan === 'lulus_magang')
                                    <span class="status-badge status-badge--lulus">
                                        <span class="status-badge__dot"></span> Selesai
                                    </span>
                                @elseif ($item->status_pengesahan === 'ditolak')
                                    <span class="status-badge status-badge--ditolak">
                                        <span class="status-badge__dot"></span> Ditolak
                                    </span>
                                @else
                                    <span class="status-badge status-badge--belum">
                                        <span class="status-badge__dot"></span> Belum Disahkan
                                    </span>
                                @endif
                            </td>

                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="penempatan-action-btn" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">

                                        @if ($item->status_pengesahan === 'menunggu')
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="konfirmasiSetujui('{{ $item->id }}','{{ addslashes($item->siswa->profile->nama ?? '-') }}','{{ addslashes($item->tempatMagang->nama_perusahaan ?? '-') }}')">
                                                <i class="bi bi-check-circle me-2"></i> Validasi Pengajuan
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" onclick="openTolak('{{ $item->id }}','{{ addslashes($item->siswa->profile->nama ?? '-') }}')">
                                                <i class="bi bi-x-circle me-2"></i> Tolak Pengajuan
                                            </a>
                                        </li>
                                        @else

                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalSahkanStatus"
                                            data-id="{{ $item->id }}"
                                            data-status="{{ $item->status_pengesahan }}">
                                                <i class="bi bi-patch-check me-2"></i> Sahkan Status
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalEditPenempatan"
                                            data-id="{{ $item->id }}"
                                            data-siswa-nama="{{ ($item->siswa->profile->nama ?? '-') . ' - ' . $item->siswa->nis }}"
                                            data-dudi-id="{{ $item->tempat_magang_id }}"
                                            data-guru-id="{{ $item->guru_id }}"
                                            data-mulai="{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('Y-m-d') }}"
                                            data-selesai="{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('Y-m-d') }}">
                                                <i class="bi bi-pencil me-2"></i> Edit Penempatan
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalBatalkanPenempatan"
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->siswa->profile->nama ?? '-' }}"
                                            data-dudi="{{ $item->tempatMagang->nama_perusahaan }}">
                                                <i class="bi bi-x-circle me-2"></i> Batalkan Penempatan
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                Belum ada data penempatan magang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="penempatan-pagination">
                <span>
                    Menampilkan {{ $penempatanList->firstItem() ?? 0 }}-{{ $penempatanList->lastItem() ?? 0 }}
                    dari {{ $penempatanList->total() }} data
                    &middot; Baris per halaman: {{ $penempatanList->perPage() }}
                </span>
                {{ $penempatanList->links() }}
            </div>
        </div>

        @if (false)
        {{-- ============================================================
            TAB 2 — PENGAJUAN MASUK DARI SISWA
            ============================================================ --}}
        <div id="tab-pengajuan" class="tab-panel">

            {{-- Toolbar pengajuan --}}
            <form action="{{ url('/admin/penempatan') }}" method="GET" id="formFilterPengajuan">
                {{-- Pertahankan filter penempatan supaya pagination tab 1 tidak reset --}}
                <input type="hidden" name="search"  value="{{ request('search') }}">
                <input type="hidden" name="kelas"   value="{{ request('kelas') }}">
                <input type="hidden" name="dudi"    value="{{ request('dudi') }}">
                <input type="hidden" name="status"  value="{{ request('status') }}">
                {{-- Buka tab pengajuan otomatis --}}
                <input type="hidden" name="tab" value="pengajuan">

                <div class="penempatan-toolbar" style="border-radius:14px;margin-bottom:0;">
                    <div class="penempatan-toolbar__left">
                        <div class="penempatan-toolbar__search">
                            <i class="bi bi-search"></i>
                            <input type="text" name="cari_pengajuan"
                                value="{{ request('cari_pengajuan') }}"
                                class="form-control form-control-sm"
                                placeholder="Cari nama siswa, NIS, atau DUDI..."
                                oninput="this.form.submit()">
                        </div>
                        <div class="penempatan-toolbar__filters">
                            <select name="status_pengajuan" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="semua"     {{ request('status_pengajuan','semua') === 'semua'    ? 'selected' : '' }}>Semua Status</option>
                                <option value="menunggu"  {{ request('status_pengajuan') === 'menunggu'         ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ request('status_pengajuan') === 'disetujui'        ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak"   {{ request('status_pengajuan') === 'ditolak'          ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="penempatan-toolbar__right">
                        <span class="penempatan-toolbar__count">{{ $pengajuanList->total() }} pengajuan</span>
                    </div>
                </div>
            </form>

            {{-- Tabel pengajuan --}}
            <div class="penempatan-table-wrap" style="border-radius:0 0 14px 14px;border-top:none;">
                <table class="table penempatan-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tempat Magang (DUDI)</th>
                            <th>Posisi / Divisi</th>
                            <th>Periode</th>
                            <th>Tgl Kirim</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuanList as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="penempatan-avatar penempatan-avatar--1">
                                        {{ strtoupper(substr($p->siswa->profile->nama ?? 'S', 0, 2)) }}
                                    </span>
                                    <div>
                                        <p class="penempatan-table__nama mb-0">{{ $p->siswa->profile->nama ?? '-' }}</p>
                                        <p class="penempatan-table__nis mb-0">{{ $p->siswa->nis ?? '-' }} &bull; {{ $p->siswa->kelas ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:.85rem;">{{ $p->tempatMagang->nama_perusahaan ?? '-' }}</div>
                                <div class="penempatan-table__nis">{{ $p->tempatMagang->bidang_usaha ?? '' }}</div>
                            </td>
                            <td style="font-size:.85rem;">{{ $p->posisi }}</td>
                            <td style="font-size:.82rem;white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}<br>
                                <span class="text-muted small">s.d. {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}</span>
                            </td>
                            <td style="font-size:.82rem;white-space:nowrap;color:var(--simmas-muted);">
                                {{ $p->created_at->format('d M Y') }}<br>
                                <span style="font-size:.75rem;">{{ $p->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td>
                                @if ($p->status === 'menunggu')
                                    <span class="status-badge status-badge--menunggu"><span class="status-badge__dot"></span> Menunggu</span>
                                @elseif ($p->status === 'disetujui')
                                    <span class="status-badge status-badge--disetujui"><span class="status-badge__dot"></span> Disetujui</span>
                                @else
                                    <span class="status-badge status-badge--ditolak"><span class="status-badge__dot"></span> Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    @if ($p->status === 'menunggu')
                                        <button type="button" class="pengajuan-action-btn pengajuan-action-btn--setuju"
                                                onclick="konfirmasiSetujui('{{ $p->id }}','{{ addslashes($p->siswa->profile->nama ?? '-') }}','{{ addslashes($p->tempatMagang->nama_perusahaan ?? '-') }}')">
                                            <i class="bi bi-check-lg"></i> Setujui
                                        </button>
                                        <button type="button" class="pengajuan-action-btn pengajuan-action-btn--tolak"
                                                onclick="openTolak('{{ $p->id }}','{{ addslashes($p->siswa->profile->nama ?? '-') }}')">
                                            <i class="bi bi-x-lg"></i> Tolak
                                        </button>
                                    @else
                                        <span class="status-badge" style="background:#F1F5F9;color:var(--simmas-muted);">
                                            Selesai
                                        </span>
                                        @if ($p->catatan_penolakan)
                                            <span class="d-block mt-1" style="font-size:.75rem;color:#DC2626;">
                                                <i class="bi bi-chat-left-text"></i> {{ Str::limit($p->catatan_penolakan, 40) }}
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <div class="py-2">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size:1.8rem;color:var(--simmas-border);"></i>
                                    Belum ada pengajuan magang dari siswa.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($pengajuanList->hasPages())
                <div class="penempatan-pagination">
                    <span>Menampilkan {{ $pengajuanList->firstItem() }}-{{ $pengajuanList->lastItem() }} dari {{ $pengajuanList->total() }} pengajuan</span>
                    {{ $pengajuanList->links() }}
                </div>
                @endif
            </div>

        </div>{{-- /tab-panel#tab-pengajuan --}}
        @endif

        {{-- ============================================================
            6. MODAL: TAMBAH PENEMPATAN MAGANG
            ============================================================ --}}
        <div class="modal fade simmas-modal" id="modalTambahPenempatan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form id="formTambahPenempatan">

                        <div class="modal-header">
                            <span class="simmas-modal__icon"><i class="bi bi-diagram-3-fill"></i></span>
                            <div class="flex-grow-1">
                                <p class="simmas-modal__title mb-0">Tambah Penempatan Magang</p>
                                <p class="simmas-modal__subtitle mb-0">Alokasikan siswa ke perusahaan mitra dan tentukan guru pembimbing.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="alert alert-danger py-2 small mb-3 simmas-form-error" data-error-for="_general"></div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pilih Siswa</label>
                                <select name="siswa_id" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih siswa --</option>
                                    @foreach ($siswaBelumMagang as $siswa)
                                        <option value="{{ $siswa->id }}">{{ $siswa->profile->nama ?? '-' }} (NIS: {{ $siswa->nis }} - {{ $siswa->kelas }})</option>
                                    @endforeach
                                </select>
                                <div class="simmas-form-error" data-error-for="siswa_id"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Tempat Magang (DUDI)</label>
                                <select name="tempat_magang_id" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih DUDI --</option>
                                    @foreach ($dudiList as $dudi)
                                        {{-- pakai accessor sisa_kuota (properti, bukan method) sesuai
                                            getSisaKuotaAttribute() di Model TempatMagang --}}
                                        <option value="{{ $dudi->id }}" @disabled($dudi->sisa_kuota <= 0)>
                                            {{ $dudi->nama_perusahaan }} (Sisa Kuota: {{ $dudi->sisa_kuota }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="simmas-form-error" data-error-for="tempat_magang_id"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Guru Pembimbing</label>
                                <select name="guru_id" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih guru --</option>
                                    @foreach ($guruList as $guru)
                                        <option value="{{ $guru->id }}">{{ $guru->profile->nama ?? '-' }}</option>
                                    @endforeach
                                </select>
                                <div class="simmas-form-error" data-error-for="guru_id"></div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control" required>
                                    <div class="simmas-form-error" data-error-for="tanggal_mulai"></div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control" required>
                                    <div class="simmas-form-error" data-error-for="tanggal_selesai"></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn hero-simmas__btn-outline" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn simmas-btn-primary-sm">Simpan Penempatan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ============================================================
            7. MODAL: EDIT PENEMPATAN MAGANG
            ============================================================ --}}
        <div class="modal fade simmas-modal" id="modalEditPenempatan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form id="formEditPenempatan" data-penempatan-id="">

                        <div class="modal-header">
                            <span class="simmas-modal__icon"><i class="bi bi-pencil-fill"></i></span>
                            <div class="flex-grow-1">
                                <p class="simmas-modal__title mb-0">Edit Penempatan Magang</p>
                                <p class="simmas-modal__subtitle mb-0">Ubah lokasi magang, pembimbing, atau jadwal.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Siswa</label>
                                <input type="text" id="editSiswaNama" class="form-control" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Perusahaan DUDI Tujuan</label>
                                <select name="tempat_magang_id" id="editDudiId" class="form-select" required>
                                    @foreach ($dudiList as $dudi)
                                        <option value="{{ $dudi->id }}">{{ $dudi->nama_perusahaan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Guru Pembimbing</label>
                                <select name="guru_id" id="editGuruId" class="form-select" required>
                                    @foreach ($guruList as $guru)
                                        <option value="{{ $guru->id }}">{{ $guru->profile->nama ?? '-' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" id="editTanggalMulai" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" id="editTanggalSelesai" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn hero-simmas__btn-outline" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn simmas-btn-primary-sm">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ============================================================
            8. MODAL: SAHKAN STATUS PENEMPATAN
            ============================================================ --}}
        <div class="modal fade simmas-modal" id="modalSahkanStatus" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form id="formSahkanStatus" data-penempatan-id="">

                        <div class="modal-header">
                            <span class="simmas-modal__icon"><i class="bi bi-arrow-repeat"></i></span>
                            <div class="flex-grow-1">
                                <p class="simmas-modal__title mb-0">Sahkan Penempatan</p>
                                <p class="simmas-modal__subtitle mb-0">Ubah status pengesahan penempatan magang siswa.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label class="form-label small fw-semibold">Status Penempatan</label>
                            <select name="status_pengesahan" id="sahkanStatusSelect" class="form-select" required>
                                <option value="disahkan">Disahkan (Sedang Magang)</option>
                                <option value="lulus_magang">Lulus Magang</option>
                                <option value="belum_disahkan">Belum Disahkan</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn hero-simmas__btn-outline" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn simmas-btn-primary-sm">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ============================================================
            9. ALERT DIALOG: KONFIRMASI BATALKAN PENEMPATAN
            ============================================================ --}}
        <div class="modal fade simmas-modal" id="modalBatalkanPenempatan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form id="formBatalkanPenempatan" data-penempatan-id="">

                        <div class="modal-body text-center pt-4">
                            <span class="simmas-alert__icon mx-auto"><i class="bi bi-exclamation-triangle-fill"></i></span>
                            <p class="fw-bold mb-2">Batalkan Penempatan Magang?</p>
                            <p class="text-muted small mb-0">
                                Apakah Anda yakin ingin membatalkan alokasi penempatan untuk siswa
                                <strong id="batalkanNamaSiswa">-</strong> di
                                "<strong id="batalkanNamaDudi">-</strong>"? Status siswa akan kembali menjadi 'Belum Magang'.
                            </p>
                        </div>

                        <div class="modal-footer justify-content-center border-top-0 pb-4">
                            <button type="button" class="btn hero-simmas__btn-outline" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning text-white fw-semibold">Ya, Batalkan Penempatan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ============================================================
            10. MODAL: KONFIRMASI SETUJUI PENGAJUAN
            ============================================================ --}}
        <div class="modal fade" id="modalSetujuiPengajuan" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header" style="background:var(--simmas-green-light);border-bottom:1px solid #BBF7D0;">
                        <h5 class="modal-title" style="color:var(--simmas-green);font-weight:700;font-size:1rem;">
                            <i class="bi bi-check-circle-fill me-2"></i>Setujui Pengajuan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:20px;">
                        <p style="font-size:.85rem;color:var(--simmas-ink);margin-bottom:0;">
                            Setujui pengajuan magang dari <strong id="setujuiNamaSiswa"></strong>
                            ke <strong id="setujuiNamaDudi"></strong>?
                        </p>
                        <div class="mt-3">
                            <label for="validasiGuruId" class="form-label fw-semibold small">Guru Pembimbing</label>
                            <select id="validasiGuruId" class="form-select form-select-sm">
                                <option value="">Pilih guru pembimbing</option>
                                @foreach ($guruList as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->profile->nama ?? '-' }}</option>
                                @endforeach
                            </select>
                            <small id="validasiGuruError" class="text-danger d-none">Guru pembimbing wajib dipilih.</small>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid var(--simmas-border);">
                        <button type="button" class="btn hero-simmas__btn-outline btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm rounded-pill px-4"
                                id="btnSetujuiConfirm"
                                style="background:var(--simmas-green);color:#fff;font-weight:600;">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="spinnerSetujui"></span>
                            Ya, Setujui
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================
            11. MODAL: TOLAK PENGAJUAN
            ============================================================ --}}
        <div class="modal fade" id="modalTolakPengajuan" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header" style="background:#FEF2F2;border-bottom:1px solid #FECACA;">
                        <h5 class="modal-title" style="color:#DC2626;font-weight:700;font-size:1rem;">
                            <i class="bi bi-x-circle-fill me-2"></i>Tolak Pengajuan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:22px;">
                        <p style="font-size:.85rem;color:var(--simmas-ink);margin-bottom:14px;">
                            Tolak pengajuan magang dari <strong id="tolakNamaSiswa"></strong>?
                            <br><span style="font-size:.78rem;color:var(--simmas-muted);">Sertakan alasan penolakan yang jelas agar siswa memahami alasan ini.</span>
                        </p>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">
                                Catatan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea id="catatanPenolakan" class="form-control" rows="3"
                                    placeholder="Contoh: Kuota DUDI ini sudah penuh, silakan pilih DUDI lain..."
                                    maxlength="500" style="font-size:.85rem;resize:none;"></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-danger d-none" id="catatanError">Catatan penolakan wajib diisi.</small>
                                <small class="text-muted ms-auto" id="catatanCount">0 / 500</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid var(--simmas-border);">
                        <button type="button" class="btn hero-simmas__btn-outline btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-pill px-4"
                                id="btnTolakConfirm" style="font-weight:600;">
                            <span class="spinner-border spinner-border-sm d-none me-1" id="spinnerTolak"></span>
                            Ya, Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    {{-- ============================================================
        JS:
        1) isi otomatis field modal Edit/Sahkan/Batalkan dari atribut
            data-* tombol yang diklik (event 'show.bs.modal')
        2) kirim semua form via fetch (AJAX) ke endpoint JSON,
            karena controller store/update/sahkan/batalkan me-return
            response()->json(), bukan redirect
        ============================================================ --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const baseUrl   = "{{ url('/admin/penempatan') }}";

        /* =========================================================
        2. AJAX FORM HELPER
        ========================================================= */
        async function kirimForm(form, url, method) {
            const formData = new FormData(form);

            form.querySelectorAll('.simmas-form-error').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: (() => {
                        if (method !== 'POST') formData.append('_method', method);
                        return formData;
                    })(),
                });

                const data = await response.json();

                if (response.status === 422) {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const el = form.querySelector(`[data-error-for="${field}"]`);
                            if (el) {
                                el.textContent = data.errors[field][0];
                                el.style.display = 'block';
                            }
                        });
                    } else if (data.message) {
                        const el = form.querySelector('[data-error-for="_general"]');
                        if (el) { el.textContent = data.message; el.style.display = 'block'; }
                    }
                    return;
                }

                if (!response.ok) {
                    alert(data.message ?? 'Terjadi kesalahan, silakan coba lagi.');
                    return;
                }

                window.location.reload();

            } catch (error) {
                alert('Gagal menghubungi server. Periksa koneksi Anda.');
            }
        }

        document.getElementById('formTambahPenempatan').addEventListener('submit', function (e) {
            e.preventDefault();
            kirimForm(this, baseUrl, 'POST');
        });

        const modalEdit = document.getElementById('modalEditPenempatan');
        modalEdit.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const form = document.getElementById('formEditPenempatan');

            form.dataset.penempatanId = btn.dataset.id;
            document.getElementById('editSiswaNama').value = btn.dataset.siswaNama;
            document.getElementById('editDudiId').value = btn.dataset.dudiId;
            document.getElementById('editGuruId').value = btn.dataset.guruId;
            document.getElementById('editTanggalMulai').value = btn.dataset.mulai;
            document.getElementById('editTanggalSelesai').value = btn.dataset.selesai;
        });

        document.getElementById('formEditPenempatan').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = this.dataset.penempatanId;
            kirimForm(this, `${baseUrl}/${id}`, 'PUT');
        });

        const modalSahkan = document.getElementById('modalSahkanStatus');
        modalSahkan.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const form = document.getElementById('formSahkanStatus');

            form.dataset.penempatanId = btn.dataset.id;
            document.getElementById('sahkanStatusSelect').value = btn.dataset.status;
        });

        document.getElementById('formSahkanStatus').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = this.dataset.penempatanId;
            kirimForm(this, `${baseUrl}/${id}/sahkan`, 'PATCH');
        });

        const modalBatal = document.getElementById('modalBatalkanPenempatan');
        modalBatal.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const form = document.getElementById('formBatalkanPenempatan');

            form.dataset.penempatanId = btn.dataset.id;
            document.getElementById('batalkanNamaSiswa').textContent = btn.dataset.nama;
            document.getElementById('batalkanNamaDudi').textContent = btn.dataset.dudi;
        });

        document.getElementById('formBatalkanPenempatan').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = this.dataset.penempatanId;
            kirimForm(this, `${baseUrl}/${id}`, 'DELETE');
        });

        /* =========================================================
        3. PENGAJUAN MAGANG (SETUJUI & TOLAK)
        ========================================================= */
        let pendingSetujuiId = null;
        window.konfirmasiSetujui = function(id, namaSiswa, namaDudi) {
            pendingSetujuiId = id;
            document.getElementById('setujuiNamaSiswa').textContent = namaSiswa;
            document.getElementById('setujuiNamaDudi').textContent  = namaDudi;
            document.getElementById('validasiGuruId').value = '';
            document.getElementById('validasiGuruError').classList.add('d-none');
            new bootstrap.Modal(document.getElementById('modalSetujuiPengajuan')).show();
        };

        document.getElementById('btnSetujuiConfirm').addEventListener('click', async function () {
            if (!pendingSetujuiId) return;

            const guruId = document.getElementById('validasiGuruId').value;
            if (!guruId) {
                document.getElementById('validasiGuruError').classList.remove('d-none');
                return;
            }

            const btn     = this;
            const spinner = document.getElementById('spinnerSetujui');
            btn.disabled  = true;
            spinner.classList.remove('d-none');

            try {
                const body = new FormData();
                body.append('guru_id', guruId);
                body.append('_method', 'PATCH');
                const res  = await fetch(`${baseUrl}/${pendingSetujuiId}/validasi-pengajuan`, {
                    method:  'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body,
                });
                const data = await res.json();

                if (res.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('modalSetujuiPengajuan')).hide();
                    window.location.reload();
                } else {
                    alert(data.message ?? 'Gagal memvalidasi pengajuan.');
                }
            } catch (e) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        });

        let pendingTolakId = null;
        window.openTolak = function(id, namaSiswa) {
            pendingTolakId = id;
            document.getElementById('tolakNamaSiswa').textContent = namaSiswa;
            document.getElementById('catatanPenolakan').value    = '';
            document.getElementById('catatanCount').textContent  = '0 / 500';
            document.getElementById('catatanError').classList.add('d-none');
            new bootstrap.Modal(document.getElementById('modalTolakPengajuan')).show();
        };

        document.getElementById('catatanPenolakan').addEventListener('input', function () {
            document.getElementById('catatanCount').textContent = this.value.length + ' / 500';
            if (this.value.trim()) {
                document.getElementById('catatanError').classList.add('d-none');
            }
        });

        document.getElementById('btnTolakConfirm').addEventListener('click', async function () {
            if (!pendingTolakId) return;

            const catatan = document.getElementById('catatanPenolakan').value.trim();
            if (!catatan) {
                document.getElementById('catatanError').classList.remove('d-none');
                return;
            }

            const btn     = this;
            const spinner = document.getElementById('spinnerTolak');
            btn.disabled  = true;
            spinner.classList.remove('d-none');

            try {
                const body = new FormData();
                body.append('catatan_penolakan', catatan);
                body.append('_method', 'PATCH');

                const res  = await fetch(`${baseUrl}/${pendingTolakId}/tolak-pengajuan`, {
                    method:  'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body,
                });
                const data = await res.json();

                if (res.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('modalTolakPengajuan')).hide();
                    window.location.reload();
                } else {
                    if (data.errors?.catatan_penolakan) {
                        document.getElementById('catatanError').textContent = data.errors.catatan_penolakan[0];
                        document.getElementById('catatanError').classList.remove('d-none');
                    } else {
                        alert(data.message ?? 'Gagal menolak pengajuan.');
                    }
                }
            } catch (e) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                spinner.classList.add('d-none');
            }
        });

    });
    </script>
    @endpush

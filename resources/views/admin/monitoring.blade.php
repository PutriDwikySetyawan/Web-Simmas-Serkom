@extends('layouts.admin')

@section('title', 'Monitoring Global')
@section('page-title', 'Monitoring Global')

@push('styles')
<style>
    /* Gunakan seluruh lebar area utama admin tanpa ruang kosong di sisi luar. */
    .admin-main .admin-content {
        width: 100%;
        max-width: none;
        margin: 0;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    @media (max-width: 991px) {
        .admin-main .admin-content {
            padding-left: 20px;
            padding-right: 20px;
        }
    }

    @media (max-width: 575.98px) {
        .admin-main .admin-content {
            padding-left: 12px;
            padding-right: 12px;
        }
    }

    /* =========================================================
       STAT CARD RINGKAS (opsional, konsisten dgn halaman lain)
    ========================================================= */

    .mon-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;

        margin-bottom: 20px;
    }

    .mon-stat-card {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        padding: 16px 18px;
    }

    .mon-stat-card__label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        margin-bottom: 6px;
    }

    .mon-stat-card__value {
        font-size: 1.4rem;
        font-weight: 800;
    }

    .mon-stat-card__hint {
        font-size: 0.72rem;
        color: var(--simmas-muted);
    }

    /* =========================================================
       PANEL UTAMA (FILTER + TABEL)
    ========================================================= */

    .mon-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;
        overflow: hidden;
        padding: 0;
    }

    /* ---------------------------------------------------
       TOOLBAR: SEARCH + FILTER KELAS
    --------------------------------------------------- */

    .mon-toolbar {
        display: flex;
        align-items: center;
        gap: 10px;

        margin: 0;
        padding: 20px;
    }

    .mon-table-wrap {
        width: calc(100% - 24px);
        margin: 0 12px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 575.98px) {
        .mon-table-wrap {
            width: calc(100% - 16px);
            margin: 0 8px;
        }
    }

    .mon-toolbar__search {
        flex: 1;

        display: flex;
        align-items: center;
        gap: 8px;

        padding: 8px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        color: var(--simmas-muted);
    }

    .mon-toolbar__search input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 0.86rem;
    }

    .mon-toolbar__select {
        padding: 8px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        font-size: 0.86rem;
        color: var(--simmas-ink);

        background: #fff;
    }

    .mon-toolbar__count {
        font-size: 0.78rem;
        color: var(--simmas-muted);
        white-space: nowrap;
    }

    /* ---------------------------------------------------
       TABEL MONITORING
    --------------------------------------------------- */

    .mon-table {
        width: 100%;
        min-width: 820px;
        display: table !important;
        border-collapse: collapse;
    }

    .mon-table th {
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

    .mon-table td {
        padding: 12px 8px;
        font-size: 0.84rem;
        vertical-align: middle;

        border-bottom: 1px solid var(--simmas-border);
    }

    .mon-table tr:last-child td {
        border-bottom: none;
    }

    .mon-pagination { padding: 0 20px 20px; }

    .mon-siswa-cell strong {
        display: block;
        font-size: 0.85rem;
    }

    .mon-siswa-cell span {
        font-size: 0.75rem;
        color: var(--simmas-muted);
    }

    .mon-dudi-cell strong {
        display: block;
        font-size: 0.82rem;
    }

    .mon-dudi-cell span {
        font-size: 0.75rem;
        color: var(--simmas-muted);
    }

    /* Rekap presensi H/S/I/A dalam format singkat berwarna */
    .mon-rekap span {
        display: inline-block;
        min-width: 18px;
        font-weight: 700;
    }

    .mon-rekap .h { color: #16a34a; }
    .mon-rekap .s { color: #d97706; }
    .mon-rekap .i { color: #2563eb; }
    .mon-rekap .a { color: #dc2626; }

    /* Badge status keaktifan */
    .mon-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        padding: 3px 10px;
        border-radius: 999px;

        font-size: 0.72rem;
        font-weight: 700;
    }

    .mon-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .mon-badge--aktif {
        background: #dcfce7;
        color: #16a34a;
    }

    .mon-badge--perhatian {
        background: #fef3c7;
        color: #d97706;
    }

    .mon-badge--bermasalah {
        background: #fee2e2;
        color: #dc2626;
    }

    .mon-time-hint {
        display: block;
        font-size: 0.72rem;
        color: var(--simmas-muted);
        margin-top: 2px;
    }

    .mon-detail-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        padding: 6px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 8px;

        background: #fff;
        color: var(--simmas-ink);

        font-size: 0.78rem;
        font-weight: 600;

        cursor: pointer;
    }

    .mon-detail-btn:hover {
        border-color: var(--simmas-blue);
        color: var(--simmas-blue);
    }

    /* ---------------------------------------------------
       EMPTY STATE
    --------------------------------------------------- */

    .mon-empty {
        text-align: center;
        padding: 40px 0;
        color: var(--simmas-muted);
        font-size: 0.86rem;
    }

    .mon-empty i {
        font-size: 1.8rem;
        display: block;
        margin-bottom: 8px;
    }

    /* =========================================================
       MODAL DETAIL MONITORING (dibangun manual, non-Bootstrap,
       supaya kita kontrol penuh saat diisi data via fetch)
    ========================================================= */

    .mon-modal-overlay {
        position: fixed;
        inset: 0;

        background: rgba(15, 23, 42, 0.55);

        display: none;
        align-items: center;
        justify-content: center;

        z-index: 1000;
    }

    .mon-modal-overlay.is-active {
        display: flex;
    }

    .mon-modal {
        width: 100%;
        max-width: 420px;

        background: #fff;
        border-radius: 16px;

        padding: 22px;
    }

    .mon-modal__header {
        display: flex;
        align-items: center;
        gap: 10px;

        margin-bottom: 16px;
    }

    .mon-modal__header-icon {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: var(--simmas-blue-light);
        color: var(--simmas-blue);
    }

    .mon-modal__title {
        font-size: 0.98rem;
        font-weight: 700;
    }

    .mon-modal__subtitle {
        font-size: 0.76rem;
        color: var(--simmas-muted);
    }

    .mon-modal__info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;

        margin-bottom: 16px;
    }

    .mon-modal__info-label {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        margin-bottom: 3px;
    }

    .mon-modal__info-value {
        font-size: 0.86rem;
        font-weight: 600;
    }

    .mon-modal__section-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        margin-bottom: 8px;
    }

    .mon-modal__rekap-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;

        margin-bottom: 16px;
    }

    .mon-modal__rekap-card {
        text-align: center;

        padding: 10px 4px;

        border-radius: 10px;
        background: var(--simmas-bg);
    }

    .mon-modal__rekap-value {
        font-size: 1.1rem;
        font-weight: 800;
    }

    .mon-modal__rekap-label {
        font-size: 0.68rem;
        color: var(--simmas-muted);
    }

    .mon-modal__jurnal-box {
        padding: 12px 14px;

        border-radius: 10px;
        background: var(--simmas-bg);

        font-size: 0.82rem;
        font-style: italic;
        color: var(--simmas-ink);

        margin-bottom: 18px;
    }

    .mon-modal__close-btn {
        width: 100%;

        padding: 10px 0;

        border: none;
        border-radius: 10px;

        background: var(--simmas-blue);
        color: #fff;

        font-weight: 700;
        font-size: 0.86rem;

        cursor: pointer;
    }

    .mon-modal__loading {
        text-align: center;
        padding: 30px 0;
        color: var(--simmas-muted);
        font-size: 0.84rem;
    }
</style>
@endpush


@section('content')

{{-- =====================================================
     1. STAT CARD RINGKAS
     Diturunkan dari koleksi $siswaList halaman berjalan,
     hanya sebagai gambaran cepat (bukan agregat seluruh data).
====================================================== --}}

<div class="mon-stats">

    <div class="mon-stat-card">
        <p class="mon-stat-card__label">Total Siswa Aktif</p>
        <p class="mon-stat-card__value">{{ $siswaList->total() }}</p>
        <p class="mon-stat-card__hint">Siswa sedang magang</p>
    </div>

    <div class="mon-stat-card">
        <p class="mon-stat-card__label">Tingkat Kehadiran</p>
        <p class="mon-stat-card__value">
            {{ $siswaList->avg('rekap_hadir') ? round($siswaList->avg('rekap_hadir')) : 0 }}%
        </p>
        <p class="mon-stat-card__hint">Rata-rata kehadiran harian</p>
    </div>

    <div class="mon-stat-card">
        <p class="mon-stat-card__label">Jurnal Terkumpul</p>
        <p class="mon-stat-card__value">{{ $siswaList->sum('jumlah_jurnal') }}</p>
        <p class="mon-stat-card__hint">Total jurnal minggu ini</p>
    </div>

    <div class="mon-stat-card">
        <p class="mon-stat-card__label">Perlu Perhatian</p>
        <p class="mon-stat-card__value">
            {{ $siswaList->where('status_keaktifan', '!=', 'Aktif')->count() }}
        </p>
        <p class="mon-stat-card__hint">Siswa bermasalah/alfa</p>
    </div>

</div>


{{-- =====================================================
     2. PANEL UTAMA: TOOLBAR + TABEL
====================================================== --}}

<div class="mon-panel">

    {{-- ---------------------------------------------------
         2a. TOOLBAR: SEARCH REAL-TIME + FILTER KELAS
         Pakai GET form supaya URL bisa di-bookmark/share
         dan hasil filter tetap ada saat pagination.
    --------------------------------------------------- --}}

    <form method="GET" action="{{ route('admin.monitoring') }}" class="mon-toolbar">

        <div class="mon-toolbar__search">
            <i class="bi bi-search"></i>
            <input
                type="text"
                name="q"
                value="{{ $keyword }}"
                placeholder="Cari siswa atau DUDI..."
                onchange="this.form.submit()"
            >
        </div>

        <select name="kelas" class="mon-toolbar__select" onchange="this.form.submit()">
            <option value="semua" {{ $kelasAktif === 'semua' ? 'selected' : '' }}>
                Semua Kelas
            </option>

            @foreach ($kelasList as $kelas)
                <option value="{{ $kelas }}" {{ $kelasAktif === $kelas ? 'selected' : '' }}>
                    {{ $kelas }}
                </option>
            @endforeach
        </select>

        <span class="mon-toolbar__count">
            {{ $siswaList->total() }} Data
        </span>

    </form>


    {{-- ---------------------------------------------------
         2b. TABEL MONITORING GLOBAL
    --------------------------------------------------- --}}

    @if ($siswaList->count() > 0)

        <div class="mon-table-wrap">
        <table class="mon-table">
            <thead>
                <tr>
                    <th>Siswa & Kelas</th>
                    <th>DUDI & Pembimbing</th>
                    <th>Kehadiran (H/S/I/A)</th>
                    <th>Jurnal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($siswaList as $siswa)
                    <tr>
                        {{-- Kolom: Siswa & Kelas --}}
                        <td>
                            <div class="mon-siswa-cell">
                                <strong>{{ $siswa->profile->nama ?? '-' }}</strong>
                                <span>{{ $siswa->kelas }}</span>
                            </div>
                        </td>

                        {{-- Kolom: DUDI & Guru Pembimbing --}}
                        <td>
                            <div class="mon-dudi-cell">
                                <strong>{{ $siswa->penempatan->tempatMagang->nama_perusahaan ?? '-' }}</strong>
                                <span>{{ $siswa->penempatan->guru->profile->nama ?? 'Belum ada pembimbing' }}</span>
                            </div>
                        </td>

                        {{-- Kolom: Rekap Presensi H/S/I/A --}}
                        <td>
                            <div class="mon-rekap">
                                <span class="h">{{ $siswa->rekap_hadir }}</span> /
                                <span class="s">{{ $siswa->rekap_sakit }}</span> /
                                <span class="i">{{ $siswa->rekap_izin }}</span> /
                                <span class="a">{{ $siswa->rekap_alfa }}</span>
                            </div>
                        </td>

                        {{-- Kolom: Jumlah Jurnal --}}
                        <td>{{ $siswa->jumlah_jurnal }}</td>

                        {{-- Kolom: Status & Terakhir Aktif --}}
                        <td>
                            @php
                                $badgeClass = match ($siswa->status_keaktifan) {
                                    'Aktif'           => 'mon-badge--aktif',
                                    'Perlu Perhatian' => 'mon-badge--perhatian',
                                    'Bermasalah'      => 'mon-badge--bermasalah',
                                    default           => 'mon-badge--aktif',
                                };
                            @endphp

                            <span class="mon-badge {{ $badgeClass }}">
                                {{ $siswa->status_keaktifan }}
                            </span>

                            <span class="mon-time-hint">
                                {{ $siswa->terakhir_aktif ? \Carbon\Carbon::parse($siswa->terakhir_aktif)->diffForHumans() : 'Belum ada aktivitas' }}
                            </span>
                        </td>

                        {{-- Kolom: Aksi (buka modal detail via AJAX) --}}
                        <td>
                            <button
                                type="button"
                                class="mon-detail-btn"
                                onclick="bukaDetailSiswa('{{ $siswa->id }}')"
                            >
                                <i class="bi bi-eye"></i>
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        {{-- Pagination bawaan Laravel --}}
        <div class="mon-pagination mt-3">
            {{ $siswaList->links() }}
        </div>

    @else

        {{-- ---------------------------------------------------
             2c. EMPTY STATE
             Ditampilkan kalau tidak ada data yang cocok
             dengan kriteria pencarian/filter.
        --------------------------------------------------- --}}

        <div class="mon-empty">
            <i class="bi bi-inbox"></i>
            Tidak ada data siswa yang cocok dengan kriteria pencarian.
        </div>

    @endif

</div>


{{-- =====================================================
     3. MODAL DETAIL MONITORING SISWA
     Struktur HTML statis, isinya diisi via JavaScript
     setelah fetch ke route admin.monitoring.detail.
====================================================== --}}

<div class="mon-modal-overlay" id="modalDetailOverlay">
    <div class="mon-modal">

        <div class="mon-modal__header">
            <span class="mon-modal__header-icon">
                <i class="bi bi-clipboard-data"></i>
            </span>

            <div>
                <p class="mon-modal__title">Detail Monitoring Magang</p>
                <p class="mon-modal__subtitle">Rincian aktivitas, kehadiran, dan progres magang siswa.</p>
            </div>
        </div>

        {{-- Loading state, ditampilkan sementara fetch berjalan --}}
        <div class="mon-modal__loading" id="modalDetailLoading">
            Memuat data...
        </div>

        {{-- Konten detail, disembunyikan dulu sampai data siap --}}
        <div id="modalDetailContent" style="display: none;">

            <div class="mon-modal__info-grid">
                <div>
                    <p class="mon-modal__info-label">Nama Siswa</p>
                    <p class="mon-modal__info-value" id="detailNamaSiswa">-</p>
                </div>

                <div>
                    <p class="mon-modal__info-label">Tempat Magang</p>
                    <p class="mon-modal__info-value" id="detailTempatMagang">-</p>
                </div>
            </div>

            <p class="mon-modal__section-label">Rekap Kehadiran</p>

            <div class="mon-modal__rekap-grid">
                <div class="mon-modal__rekap-card">
                    <p class="mon-modal__rekap-value" id="detailRekapHadir">0</p>
                    <p class="mon-modal__rekap-label">Hadir</p>
                </div>
                <div class="mon-modal__rekap-card">
                    <p class="mon-modal__rekap-value" id="detailRekapSakit">0</p>
                    <p class="mon-modal__rekap-label">Sakit</p>
                </div>
                <div class="mon-modal__rekap-card">
                    <p class="mon-modal__rekap-value" id="detailRekapIzin">0</p>
                    <p class="mon-modal__rekap-label">Izin</p>
                </div>
                <div class="mon-modal__rekap-card">
                    <p class="mon-modal__rekap-value" id="detailRekapAlfa">0</p>
                    <p class="mon-modal__rekap-label">Alfa</p>
                </div>
            </div>

            <p class="mon-modal__section-label">Jurnal Terakhir</p>

            <div class="mon-modal__jurnal-box" id="detailJurnalTerakhir">
                -
            </div>

        </div>

        <button type="button" class="mon-modal__close-btn" onclick="tutupModalDetail()">
            Tutup Detail
        </button>

    </div>
</div>

@endsection


@push('scripts')
<script>

/* =========================================================
   MODAL DETAIL MONITORING — AJAX FETCH
   ========================================================= */

const modalDetailOverlay = document.getElementById('modalDetailOverlay');
const modalDetailLoading = document.getElementById('modalDetailLoading');
const modalDetailContent = document.getElementById('modalDetailContent');

/**
 * Buka modal detail siswa berdasarkan ID.
 * Mengambil data dari endpoint:
 *   GET /admin/monitoring/{siswa}/detail
 * yang dikembalikan sebagai JSON oleh DashboardController::detail().
 */
async function bukaDetailSiswa(siswaId) {

    // Tampilkan modal dalam kondisi loading
    modalDetailOverlay.classList.add('is-active');
    modalDetailLoading.style.display = 'block';
    modalDetailContent.style.display = 'none';

    try {
        const response = await fetch(`/admin/monitoring/${siswaId}/detail`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Gagal mengambil data detail siswa.');
        }

        const data = await response.json();

        // Isi elemen-elemen modal dengan data hasil fetch
        document.getElementById('detailNamaSiswa').textContent = data.nama_siswa;
        document.getElementById('detailTempatMagang').textContent = data.tempat_magang;

        document.getElementById('detailRekapHadir').textContent = data.rekap.hadir;
        document.getElementById('detailRekapSakit').textContent = data.rekap.sakit;
        document.getElementById('detailRekapIzin').textContent = data.rekap.izin;
        document.getElementById('detailRekapAlfa').textContent = data.rekap.alfa;

        document.getElementById('detailJurnalTerakhir').textContent = data.jurnal_terakhir;

        // Tampilkan konten, sembunyikan loading
        modalDetailLoading.style.display = 'none';
        modalDetailContent.style.display = 'block';

    } catch (error) {
        modalDetailLoading.textContent = 'Terjadi kesalahan saat memuat data. Coba lagi.';
        console.error(error);
    }
}

/**
 * Tutup modal detail dan reset kondisi ke loading
 * supaya bersih saat dibuka lagi untuk siswa lain.
 */
function tutupModalDetail() {
    modalDetailOverlay.classList.remove('is-active');
    modalDetailLoading.style.display = 'block';
    modalDetailContent.style.display = 'none';
}

// Klik di luar area modal (overlay) juga menutup modal
modalDetailOverlay.addEventListener('click', function (event) {
    if (event.target === modalDetailOverlay) {
        tutupModalDetail();
    }
});

</script>
@endpush

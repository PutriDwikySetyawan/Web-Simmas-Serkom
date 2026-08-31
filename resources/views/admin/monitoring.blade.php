@extends('layouts.admin')

@section('title', 'Monitoring Global')
@section('page-title', 'Monitoring Global')

@push('styles')
<style>
    /* =========================================================
       STAT CARD (4 KARTU)
       Mengikuti bahasa desain stat-card di DUDI & Dashboard.
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

        padding: 18px 20px;

        transition: box-shadow 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
    }

    .mon-stat-card:hover {
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
        transform: translateY(-2px);
        border-color: #dbe3ee;
    }

    .mon-stat-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;

        margin-bottom: 10px;
    }

    .mon-stat-card__label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;

        color: var(--simmas-muted);
    }

    .mon-stat-card__icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        font-size: 1rem;
    }

    .mon-stat-card__value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--simmas-ink);

        margin-bottom: 2px;
    }

    .mon-stat-card__hint {
        font-size: 0.76rem;
        color: var(--simmas-muted);
    }

    /* =========================================================
       PANEL UTAMA (search, filter, tabel)
    ========================================================= */

    .mon-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 16px;
        padding: 22px;
    }

    /* ---------------------------------------------------
       SEARCH & FILTER
    --------------------------------------------------- */

    .mon-panel .input-group-text {
        border-radius: 10px 0 0 10px !important;
        border-color: var(--simmas-border) !important;
        color: var(--simmas-muted);
    }

    .mon-panel .form-control,
    .mon-panel .form-select {
        border-color: var(--simmas-border);
        font-size: 0.86rem;
    }

    .mon-panel .form-control {
        border-radius: 0 10px 10px 0 !important;
    }

    .mon-panel .form-select {
        border-radius: 10px;
    }

    .mon-panel .form-control:focus,
    .mon-panel .form-select:focus {
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    .mon-count-badge {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--simmas-muted);
        white-space: nowrap;
    }

    /* ---------------------------------------------------
       TABEL MONITORING
    --------------------------------------------------- */

    .mon-table thead th {
        background: var(--simmas-bg);

        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--simmas-muted);

        border: none;
        padding: 12px 14px;
    }

    .mon-table thead tr th:first-child {
        border-radius: 10px 0 0 10px;
    }

    .mon-table thead tr th:last-child {
        border-radius: 0 10px 10px 0;
    }

    .mon-table tbody td {
        padding: 14px;
        font-size: 0.86rem;
        color: var(--simmas-ink);

        border-bottom: 1px solid var(--simmas-border);
        vertical-align: middle;
    }

    .mon-table tbody tr:last-child td {
        border-bottom: none;
    }

    .mon-table tbody tr {
        transition: background 0.15s ease;
    }

    .mon-table tbody tr:hover {
        background: var(--simmas-bg);
    }

    .mon-siswa-cell strong {
        display: block;
        font-size: 0.88rem;
        color: var(--simmas-ink);
    }

    .mon-siswa-cell span {
        font-size: 0.76rem;
        color: var(--simmas-muted);
    }

    .mon-dudi-cell strong {
        display: block;
        font-size: 0.85rem;
        color: var(--simmas-ink);
    }

    .mon-dudi-cell span {
        font-size: 0.76rem;
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

        padding: 4px 10px;
        border-radius: 999px;

        font-size: 0.74rem;
        font-weight: 700;
    }

    .mon-badge i {
        font-size: 0.55rem;
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
        transition: all 0.15s ease;
    }

    .mon-detail-btn:hover {
        border-color: var(--simmas-blue);
        color: var(--simmas-blue);
        background: var(--simmas-blue-light);
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
       MODAL DETAIL MONITORING
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
        max-width: 440px;

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
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: var(--simmas-blue-light);
        color: var(--simmas-blue);
        font-size: 1rem;
    }

    .mon-modal__title {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--simmas-ink);
        margin-bottom: 2px;
    }

    .mon-modal__subtitle {
        font-size: 0.76rem;
        color: var(--simmas-muted);
        margin-bottom: 0;
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
        color: var(--simmas-ink);
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
        margin-bottom: 0;
    }

    .mon-modal__rekap-label {
        font-size: 0.68rem;
        color: var(--simmas-muted);
        margin-bottom: 0;
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
        transition: background 0.15s ease;
    }

    .mon-modal__close-btn:hover {
        background: var(--simmas-blue-dark);
    }

    .mon-modal__loading {
        text-align: center;
        padding: 30px 0;
        color: var(--simmas-muted);
        font-size: 0.84rem;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 992px) {
        .mon-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .mon-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush


@section('content')

<div class="container-fluid py-4">

    {{-- =====================================================
         1. STAT CARD RINGKAS (4 KARTU)
         Diturunkan dari koleksi $siswaList halaman berjalan
    ====================================================== --}}

    <div class="mon-stats">

        {{-- Card: Total Siswa Aktif --}}
        <div class="mon-stat-card">
            <div class="mon-stat-card__top">
                <span class="mon-stat-card__label">Total Siswa Aktif</span>
                <span class="mon-stat-card__icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-people-fill"></i>
                </span>
            </div>
            <div class="mon-stat-card__value">{{ $siswaList->total() }}</div>
            <div class="mon-stat-card__hint">siswa sedang magang</div>
        </div>

        {{-- Card: Tingkat Kehadiran --}}
        <div class="mon-stat-card">
            <div class="mon-stat-card__top">
                <span class="mon-stat-card__label">Tingkat Kehadiran</span>
                <span class="mon-stat-card__icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="bi bi-calendar-check-fill"></i>
                </span>
            </div>
            <div class="mon-stat-card__value">
                {{ $siswaList->avg('rekap_hadir') ? round($siswaList->avg('rekap_hadir')) : 0 }}%
            </div>
            <div class="mon-stat-card__hint">rata-rata kehadiran harian</div>
        </div>

        {{-- Card: Jurnal Terkumpul --}}
        <div class="mon-stat-card">
            <div class="mon-stat-card__top">
                <span class="mon-stat-card__label">Jurnal Terkumpul</span>
                <span class="mon-stat-card__icon" style="background: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-journal-text"></i>
                </span>
            </div>
            <div class="mon-stat-card__value">{{ $siswaList->sum('jumlah_jurnal') }}</div>
            <div class="mon-stat-card__hint">total jurnal minggu ini</div>
        </div>

        {{-- Card: Perlu Perhatian --}}
        <div class="mon-stat-card">
            <div class="mon-stat-card__top">
                <span class="mon-stat-card__label">Perlu Perhatian</span>
                <span class="mon-stat-card__icon" style="background: #fee2e2; color: #dc2626;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </span>
            </div>
            <div class="mon-stat-card__value">
                {{ $siswaList->where('status_keaktifan', '!=', 'Aktif')->count() }}
            </div>
            <div class="mon-stat-card__hint">siswa bermasalah/alfa</div>
        </div>

    </div>


    {{-- =====================================================
         2. PANEL UTAMA: SEARCH, FILTER & TABEL
    ====================================================== --}}

    <div class="mon-panel">

        {{-- ---------------------------------------------------
             2a. BARIS SEARCH & FILTER
             Pakai GET form supaya URL bisa di-bookmark/share
        --------------------------------------------------- --}}

        <form method="GET" action="{{ route('admin.monitoring') }}" class="row g-2 mb-3 align-items-center">

            {{-- Input pencarian --}}
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input
                        type="text"
                        name="q"
                        class="form-control border-start-0"
                        placeholder="Cari siswa atau DUDI..."
                        value="{{ $keyword }}"
                    >
                </div>
            </div>

            {{-- Filter Kelas --}}
            <div class="col-md-3">
                <select name="kelas" class="form-select" onchange="this.form.submit()">
                    <option value="semua" {{ $kelasAktif === 'semua' ? 'selected' : '' }}>
                        Semua Kelas
                    </option>

                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ $kelasAktif === $kelas ? 'selected' : '' }}>
                            {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jumlah data di ujung kanan --}}
            <div class="col-md-4 d-flex justify-content-end align-items-center">
                <span class="mon-count-badge">
                    {{ $siswaList->total() }} Data Siswa
                </span>
            </div>

        </form>


        {{-- ---------------------------------------------------
             2b. TABEL MONITORING GLOBAL
        --------------------------------------------------- --}}

        @if ($siswaList->count() > 0)

            <div class="table-responsive">
                <table class="table mon-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Siswa & Kelas</th>
                            <th>DUDI & Pembimbing</th>
                            <th>Kehadiran (H/S/I/A)</th>
                            <th>Jurnal</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
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
                                        <i class="bi bi-circle-fill"></i> {{ $siswa->status_keaktifan }}
                                    </span>

                                    <span class="mon-time-hint">
                                        {{ $siswa->terakhir_aktif ? \Carbon\Carbon::parse($siswa->terakhir_aktif)->diffForHumans() : 'Belum ada aktivitas' }}
                                    </span>
                                </td>

                                {{-- Kolom: Aksi (buka modal detail via AJAX) --}}
                                <td class="text-end">
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
            <div class="mt-3">
                {{ $siswaList->links() }}
            </div>

        @else

            {{-- ---------------------------------------------------
                 2c. EMPTY STATE
            --------------------------------------------------- --}}

            <div class="mon-empty">
                <i class="bi bi-inbox"></i>
                Tidak ada data siswa yang cocok dengan kriteria pencarian.
            </div>

        @endif

    </div>

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

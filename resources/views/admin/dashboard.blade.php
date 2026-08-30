@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* =========================================================
       HERO BANNER
    ========================================================= */

    .dash-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 26px 28px;
        margin-bottom: 20px;

        border-radius: 16px;

        background: linear-gradient(
            135deg,
            var(--simmas-blue) 0%,
            var(--simmas-blue-dark) 100%
        );

        color: #fff;
    }

    .dash-hero__date {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;

        color: rgba(255, 255, 255, 0.75);

        margin-bottom: 6px;
    }

    .dash-hero__title {
        font-size: 1.3rem;
        font-weight: 800;

        margin-bottom: 6px;
    }

    .dash-hero__subtitle {
        font-size: 0.86rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .dash-hero__action {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 9px 16px;

        background: #fff;
        color: var(--simmas-blue);

        border-radius: 8px;

        font-size: 0.82rem;
        font-weight: 700;

        text-decoration: none;

        white-space: nowrap;
    }

    /* =========================================================
       STAT CARD (4 KARTU)
    ========================================================= */

    .dash-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;

        margin-bottom: 20px;
    }

    .dash-stat-card {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        padding: 18px 20px;
    }

    .dash-stat-card__top {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 10px;
    }

    .dash-stat-card__label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;

        color: var(--simmas-muted);
    }

    .dash-stat-card__icon {
        width: 32px;
        height: 32px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        font-size: 0.9rem;
    }

    .dash-stat-card__value {
        font-size: 1.6rem;
        font-weight: 800;

        margin-bottom: 2px;
    }

    .dash-stat-card__hint {
        font-size: 0.74rem;
        color: var(--simmas-muted);
    }

    /* =========================================================
       GRID BAWAH: GRAFIK + WIDGET SAMPING
    ========================================================= */

    .dash-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 16px;

        margin-bottom: 16px;
    }

    .dash-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 14px;

        padding: 20px;
    }

    .dash-panel__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .dash-panel__title {
        font-size: 0.92rem;
        font-weight: 700;

        margin-bottom: 2px;
    }

    .dash-panel__subtitle {
        font-size: 0.76rem;
        color: var(--simmas-muted);

        margin-bottom: 16px;
    }

    .dash-panel__link {
        flex-shrink: 0;

        font-size: 0.76rem;
        font-weight: 700;
        color: var(--simmas-blue);
        text-decoration: none;

        white-space: nowrap;
    }

    .dash-panel__link:hover {
        text-decoration: underline;
    }

    /* =========================================================
       WIDGET STATUS PENGAJUAN
    ========================================================= */

    .dash-status-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .dash-status-item__top {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 6px;

        font-size: 0.82rem;
    }

    .dash-status-item__label {
        display: flex;
        align-items: center;
        gap: 8px;

        font-weight: 600;
    }

    .dash-status-item__dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dash-status-item__right {
        display: flex;
        align-items: center;
        gap: 8px;

        color: var(--simmas-muted);
        font-weight: 700;
    }

    .dash-status-item__percent {
        min-width: 34px;
        text-align: right;
    }

    .dash-status-item__bar {
        height: 6px;
        border-radius: 999px;
        background: var(--simmas-bg);
        overflow: hidden;
    }

    .dash-status-item__bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.3s ease;
    }

    /* =========================================================
       TABEL DISTRIBUSI DUDI
    ========================================================= */

    .dash-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dash-table th {
        text-align: left;

        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;

        color: var(--simmas-muted);

        padding: 8px 6px;

        border-bottom: 1px solid var(--simmas-border);
    }

    .dash-table td {
        padding: 10px 6px;

        font-size: 0.84rem;

        border-bottom: 1px solid var(--simmas-border);
    }

    .dash-table tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       WIDGET LOG AKTIVITAS
    ========================================================= */

    .dash-log-item {
        display: flex;
        gap: 10px;

        padding: 10px 0;

        border-bottom: 1px solid var(--simmas-border);
    }

    .dash-log-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .dash-log-item__icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: var(--simmas-blue-light);
        color: var(--simmas-blue);

        font-size: 0.8rem;
    }

    .dash-log-item__text {
        font-size: 0.82rem;
        line-height: 1.4;
    }

    .dash-log-item__time {
        font-size: 0.72rem;
        color: var(--simmas-muted);
    }

    .dash-empty {
        text-align: center;
        padding: 24px 0;
        color: var(--simmas-muted);
        font-size: 0.84rem;
    }

    /* =========================================================
       WIDGET KELOLA DATA (SHORTCUT MASTER DATA)
    ========================================================= */

    .dash-quicklinks {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .dash-quicklink {
        display: flex;
        flex-direction: column;
        gap: 10px;

        padding: 14px;

        border: 1px solid var(--simmas-border);
        border-radius: 10px;

        text-decoration: none;
        color: var(--simmas-ink);

        transition: border-color 0.15s ease, background 0.15s ease;
    }

    .dash-quicklink:hover {
        border-color: var(--simmas-blue);
        background: var(--simmas-blue-light);
    }

    .dash-quicklink__icon {
        width: 32px;
        height: 32px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        font-size: 0.9rem;
    }

    .dash-quicklink__label {
        font-size: 0.8rem;
        font-weight: 700;
    }

    .dash-quicklink__hint {
        font-size: 0.7rem;
        color: var(--simmas-muted);
        margin-top: -6px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {
        .dash-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .dash-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .dash-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }

        .dash-stats {
            grid-template-columns: 1fr;
        }

        .dash-quicklinks {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush


@section('content')

{{-- =====================================================
     1. HERO BANNER
     Sapaan admin, tanggal hari ini, dan notifikasi
     pengajuan baru yang perlu ditinjau.
====================================================== --}}

<div class="dash-hero">

    <div>
        <p class="dash-hero__date">
            {{ now()->translatedFormat('l, d F Y') }}
        </p>

        <h1 class="dash-hero__title">
            Selamat datang kembali, {{ auth()->user()->nama ?? 'Admin' }}
        </h1>

        <p class="dash-hero__subtitle">
            @if ($pengajuanMenungguValidasi > 0)
                Ada <strong>{{ $pengajuanMenungguValidasi }}</strong> pengajuan magang yang perlu divalidasi hari ini.
            @else
                Belum ada pengajuan magang yang perlu divalidasi hari ini.
            @endif
        </p>
    </div>

    <a href="{{ route('admin.penempatan.index') }}" class="dash-hero__action">
        <i class="bi bi-clipboard-check"></i>
        Tinjau Pengajuan
    </a>

</div>


{{-- =====================================================
     2. STAT CARD (4 KARTU)
     Total Siswa, Guru Pembimbing Aktif,
     Mitra DUDI Terverifikasi, Pengajuan Menunggu Validasi.
====================================================== --}}

<div class="dash-stats">

    {{-- Kartu 1: Total Siswa --}}
    <div class="dash-stat-card">
        <div class="dash-stat-card__top">
            <span class="dash-stat-card__label">Total Siswa</span>
            <span class="dash-stat-card__icon" style="background: #dbeafe; color: #2563eb;">
                <i class="bi bi-people-fill"></i>
            </span>
        </div>

        <div class="dash-stat-card__value">{{ $totalSiswa }}</div>
        <div class="dash-stat-card__hint">Data real-time</div>
    </div>

    {{-- Kartu 2: Guru Pembimbing Aktif --}}
    <div class="dash-stat-card">
        <div class="dash-stat-card__top">
            <span class="dash-stat-card__label">Guru Pembimbing</span>
            <span class="dash-stat-card__icon" style="background: #ede9fe; color: #7c3aed;">
                <i class="bi bi-person-badge-fill"></i>
            </span>
        </div>

        <div class="dash-stat-card__value">{{ $guruPembimbingAktif }}</div>
        <div class="dash-stat-card__hint">Data real-time</div>
    </div>

    {{-- Kartu 3: Mitra DUDI Terverifikasi --}}
    <div class="dash-stat-card">
        <div class="dash-stat-card__top">
            <span class="dash-stat-card__label">Mitra DUDI</span>
            <span class="dash-stat-card__icon" style="background: #dcfce7; color: #16a34a;">
                <i class="bi bi-building-fill"></i>
            </span>
        </div>

        <div class="dash-stat-card__value">{{ $mitraDudiTerverifikasi }}</div>
        <div class="dash-stat-card__hint">Data real-time</div>
    </div>

    {{-- Kartu 4: Pengajuan Menunggu Validasi --}}
    <div class="dash-stat-card">
        <div class="dash-stat-card__top">
            <span class="dash-stat-card__label">Menunggu Validasi</span>
            <span class="dash-stat-card__icon" style="background: #fef3c7; color: #d97706;">
                <i class="bi bi-hourglass-split"></i>
            </span>
        </div>

        <div class="dash-stat-card__value">{{ $pengajuanMenungguValidasi }}</div>
        <div class="dash-stat-card__hint">Data real-time</div>
    </div>

</div>


{{-- =====================================================
     3. GRID: GRAFIK TREN PENGAJUAN + STATUS PENGAJUAN
====================================================== --}}

<div class="dash-grid">

    {{-- ---------------------------------------------------
         3a. GRAFIK TREN PENGAJUAN (6 BULAN TERAKHIR)
         Data $labelBulan & $dataPengajuan dikirim dari
         DashboardController, dirender pakai Chart.js.
    --------------------------------------------------- --}}

    <div class="dash-panel">
        <p class="dash-panel__title">Tren Pengajuan Magang</p>
        <p class="dash-panel__subtitle">Jumlah pengajuan & persetujuan 6 bulan terakhir</p>

        <canvas id="chartTrenPengajuan" height="110"></canvas>
    </div>


    {{-- ---------------------------------------------------
         3b. WIDGET STATUS PENGAJUAN
         Distribusi status pengajuan magang siswa
         ($statusPengajuan dikirim dari controller, berisi
         disetujui / menunggu / ditolak).
    --------------------------------------------------- --}}

    <div class="dash-panel">
        <div class="dash-panel__header">
            <div>
                <p class="dash-panel__title">Status Pengajuan</p>
                <p class="dash-panel__subtitle">Distribusi status magang siswa</p>
            </div>

            <a href="{{ route('admin.penempatan.index') }}" class="dash-panel__link">
                Detail <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        @php
            $totalStatus = max(
                ($statusPengajuan['disetujui'] ?? 0)
                + ($statusPengajuan['menunggu'] ?? 0)
                + ($statusPengajuan['ditolak'] ?? 0),
                0
            );

            $statusItems = [
                [
                    'label' => 'Disetujui',
                    'value' => $statusPengajuan['disetujui'] ?? 0,
                    'color' => '#16a34a',
                    'icon'  => 'bi-check-circle-fill',
                ],
                [
                    'label' => 'Menunggu',
                    'value' => $statusPengajuan['menunggu'] ?? 0,
                    'color' => '#d97706',
                    'icon'  => 'bi-hourglass-split',
                ],
                [
                    'label' => 'Ditolak',
                    'value' => $statusPengajuan['ditolak'] ?? 0,
                    'color' => '#dc2626',
                    'icon'  => 'bi-x-circle-fill',
                ],
            ];
        @endphp

        <div class="dash-status-list">
            @foreach ($statusItems as $item)
                @php
                    $percent = $totalStatus > 0
                        ? round(($item['value'] / $totalStatus) * 100)
                        : 0;
                @endphp

                <div class="dash-status-item">
                    <div class="dash-status-item__top">
                        <span class="dash-status-item__label">
                            <span class="dash-status-item__dot" style="background: {{ $item['color'] }};"></span>
                            {{ $item['label'] }}
                        </span>

                        <span class="dash-status-item__right">
                            {{ $item['value'] }}
                            <span class="dash-status-item__percent">{{ $percent }}%</span>
                        </span>
                    </div>

                    <div class="dash-status-item__bar">
                        <div
                            class="dash-status-item__bar-fill"
                            style="width: {{ $percent }}%; background: {{ $item['color'] }};"
                        ></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>


{{-- =====================================================
     4. GRID: LOG AKTIVITAS + KELOLA DATA
====================================================== --}}

<div class="dash-grid" style="grid-template-columns: 1fr 1fr;">

    {{-- ---------------------------------------------------
         4a. WIDGET LOG AKTIVITAS TERBARU (5 TERAKHIR)
    --------------------------------------------------- --}}

    <div class="dash-panel">
        <p class="dash-panel__title">Aktivitas Sistem Terakhir</p>
        <p class="dash-panel__subtitle">5 aktivitas terbaru di seluruh sistem</p>

        @forelse ($activityLogs as $log)
            <div class="dash-log-item">
                <span class="dash-log-item__icon">
                    <i class="bi bi-clock-history"></i>
                </span>

                <div>
                    <p class="dash-log-item__text">
                        <strong>{{ $log->actor_email ?? 'Sistem' }}</strong>
                        — {{ str_replace('_', ' ', $log->action_type) }}
                    </p>
                    <span class="dash-log-item__time">
                        {{ $log->created_at?->diffForHumans() }}
                    </span>
                </div>
            </div>
        @empty
            <div class="dash-empty">
                Belum ada aktivitas yang tercatat.
            </div>
        @endforelse
    </div>


    {{-- ---------------------------------------------------
         4b. WIDGET KELOLA DATA
         Shortcut cepat ke halaman master data & manajemen
         yang paling sering diakses admin.
    --------------------------------------------------- --}}

    <div class="dash-panel">
        <p class="dash-panel__title">Kelola Data</p>
        <p class="dash-panel__subtitle">Akses cepat ke halaman master data</p>

        <div class="dash-quicklinks">

            <a href="{{ route('admin.guru.index') }}" class="dash-quicklink">
                <span class="dash-quicklink__icon" style="background: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-person-badge-fill"></i>
                </span>
                <span class="dash-quicklink__label">Data Guru</span>
            </a>

            <a href="{{ route('admin.siswa.index') }}" class="dash-quicklink">
                <span class="dash-quicklink__icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-people-fill"></i>
                </span>
                <span class="dash-quicklink__label">Data Siswa</span>
            </a>

            <a href="{{ route('admin.dudi.index') }}" class="dash-quicklink">
                <span class="dash-quicklink__icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="bi bi-building-fill"></i>
                </span>
                <span class="dash-quicklink__label">Data DUDI</span>
            </a>

            <a href="{{ route('admin.penempatan.index') }}" class="dash-quicklink">
                <span class="dash-quicklink__icon" style="background: #fef3c7; color: #d97706;">
                    <i class="bi bi-diagram-3-fill"></i>
                </span>
                <span class="dash-quicklink__label">Penempatan Magang</span>
            </a>

        </div>
    </div>

</div>


{{-- =====================================================
     5. TABEL DISTRIBUSI DUDI
     Ringkasan nama mitra, kuota, dan jumlah siswa aktif.
====================================================== --}}

<div class="dash-panel">
    <p class="dash-panel__title">Distribusi Mitra DUDI</p>
    <p class="dash-panel__subtitle">5 mitra dengan siswa magang aktif terbanyak</p>

    <table class="dash-table">
        <thead>
            <tr>
                <th>Perusahaan</th>
                <th>Kuota</th>
                <th>Siswa Aktif</th>
                <th>Sisa Kuota</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($distribusiDudi as $dudi)
                <tr>
                    <td>{{ $dudi->nama_perusahaan }}</td>
                    <td>{{ $dudi->kuota }}</td>
                    <td>{{ $dudi->siswa_aktif_count }}</td>
                    {{-- pakai accessor sisa_kuota di Model TempatMagang, biar rumusnya
                        nggak ditulis ulang manual di sini --}}
                    <td>{{ $dudi->sisa_kuota }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="dash-empty">
                        Belum ada data mitra DUDI.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection


@push('scripts')

{{-- Chart.js, dipakai khusus untuk grafik tren pengajuan --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       RENDER GRAFIK TREN PENGAJUAN
       Data label bulan & jumlah pengajuan dikirim dari
       controller lewat Blade, di-encode ke JSON supaya
       aman dibaca JavaScript.
    ====================================================== */

    const labelBulan    = @json($labelBulan);
    const dataPengajuan = @json($dataPengajuan);

    const ctx = document.getElementById('chartTrenPengajuan');

    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelBulan,
                datasets: [
                    {
                        label: 'Jumlah Pengajuan',
                        data: dataPengajuan,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: '#2563eb',
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0, // sumbu Y hanya bilangan bulat
                        },
                    },
                },
            },
        });
    }

});
</script>

@endpush
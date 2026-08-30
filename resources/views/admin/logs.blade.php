{{--
    ==========================================================================
    FILE : resources/views/admin/logs.blade.php
    HALAMAN : Portal Administrator - Sistem Log Aktivitas & Audit (/admin/logs)
    ==========================================================================
    Variabel dari controller:
        $logs, $totalLogs, $actionTypes,
        $search, $levelFilter, $aksiFilter, $emailFilter, $dariTanggal, $sampaiTanggal
--}}
@extends('layouts.admin')

@section('title', 'Log Aktivitas & Audit')
@section('page-title', 'Log Aktivitas & Audit')

@section('content')

    {{-- ============================================================
         1. HEADER HALAMAN
    ============================================================ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="badge log-count-badge">
                <i class="bi bi-journal-text me-1"></i> {{ $totalLogs }} Log Tercatat
            </span>
            <a href="{{ url()->full() }}" class="btn btn-sm log-btn-refresh">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </a>
        </div>
    </div>

    {{-- ============================================================
         2. CARD FILTER
    ============================================================ --}}
    <div class="card border log-filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.logs') }}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="log-filter-title">
                        <i class="bi bi-funnel me-1"></i> Filter Log
                    </span>
                    <a href="{{ route('admin.logs') }}" class="btn btn-sm log-btn-reset">
                        <i class="bi bi-x-lg me-1"></i> Reset Filter
                    </a>
                </div>

                <div class="row g-3">
                    <div class="col-md-4 col-lg-2">
                        <label class="log-filter-label">Kata Kunci</label>
                        <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm"
                               placeholder="Email, IP, nama...">
                    </div>

                    <div class="col-md-4 col-lg-2">
                        <label class="log-filter-label">Level</label>
                        <select name="level" class="form-select form-select-sm">
                            <option value="">Semua Level</option>
                            <option value="INFO" {{ $levelFilter === 'INFO' ? 'selected' : '' }}>INFO</option>
                            <option value="WARN" {{ $levelFilter === 'WARN' ? 'selected' : '' }}>WARN</option>
                            <option value="ERROR" {{ $levelFilter === 'ERROR' ? 'selected' : '' }}>ERROR</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-lg-2">
                        <label class="log-filter-label">Tipe Aksi</label>
                        <select name="aksi" class="form-select form-select-sm">
                            <option value="">Semua Aksi</option>
                            @foreach ($actionTypes as $tipe)
                                <option value="{{ $tipe }}" {{ $aksiFilter === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-lg-2">
                        <label class="log-filter-label">Email Pengguna</label>
                        <input type="text" name="email" value="{{ $emailFilter }}" class="form-control form-control-sm"
                               placeholder="@simmas.sch.id">
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <label class="log-filter-label">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ $dariTanggal }}" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <label class="log-filter-label">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ $sampaiTanggal }}" class="form-control form-control-sm">
                    </div>
                </div>

                {{-- Submit otomatis lewat tombol di bawah, cukup 1 tombol Terapkan --}}
                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-sm log-btn-terapkan">
                        <i class="bi bi-search me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         3. CARD TABEL AUDIT LOG
    ============================================================ --}}
    <div class="card border log-card">

        <div class="log-table-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <div class="log-table-title">Audit Trail Log ({{ $logs->total() }})</div>
                <div class="log-table-sub">Data log bersifat append-only dan tidak dapat dimodifikasi</div>
            </div>
            <button type="button" class="btn btn-sm log-btn-danger d-flex align-items-center gap-1"
                    data-bs-toggle="modal" data-bs-target="#modalBersihkanLog">
                <i class="bi bi-trash3"></i> Bersihkan Log Lama
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle log-table mb-0">
                <thead>
                    <tr class="text-uppercase log-table-head">
                        <th style="width: 14%">Waktu</th>
                        <th style="width: 9%">Level</th>
                        <th style="width: 24%">Aktivitas</th>
                        <th style="width: 24%">Pengguna</th>
                        <th style="width: 14%">Alamat IP</th>
                        <th style="width: 15%">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php
                            $levelClass = match ($log->level) {
                                'INFO'  => 'log-badge-info',
                                'WARN'  => 'log-badge-warn',
                                'ERROR' => 'log-badge-error',
                                default => 'log-badge-default',
                            };

                            // Label & ikon aktivitas — dipetakan dari action_type
                            $aksi = $log->action_type;
                            $aksiLabel = match (true) {
                                str_contains($aksi, 'LOGIN')   => 'Login Berhasil',
                                str_contains($aksi, 'LOGOUT')  => 'Logout',
                                str_contains($aksi, 'ABSENSI') => 'Aktivitas Absensi',
                                str_contains($aksi, 'JURNAL')  => 'Aktivitas Jurnal',
                                default => ucwords(strtolower(str_replace('_', ' ', $aksi))),
                            };
                            $aksiIcon = match (true) {
                                str_contains($aksi, 'LOGIN'), str_contains($aksi, 'LOGOUT') => 'bi-lock-fill',
                                str_contains($aksi, 'ABSENSI') => 'bi-calendar-check-fill',
                                str_contains($aksi, 'JURNAL')  => 'bi-journal-text',
                                default => 'bi-activity',
                            };

                            $namaPengguna = $log->metadata['full_name'] ?? $log->actor_email;

                            $roleClass = match ($log->actor_role) {
                                'admin' => 'log-role-admin',
                                'guru'  => 'log-role-guru',
                                'siswa' => 'log-role-siswa',
                                default => 'log-role-default',
                            };
                        @endphp
                        <tr>
                            {{-- WAKTU --}}
                            <td>
                                <div class="log-cell-main">{{ $log->created_at->format('d/m/y') }}</div>
                                <div class="log-cell-sub">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>

                            {{-- LEVEL --}}
                            <td>
                                <span class="badge log-level-badge {{ $levelClass }}">
                                    <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>{{ $log->level }}
                                </span>
                            </td>

                            {{-- AKTIVITAS --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="log-aksi-icon"><i class="bi {{ $aksiIcon }}"></i></span>
                                    <div>
                                        <div class="log-cell-main">{{ $aksiLabel }}</div>
                                        <div class="log-cell-sub">{{ $aksi }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- PENGGUNA --}}
                            <td>
                                <div class="log-cell-main">{{ $namaPengguna }}</div>
                                <div class="log-cell-sub">
                                    {{ $log->actor_email }}
                                    <span class="log-role-badge {{ $roleClass }}">{{ $log->actor_role }}</span>
                                </div>
                            </td>

                            {{-- ALAMAT IP --}}
                            <td>
                                <span class="log-ip-text">{{ $log->ip_address }}</span>
                            </td>

                            {{-- DETAIL --}}
                            <td>
                                <button type="button" class="btn btn-link btn-sm log-link-metadata d-inline-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#modalDetail{{ $log->id }}">
                                    <i class="bi bi-file-earmark-text"></i> Detail
                                </button>
                            </td>
                        </tr>

                        {{-- MODAL DETAIL LOG --}}
                        <div class="modal fade" id="modalDetail{{ $log->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content log-modal">
                                    <div class="modal-header border-0 pb-0">
                                        <div>
                                            <div class="log-modal-eyebrow">Audit Trail</div>
                                            <h5 class="fw-bold mb-0">Detail Aktivitas Log</h5>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="log-detail-row">
                                            <div class="log-detail-label">Waktu</div>
                                            <div class="log-detail-value">{{ $log->created_at->format('d/m/y, H.i.s') }}</div>
                                        </div>
                                        <div class="log-detail-row">
                                            <div class="log-detail-label">Aktivitas</div>
                                            <div class="log-detail-value">{{ $aksiLabel }} ({{ $aksi }})</div>
                                        </div>
                                        <div class="log-detail-row">
                                            <div class="log-detail-label">Pengguna</div>
                                            <div class="log-detail-value">{{ $namaPengguna }} — {{ $log->actor_email }}</div>
                                        </div>
                                        <div class="log-detail-row">
                                            <div class="log-detail-label">Level</div>
                                            <div class="log-detail-value">{{ $log->level }}</div>
                                        </div>
                                        <div class="log-detail-row">
                                            <div class="log-detail-label">Alamat IP</div>
                                            <div class="log-detail-value">{{ $log->ip_address }}</div>
                                        </div>
                                        <div class="log-detail-row mb-0">
                                            <div class="log-detail-label">Metadata (JSON)</div>
                                            <pre class="log-metadata-box mb-0">{{ json_encode($log->metadata ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-sm log-btn-tutup px-3" data-bs-dismiss="modal">
                                            Tutup <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="6" class="border-0">
                                <div class="log-empty-state text-center py-5">
                                    <i class="bi bi-inbox log-empty-icon"></i>
                                    <p class="fw-semibold mb-1 mt-3">Belum ada data log aktivitas</p>
                                    <p class="text-muted small mb-0">
                                        Aktivitas pengguna akan tercatat otomatis di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ============================================================
             4. FOOTER: INFO DATA + PAGINATION
        ============================================================ --}}
        <div class="log-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="log-footer-text">
                Menampilkan {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }}
                dari {{ $logs->total() }} data
            </div>

            <nav aria-label="Navigasi halaman log">
                <ul class="log-pagination d-flex align-items-center gap-1 mb-0">
                    <li>
                        <a class="log-page-btn {{ $logs->onFirstPage() ? 'disabled' : '' }}"
                           href="{{ $logs->onFirstPage() ? '#' : $logs->url(1) }}">
                            <i class="bi bi-chevron-bar-left"></i>
                        </a>
                    </li>
                    <li>
                        <a class="log-page-btn {{ $logs->onFirstPage() ? 'disabled' : '' }}"
                           href="{{ $logs->onFirstPage() ? '#' : $logs->previousPageUrl() }}">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <li class="log-page-indicator">
                        {{ $logs->currentPage() }} / {{ $logs->lastPage() }}
                    </li>
                    <li>
                        <a class="log-page-btn {{ $logs->hasMorePages() ? '' : 'disabled' }}"
                           href="{{ $logs->hasMorePages() ? $logs->nextPageUrl() : '#' }}">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                    <li>
                        <a class="log-page-btn {{ $logs->hasMorePages() ? '' : 'disabled' }}"
                           href="{{ $logs->hasMorePages() ? $logs->url($logs->lastPage()) : '#' }}">
                            <i class="bi bi-chevron-bar-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    {{-- ============================================================
         5. MODAL KONFIRMASI "BERSIHKAN LOG"
    ============================================================ --}}
    <div class="modal fade" id="modalBersihkanLog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content log-modal">
                <div class="modal-body text-center pt-4 pb-3">
                    <div class="log-warning-icon mx-auto mb-3">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Konfirmasi Bersihkan Log</h6>
                    <p class="text-muted mb-0 px-2">
                        Tindakan ini akan menghapus log yang lebih lama dari 90 hari secara permanen
                        dan tidak dapat dibatalkan. Apakah kamu yakin ingin melanjutkan?
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('admin.logs.clear') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn log-btn-danger btn-sm px-3">
                            <i class="bi bi-trash3 me-1"></i> Ya, Bersihkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         6. CSS KHUSUS HALAMAN INI
    ============================================================ --}}
    <style>
        :root {
            --log-primary: #2f5cf0;
            --log-primary-soft: #eef2ff;
            --log-danger: #ee4148;
            --log-ink: #1f2430;
            --log-muted: #8a92a3;
            --log-border: #e9ebf0;
            --log-head-bg: #f8f9fb;
        }

        .log-eyebrow {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--log-muted);
        }

        .log-page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--log-ink);
        }

        .log-count-badge {
            background: var(--log-primary-soft);
            color: var(--log-primary);
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.5rem 0.85rem;
            border-radius: 999px;
        }

        .log-btn-refresh {
            background: #fff;
            border: 1px solid var(--log-border);
            color: var(--log-ink);
            font-weight: 600;
            font-size: 0.82rem;
            border-radius: 8px;
        }
        .log-btn-refresh:hover { background: var(--log-primary-soft); color: var(--log-primary); }

        .log-filter-card { border-color: var(--log-border) !important; border-radius: 0.9rem; }

        .log-filter-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--log-ink);
        }

        .log-filter-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--log-muted);
            margin-bottom: 0.3rem;
            display: block;
        }

        .log-btn-reset {
            background: #fff;
            border: 1px solid var(--log-border);
            color: var(--log-muted);
            font-size: 0.78rem;
            font-weight: 600;
            border-radius: 8px;
        }
        .log-btn-reset:hover { color: var(--log-danger); border-color: var(--log-danger); }

        .log-btn-terapkan {
            background: var(--log-primary);
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.82rem;
            border-radius: 8px;
            padding: 0.45rem 1.1rem;
        }
        .log-btn-terapkan:hover { background: #2447c4; color: #fff; }

        .log-card { border-color: var(--log-border) !important; border-radius: 0.9rem; overflow: hidden; background: #fff; }

        .log-table-header {
            padding: 1rem 1.15rem;
            border-bottom: 1px solid var(--log-border);
        }

        .log-table-title { font-size: 0.95rem; font-weight: 700; color: var(--log-ink); }
        .log-table-sub { font-size: 0.76rem; color: var(--log-muted); }

        .log-btn-danger {
            background: var(--log-danger);
            border: none;
            color: #fff;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.45rem 0.9rem;
            font-size: 0.8rem;
        }
        .log-btn-danger:hover { background: #d93036; color: #fff; }

        .log-table-head th {
            background: var(--log-head-bg);
            color: var(--log-muted);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 0.85rem 1.15rem;
            border-bottom: 1px solid var(--log-border);
            border-top: none;
        }

        .log-table td { padding: 0.9rem 1.15rem; border-bottom: 1px solid var(--log-border); vertical-align: middle; }
        .log-table tbody tr:last-child td { border-bottom: none; }
        .log-table tbody tr:hover { background: #fbfbfd; }

        .log-cell-main { font-size: 0.86rem; font-weight: 600; color: var(--log-ink); }
        .log-cell-sub { font-size: 0.74rem; color: var(--log-muted); margin-top: 1px; }

        .log-aksi-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #fff4e5;
            color: #d98324;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .log-role-badge {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 0.3rem;
        }
        .log-role-admin   { color: var(--log-primary); }
        .log-role-guru    { color: #d9534f; }
        .log-role-siswa   { color: #1c9c5b; }
        .log-role-default { color: var(--log-muted); }

        .log-ip-text { font-size: 0.82rem; color: var(--log-muted); }

        .log-level-badge { font-weight: 600; font-size: 0.72rem; padding: 0.35rem 0.6rem; border-radius: 6px; border: 1px solid transparent; }
        .log-badge-info    { background: #eef4ff; color: #3b6fe0; border-color: #dbe6fd; }
        .log-badge-warn    { background: #fff8ec; color: #b3720a; border-color: #fbe8c6; }
        .log-badge-error   { background: #fdecec; color: var(--log-danger); border-color: #f9d3d4; }
        .log-badge-default { background: #f1f2f4; color: var(--log-muted); border-color: var(--log-border); }

        .log-link-metadata { color: var(--log-primary); font-size: 0.83rem; font-weight: 500; text-decoration: none; padding: 0; }
        .log-link-metadata:hover { color: #2447c4; text-decoration: underline; }

        .log-empty-icon { font-size: 2.4rem; color: #d7dbe3; }

        .log-modal { border-radius: 1rem; border: none; }
        .log-modal-eyebrow {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--log-muted);
            margin-bottom: 0.2rem;
        }

        .log-detail-row {
            padding: 0.65rem 0;
            border-bottom: 1px solid var(--log-border);
        }
        .log-detail-row:first-child { padding-top: 0; }
        .log-detail-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--log-muted);
            margin-bottom: 0.2rem;
        }
        .log-detail-value { font-size: 0.92rem; font-weight: 600; color: var(--log-ink); }

        .log-metadata-box {
            background: #0f1729;
            color: #e5e9f0;
            border-radius: 8px;
            padding: 0.9rem;
            font-size: 0.78rem;
            max-height: 220px;
            overflow: auto;
            margin-top: 0.3rem;
        }

        .log-warning-icon {
            width: 56px; height: 56px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: #fdecec; color: var(--log-danger); font-size: 1.5rem;
        }

        .log-btn-tutup { background: var(--log-primary); border: none; color: #fff; font-weight: 600; }
        .log-btn-tutup:hover { background: #2447c4; color: #fff; }

        .log-footer { padding: 0.9rem 1.15rem; }
        .log-footer-text { font-size: 0.8rem; color: var(--log-muted); }

        .log-page-btn {
            width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--log-border); border-radius: 7px; color: var(--log-ink); background: #fff; font-size: 0.8rem;
        }
        .log-page-btn:hover:not(.disabled) { background: var(--log-primary-soft); border-color: var(--log-primary); color: var(--log-primary); }
        .log-page-btn.disabled { color: #c7cbd4; pointer-events: none; }

        .log-page-indicator { font-size: 0.82rem; font-weight: 600; color: var(--log-ink); padding: 0 0.4rem; list-style: none; }
        .log-pagination { list-style: none; padding-left: 0; }

        @media (max-width: 767.98px) {
            .log-table-header { flex-direction: column; align-items: flex-start !important; }
        }
    </style>

@endsection
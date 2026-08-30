@extends('layouts.siswa')

@section('title', 'Jurnal Kegiatan')
@section('page-title', 'Jurnal Kegiatan')

@php
    $statusBadgeMap = [
        'menunggu'  => ['label' => 'Menunggu',    'class' => 'sw-badge-orange'],
        'disetujui' => ['label' => 'Disetujui',   'class' => 'sw-badge-green'],
        'revisi'    => ['label' => 'Perlu Revisi', 'class' => 'sw-badge-red'],
    ];
@endphp

@section('styles')
<style>
    :root {
        --sw-primary:      var(--guru-primary, #3B5BFB);
        --sw-primary-dark: var(--guru-primary-dark, #2540D6);
        --sw-primary-soft: var(--guru-primary-soft, #EAEEFF);
        --sw-ink:          var(--guru-ink, #111827);
        --sw-muted:        var(--guru-muted, #6B7280);
        --sw-border:       var(--guru-border, #E5E7EB);
        --sw-radius-sm:    10px;
        --sw-radius-md:    14px;
        --sw-radius-lg:    18px;
        --sw-shadow:       0 1px 3px rgba(16, 24, 40, 0.05);
    }

    /* ================= MAIN PANEL & TOOLBAR ================= */
    .sw-panel {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-lg);
        padding: 1.5rem 1.75rem;
        box-shadow: var(--sw-shadow);
    }

    .sw-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .sw-search-box {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        background: #F8FAFC;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-sm);
        padding: 0.55rem 0.95rem;
        width: 100%;
        max-width: 380px;
        transition: all 0.18s ease;
    }

    .sw-search-box:focus-within {
        border-color: var(--sw-primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59, 91, 251, 0.12);
    }

    .sw-search-box i {
        color: var(--sw-muted);
        font-size: 0.9rem;
    }

    .sw-search-box input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 0.86rem;
        width: 100%;
        color: var(--sw-ink);
    }

    .sw-btn-tulis {
        background: var(--sw-primary);
        color: #fff;
        border: none;
        border-radius: var(--sw-radius-sm);
        padding: 0.62rem 1.25rem;
        font-size: 0.86rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 4px 14px -3px rgba(59, 91, 251, 0.4);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .sw-btn-tulis:hover {
        background: var(--sw-primary-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    /* ================= TABEL JURNAL ================= */
    .sw-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sw-table th {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--sw-muted);
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--sw-border);
        text-align: left;
        white-space: nowrap;
        background: #FAFBFD;
    }

    .sw-table th:first-child { border-top-left-radius: 10px; }
    .sw-table th:last-child { border-top-right-radius: 10px; }

    .sw-table td {
        padding: 1.1rem 1rem;
        font-size: 0.86rem;
        color: var(--sw-ink);
        vertical-align: top;
        border-bottom: 1px solid #F1F3F5;
    }

    .sw-table tr:last-child td {
        border-bottom: none;
    }

    .sw-table tr:hover td {
        background: #FAFBFD;
    }

    .sw-kegiatan-text {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--sw-ink);
        line-height: 1.5;
        margin-bottom: 0.35rem;
    }

    .sw-sub-info {
        font-size: 0.78rem;
        line-height: 1.45;
        margin-top: 0.3rem;
        padding: 0.35rem 0.6rem;
        border-radius: 6px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        display: inline-block;
        max-width: 100%;
    }

    .sw-sub-info.kendala {
        background: #FFFDF8;
        border-color: #FEF3C7;
        color: #92400E;
    }

    .sw-sub-info.solusi {
        background: #F8FDF9;
        border-color: #D1FAE5;
        color: #065F46;
    }

    /* ================= BADGES ================= */
    .sw-badge {
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.28rem 0.75rem;
        border-radius: 999px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .sw-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .sw-badge-green  { background: #E7F8EF; color: #1C9C5B; }
    .sw-badge-orange { background: #FFF4E5; color: #D98324; }
    .sw-badge-red    { background: #FDEAEA; color: #DC3545; }
    .sw-badge-gray   { background: #F3F4F6; color: #6B7280; }

    /* ================= TOMBOL FOTO PILL ================= */
    .sw-photo-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.32rem 0.75rem;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 600;
        border: 1px solid #D0DBFF;
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .sw-photo-pill:hover {
        background: var(--sw-primary);
        color: #fff;
        border-color: var(--sw-primary);
    }

    /* ================= AKSI BUTTONS ================= */
    .sw-action-btn-group {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .sw-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--sw-border);
        background: #fff;
        color: var(--sw-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .sw-action-btn:hover {
        background: #F8FAFC;
        color: var(--sw-ink);
        border-color: #CBD5E1;
    }

    .sw-action-btn.edit:hover {
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        border-color: var(--sw-primary);
    }

    .sw-action-btn.delete:hover {
        background: #FDEAEA;
        color: #DC3545;
        border-color: #DC3545;
    }

    .sw-action-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* ================= EMPTY STATE ================= */
    .sw-empty-box {
        text-align: center;
        padding: 4rem 1.5rem;
        color: var(--sw-muted);
    }

    .sw-empty-icon {
        font-size: 2.8rem;
        color: #CBD5E1;
        margin-bottom: 0.85rem;
        display: inline-block;
    }

    .sw-empty-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--sw-ink);
        margin-bottom: 0.3rem;
    }

    .sw-empty-sub {
        font-size: 0.84rem;
        color: var(--sw-muted);
        max-width: 360px;
        margin: 0 auto;
    }

    /* ================= MODAL STYLES ================= */
    .simmas-modal .modal-content {
        border-radius: 18px;
        border: none;
        box-shadow: 0 16px 40px -10px rgba(17, 24, 39, 0.25);
    }

    .simmas-modal .modal-header {
        border-bottom: 1px solid var(--sw-border);
        padding: 1.25rem 1.5rem;
    }

    .simmas-modal .modal-body {
        padding: 1.5rem;
    }

    .simmas-modal .modal-footer {
        border-top: 1px solid var(--sw-border);
        padding: 1rem 1.5rem;
        background: #FAFBFD;
        border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px;
    }

    .simmas-modal-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #F3E8FF;
        color: #7C3AED;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        margin-right: 0.85rem;
    }

    .simmas-modal-icon.blue {
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
    }

    .simmas-modal-icon.red {
        background: #FDEAEA;
        color: #DC3545;
    }

    .simmas-form-label {
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #374151;
        margin-bottom: 0.45rem;
        display: block;
    }

    .simmas-form-control {
        border: 1px solid var(--sw-border);
        border-radius: 10px;
        padding: 0.65rem 0.85rem;
        font-size: 0.88rem;
        color: var(--sw-ink);
        background: #fff;
        width: 100%;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .simmas-form-control:focus {
        border-color: var(--sw-primary);
        box-shadow: 0 0 0 3px rgba(59, 91, 251, 0.15);
        outline: none;
    }

    /* Foto Upload Dropzone */
    .sw-upload-zone {
        border: 2px dashed #CBD5E1;
        border-radius: 12px;
        padding: 1.25rem 1rem;
        text-align: center;
        background: #F8FAFC;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .sw-upload-zone:hover {
        border-color: var(--sw-primary);
        background: #F0F4FF;
    }

    .sw-upload-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #fff;
        color: var(--sw-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 0.4rem;
    }

    .sw-upload-text {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--sw-ink);
        margin-bottom: 0.15rem;
    }

    .sw-upload-hint {
        font-size: 0.72rem;
        color: var(--sw-muted);
    }

    .sw-preview-wrap {
        display: none;
        position: relative;
        text-align: center;
        margin-top: 0.5rem;
    }

    .sw-preview-img {
        max-width: 100%;
        max-height: 160px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--sw-border);
    }

    .sw-remove-preview {
        position: absolute;
        top: 6px;
        right: calc(50% - 75px);
        background: #DC3545;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .simmas-form-error {
        font-size: 0.74rem;
        color: #DC2626;
        margin-top: 0.3rem;
        display: none;
    }
</style>
@endsection

@section('content')

<div class="sw-panel">

    {{-- ============================================================ --}}
    {{-- 1. TOOLBAR: SEARCH & TOMBOL + TULIS JURNAL --}}
    {{-- ============================================================ --}}
    <div class="sw-toolbar">

        {{-- Form Search --}}
        <form method="GET" action="{{ route('siswa.jurnal.index') }}" class="sw-search-box">
            <i class="bi bi-search"></i>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari kegiatan atau kendala..."
                   onchange="this.form.submit()">
        </form>

        {{-- Tombol Tulis Jurnal --}}
        <button type="button" class="sw-btn-tulis" onclick="bukaModalTulisJurnal()">
            <i class="bi bi-plus-lg"></i> Tulis Jurnal
        </button>

    </div>

    {{-- ============================================================ --}}
    {{-- 2. TABEL RIWAYAT JURNAL KEGIATAN --}}
    {{-- ============================================================ --}}
    <div class="table-responsive">
        <table class="sw-table">
            <thead>
                <tr>
                    <th style="width: 15%;">TANGGAL</th>
                    <th style="width: 45%;">KEGIATAN</th>
                    <th style="width: 15%;">FOTO</th>
                    <th style="width: 15%;">STATUS</th>
                    <th style="width: 10%; text-align: right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jurnalList as $item)
                    @php
                        $badge = $statusBadgeMap[$item->status_verifikasi] ?? ['label' => ucfirst($item->status_verifikasi), 'class' => 'sw-badge-gray'];
                        $tglFormat = \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y');
                        $isDisetujui = ($item->status_verifikasi === 'disetujui');
                    @endphp
                    <tr>
                        {{-- 1. TANGGAL --}}
                        <td class="fw-semibold text-nowrap">
                            {{ $tglFormat }}
                        </td>

                        {{-- 2. RINCIAN KEGIATAN & KENDALA/SOLUSI --}}
                        <td>
                            <div class="sw-kegiatan-text">{{ $item->kegiatan }}</div>

                            <div class="d-flex flex-column gap-1">
                                @if($item->kendala)
                                    <div>
                                        <span class="sw-sub-info kendala">
                                            <i class="bi bi-exclamation-circle me-1"></i><strong>Kendala:</strong> {{ $item->kendala }}
                                        </span>
                                    </div>
                                @endif

                                @if($item->solusi)
                                    <div>
                                        <span class="sw-sub-info solusi">
                                            <i class="bi bi-check2-circle me-1"></i><strong>Solusi:</strong> {{ $item->solusi }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{-- 3. FOTO BUKTI --}}
                        <td>
                            @if($item->photo_url)
                                <button type="button"
                                        class="sw-photo-pill"
                                        onclick="lihatFotoJurnal('{{ asset('storage/' . $item->photo_url) }}', 'Foto Bukti - {{ $tglFormat }}')">
                                    <i class="bi bi-image"></i> Foto Bukti
                                </button>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        {{-- 4. STATUS & CATATAN GURU --}}
                        <td>
                            <span class="sw-badge {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                            @if($item->catatan_guru)
                                <div class="text-danger small mt-1" style="font-size: 0.74rem;">
                                    <i class="bi bi-chat-quote me-1"></i>{{ $item->catatan_guru }}
                                </div>
                            @endif
                        </td>

                        {{-- 5. AKSI (EDIT / HAPUS) --}}
                        <td style="text-align: right;">
                            <div class="sw-action-btn-group justify-content-end">
                                {{-- Tombol Edit --}}
                                <button type="button"
                                        class="sw-action-btn edit"
                                        title="{{ $isDisetujui ? 'Jurnal yang sudah disetujui tidak dapat diedit' : 'Edit Jurnal' }}"
                                        onclick="bukaModalEditJurnal({{ json_encode($item) }})"
                                        {{ $isDisetujui ? 'disabled' : '' }}>
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <button type="button"
                                        class="sw-action-btn delete"
                                        title="{{ $isDisetujui ? 'Jurnal yang sudah disetujui tidak dapat dihapus' : 'Hapus Jurnal' }}"
                                        onclick="bukaModalHapusJurnal('{{ $item->id }}', '{{ $tglFormat }}')"
                                        {{ $isDisetujui ? 'disabled' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="sw-empty-box">
                                <div class="sw-empty-icon"><i class="bi bi-journal-richtext"></i></div>
                                <div class="sw-empty-title">Belum ada jurnal kegiatan.</div>
                                <p class="sw-empty-sub">Tekan tombol "Tulis Jurnal" untuk mulai melaporkan aktivitas magang Anda.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- ============================================================ --}}
{{-- 3. MODAL DIALOG: FORM TULIS JURNAL KEGIATAN HARIAN (IMAGE 2) --}}
{{-- ============================================================ --}}
<div class="modal fade simmas-modal" id="modalTulisJurnal" tabindex="-1" aria-labelledby="modalTulisJurnalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="formTulisJurnal" method="POST" action="{{ route('siswa.jurnal.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Modal Header --}}
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="simmas-modal-icon">
                            <i class="bi bi-journal-plus"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0" id="modalTulisJurnalLabel">Tulis Jurnal Kegiatan</h6>
                            <small class="text-muted">Catat aktivitas yang Anda kerjakan di tempat magang hari ini.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body">

                    {{-- General Alert Error --}}
                    <div class="alert alert-danger py-2 small mb-3 simmas-form-error" id="formTulisGeneralError"></div>

                    {{-- 1. TANGGAL PELAKSANAAN --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="tulis_tanggal">TANGGAL PELAKSANAAN</label>
                        <input type="date"
                               name="tanggal"
                               id="tulis_tanggal"
                               class="simmas-form-control"
                               value="{{ date('Y-m-d') }}"
                               required>
                        <div class="simmas-form-error" id="error_tulis_tanggal"></div>
                    </div>

                    {{-- 2. RINCIAN KEGIATAN --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="tulis_kegiatan">RINCIAN KEGIATAN</label>
                        <textarea name="kegiatan"
                                  id="tulis_kegiatan"
                                  rows="3"
                                  class="simmas-form-control"
                                  placeholder="Contoh: Konfigurasi database PostgreSQL dan integrasi REST API untuk manajemen penempatan siswa."
                                  required></textarea>
                        <div class="simmas-form-error" id="error_tulis_kegiatan"></div>
                    </div>

                    {{-- 3. KENDALA / MASALAH (OPSIONAL) --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="tulis_kendala">KENDALA / MASALAH (OPSIONAL)</label>
                        <input type="text"
                               name="kendala"
                               id="tulis_kendala"
                               class="simmas-form-control"
                               placeholder="Contoh: Tidak ada kendala, tugas berjalan lancar.">
                        <div class="simmas-form-error" id="error_tulis_kendala"></div>
                    </div>

                    {{-- 4. TINDAK LANJUT / SOLUSI (OPSIONAL) --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="tulis_solusi">TINDAK LANJUT / SOLUSI (OPSIONAL)</label>
                        <input type="text"
                               name="solusi"
                               id="tulis_solusi"
                               class="simmas-form-control"
                               placeholder="Contoh: Mengonsultasikan dengan pembimbing lapangan.">
                        <div class="simmas-form-error" id="error_tulis_solusi"></div>
                    </div>

                    {{-- 5. FOTO BUKTI PEKERJAAN (OPSIONAL) --}}
                    <div class="mb-2">
                        <label class="simmas-form-label">FOTO BUKTI PEKERJAAN (OPSIONAL)</label>
                        <div class="sw-upload-zone" id="tulisDropzone" onclick="document.getElementById('tulisPhotoInput').click()">
                            <div class="sw-upload-icon">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                            <div class="sw-upload-text">Klik untuk lampirkan screenshot atau foto dokumentasi</div>
                            <div class="sw-upload-hint">Format JPG, PNG atau WebP (Maks. 4MB)</div>
                            <input type="file"
                                   name="photo"
                                   id="tulisPhotoInput"
                                   accept="image/*"
                                   style="display: none;"
                                   onchange="previewImageTulis(this)">
                        </div>

                        {{-- Preview Box --}}
                        <div class="sw-preview-wrap" id="tulisPreviewWrap">
                            <img src="" alt="Preview Foto" class="sw-preview-img" id="tulisPreviewImg">
                            <button type="button" class="sw-remove-preview" onclick="hapusPreviewTulis()" title="Hapus Foto">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>

                        <div class="simmas-form-error" id="error_tulis_photo"></div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="border: 1px solid var(--sw-border); font-size: 0.84rem;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" id="btnSubmitTulis" style="font-size: 0.84rem; background: var(--sw-primary); border-color: var(--sw-primary);">
                        <i class="bi bi-send me-1"></i> Kirim Jurnal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- 4. MODAL DIALOG: FORM EDIT JURNAL KEGIATAN (IMAGE 2 BOTTOM) --}}
{{-- ============================================================ --}}
<div class="modal fade simmas-modal" id="modalEditJurnal" tabindex="-1" aria-labelledby="modalEditJurnalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form id="formEditJurnal" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Modal Header --}}
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="simmas-modal-icon blue">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0" id="modalEditJurnalLabel">Edit Jurnal Kegiatan Harian</h6>
                            <small class="text-muted">Perbarui laporan aktivitas magang Anda.</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body">

                    {{-- General Alert Error --}}
                    <div class="alert alert-danger py-2 small mb-3 simmas-form-error" id="formEditGeneralError"></div>

                    {{-- 1. TANGGAL PELAKSANAAN --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="edit_tanggal">TANGGAL PELAKSANAAN</label>
                        <input type="date"
                               name="tanggal"
                               id="edit_tanggal"
                               class="simmas-form-control"
                               required>
                        <div class="simmas-form-error" id="error_edit_tanggal"></div>
                    </div>

                    {{-- 2. RINCIAN KEGIATAN --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="edit_kegiatan">RINCIAN KEGIATAN</label>
                        <textarea name="kegiatan"
                                  id="edit_kegiatan"
                                  rows="3"
                                  class="simmas-form-control"
                                  required></textarea>
                        <div class="simmas-form-error" id="error_edit_kegiatan"></div>
                    </div>

                    {{-- 3. KENDALA / MASALAH (OPSIONAL) --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="edit_kendala">KENDALA / MASALAH (OPSIONAL)</label>
                        <input type="text"
                               name="kendala"
                               id="edit_kendala"
                               class="simmas-form-control">
                        <div class="simmas-form-error" id="error_edit_kendala"></div>
                    </div>

                    {{-- 4. TINDAK LANJUT / SOLUSI (OPSIONAL) --}}
                    <div class="mb-3">
                        <label class="simmas-form-label" for="edit_solusi">TINDAK LANJUT / SOLUSI (OPSIONAL)</label>
                        <input type="text"
                               name="solusi"
                               id="edit_solusi"
                               class="simmas-form-control">
                        <div class="simmas-form-error" id="error_edit_solusi"></div>
                    </div>

                    {{-- 5. FOTO BUKTI PEKERJAAN (OPSIONAL) --}}
                    <div class="mb-2">
                        <label class="simmas-form-label">FOTO BUKTI PEKERJAAN (OPSIONAL)</label>
                        <div class="sw-upload-zone" id="editDropzone" onclick="document.getElementById('editPhotoInput').click()">
                            <div class="sw-upload-icon">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                            <div class="sw-upload-text">Klik untuk ubah foto dokumentasi</div>
                            <div class="sw-upload-hint">Format JPG, PNG atau WebP (Maks. 4MB)</div>
                            <input type="file"
                                   name="photo"
                                   id="editPhotoInput"
                                   accept="image/*"
                                   style="display: none;"
                                   onchange="previewImageEdit(this)">
                        </div>

                        {{-- Preview Box --}}
                        <div class="sw-preview-wrap" id="editPreviewWrap">
                            <img src="" alt="Preview Foto" class="sw-preview-img" id="editPreviewImg">
                            <button type="button" class="sw-remove-preview" onclick="hapusPreviewEdit()" title="Hapus Foto">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>

                        <div class="simmas-form-error" id="error_edit_photo"></div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="border: 1px solid var(--sw-border); font-size: 0.84rem;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold" id="btnSubmitEdit" style="font-size: 0.84rem; background: var(--sw-primary); border-color: var(--sw-primary);">
                        <i class="bi bi-check2 me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- 5. ALERT DIALOG: KONFIRMASI HAPUS LAPORAN JURNAL (IMAGE 3) --}}
{{-- ============================================================ --}}
<div class="modal fade simmas-modal" id="modalHapusJurnal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content">

            <div class="modal-body text-center pt-4 pb-2 px-4">
                <div class="simmas-modal-icon red mx-auto mb-3" style="width: 48px; height: 48px; font-size: 1.4rem; border-radius: 50%;">
                    <i class="bi bi-trash-fill"></i>
                </div>
                <h6 class="fw-bold text-dark mb-2" style="font-size: 1.05rem;">Hapus Laporan Jurnal?</h6>
                <p class="text-muted small mb-0" style="line-height: 1.55;">
                    Apakah Anda yakin ingin menghapus draft jurnal kegiatan tanggal <strong id="hapusJurnalTanggal" class="text-dark">-</strong>? Laporan yang sudah disetujui guru tidak dapat dihapus.
                </p>
            </div>

            <div class="modal-footer justify-content-center border-top-0 pt-2 pb-4 gap-2">
                <button type="button" class="btn btn-light px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="border: 1px solid var(--sw-border); font-size: 0.84rem;">
                    Batal
                </button>
                <button type="button" class="btn btn-danger px-4 py-2 fw-bold" id="btnKonfirmasiHapus" style="font-size: 0.84rem;">
                    Ya, Hapus Jurnal
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- 6. MODAL PREVIEW FOTO JURNAL --}}
{{-- ============================================================ --}}
<div class="modal fade simmas-modal" id="modalLihatFotoJurnal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark mb-0" id="lihatFotoJurnalTitle">Foto Bukti Kegiatan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="lihatFotoJurnalImg" alt="Foto Jurnal" class="img-fluid rounded shadow-sm" style="max-height: 120px; max-width: 160px; width: auto; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let deleteJurnalId = null;

// ============================================================
// BUKA MODAL TULIS JURNAL
// ============================================================
function bukaModalTulisJurnal() {
    clearFormErrors('formTulisJurnal');
    hapusPreviewTulis();
    document.getElementById('formTulisJurnal').reset();
    document.getElementById('tulis_tanggal').value = new Date().toISOString().split('T')[0];

    const modal = new bootstrap.Modal(document.getElementById('modalTulisJurnal'));
    modal.show();
}

// ============================================================
// BUKA MODAL EDIT JURNAL
// ============================================================
function bukaModalEditJurnal(jurnal) {
    clearFormErrors('formEditJurnal');
    hapusPreviewEdit();

    const form = document.getElementById('formEditJurnal');
    form.action = `{{ url('siswa/jurnal') }}/${jurnal.id}`;

    document.getElementById('edit_tanggal').value = jurnal.tanggal;
    document.getElementById('edit_kegiatan').value = jurnal.kegiatan || '';
    document.getElementById('edit_kendala').value = jurnal.kendala || '';
    document.getElementById('edit_solusi').value = jurnal.solusi || '';

    // Jika ada foto lama, tampilkan di preview
    if (jurnal.photo_url) {
        const previewWrap = document.getElementById('editPreviewWrap');
        const previewImg = document.getElementById('editPreviewImg');
        const dropzone = document.getElementById('editDropzone');

        previewImg.src = `{{ asset('storage') }}/${jurnal.photo_url}`;
        previewWrap.style.display = 'block';
        dropzone.style.display = 'none';
    }

    const modal = new bootstrap.Modal(document.getElementById('modalEditJurnal'));
    modal.show();
}

// ============================================================
// BUKA MODAL HAPUS JURNAL
// ============================================================
function bukaModalHapusJurnal(id, tglStr) {
    deleteJurnalId = id;
    document.getElementById('hapusJurnalTanggal').textContent = tglStr;
    const modal = new bootstrap.Modal(document.getElementById('modalHapusJurnal'));
    modal.show();
}

// ============================================================
// PREVIEW & REMOVE FOTO TULIS
// ============================================================
function previewImageTulis(input) {
    const previewWrap = document.getElementById('tulisPreviewWrap');
    const previewImg = document.getElementById('tulisPreviewImg');
    const dropzone = document.getElementById('tulisDropzone');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewWrap.style.display = 'block';
            dropzone.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function hapusPreviewTulis() {
    const input = document.getElementById('tulisPhotoInput');
    const previewWrap = document.getElementById('tulisPreviewWrap');
    const previewImg = document.getElementById('tulisPreviewImg');
    const dropzone = document.getElementById('tulisDropzone');

    if (input) input.value = '';
    if (previewImg) previewImg.src = '';
    if (previewWrap) previewWrap.style.display = 'none';
    if (dropzone) dropzone.style.display = 'block';
}

// ============================================================
// PREVIEW & REMOVE FOTO EDIT
// ============================================================
function previewImageEdit(input) {
    const previewWrap = document.getElementById('editPreviewWrap');
    const previewImg = document.getElementById('editPreviewImg');
    const dropzone = document.getElementById('editDropzone');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewWrap.style.display = 'block';
            dropzone.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function hapusPreviewEdit() {
    const input = document.getElementById('editPhotoInput');
    const previewWrap = document.getElementById('editPreviewWrap');
    const previewImg = document.getElementById('editPreviewImg');
    const dropzone = document.getElementById('editDropzone');

    if (input) input.value = '';
    if (previewImg) previewImg.src = '';
    if (previewWrap) previewWrap.style.display = 'none';
    if (dropzone) dropzone.style.display = 'block';
}

// ============================================================
// LIHAT FOTO JURNAL DI MODAL
// ============================================================
function lihatFotoJurnal(url, title) {
    document.getElementById('lihatFotoJurnalImg').src = url;
    document.getElementById('lihatFotoJurnalTitle').textContent = title || 'Foto Bukti Kegiatan';
    const modal = new bootstrap.Modal(document.getElementById('modalLihatFotoJurnal'));
    modal.show();
}

function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.querySelectorAll('.simmas-form-error').forEach(el => {
        el.style.display = 'none';
        el.textContent = '';
    });
    form.querySelectorAll('.simmas-form-control').forEach(el => {
        el.classList.remove('is-invalid');
    });
}

// ============================================================
// EVENT LISTENERS: SUBMIT TULIS, EDIT, DAN DELETE JURNAL
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    // 1. Submit Tulis Jurnal
    const formTulis = document.getElementById('formTulisJurnal');
    const submitBtnTulis = document.getElementById('btnSubmitTulis');
    const modalTulisEl = document.getElementById('modalTulisJurnal');
    const generalErrorTulis = document.getElementById('formTulisGeneralError');

    if (formTulis) {
        formTulis.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearFormErrors('formTulisJurnal');

            const formData = new FormData(formTulis);
            const originalBtnHtml = submitBtnTulis.innerHTML;

            submitBtnTulis.disabled = true;
            submitBtnTulis.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

            try {
                const response = await fetch(formTulis.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById(`error_tulis_${field}`);
                            const inputEl = document.getElementById(`tulis_${field}`);
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.style.display = 'block';
                            }
                            if (inputEl) {
                                inputEl.classList.add('is-invalid');
                            }
                        });
                    } else {
                        if (generalErrorTulis) {
                            generalErrorTulis.textContent = data.message || 'Terjadi kesalahan saat menyimpan jurnal.';
                            generalErrorTulis.style.display = 'block';
                        }
                    }
                    submitBtnTulis.disabled = false;
                    submitBtnTulis.innerHTML = originalBtnHtml;
                    return;
                }

                if (typeof window.showAppToast === 'function') {
                    window.showAppToast(data.message || 'Jurnal kegiatan berhasil dikirim!', 'success');
                }

                const bsModal = bootstrap.Modal.getInstance(modalTulisEl);
                if (bsModal) bsModal.hide();

                setTimeout(() => window.location.reload(), 600);

            } catch (err) {
                console.error(err);
                if (generalErrorTulis) {
                    generalErrorTulis.textContent = 'Gagal terhubung ke server. Silakan coba lagi.';
                    generalErrorTulis.style.display = 'block';
                }
                submitBtnTulis.disabled = false;
                submitBtnTulis.innerHTML = originalBtnHtml;
            }
        });
    }

    // 2. Submit Edit Jurnal
    const formEdit = document.getElementById('formEditJurnal');
    const submitBtnEdit = document.getElementById('btnSubmitEdit');
    const modalEditEl = document.getElementById('modalEditJurnal');
    const generalErrorEdit = document.getElementById('formEditGeneralError');

    if (formEdit) {
        formEdit.addEventListener('submit', async function (e) {
            e.preventDefault();
            clearFormErrors('formEditJurnal');

            const formData = new FormData(formEdit);
            const originalBtnHtml = submitBtnEdit.innerHTML;

            submitBtnEdit.disabled = true;
            submitBtnEdit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

            try {
                const response = await fetch(formEdit.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById(`error_edit_${field}`);
                            const inputEl = document.getElementById(`edit_${field}`);
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.style.display = 'block';
                            }
                            if (inputEl) {
                                inputEl.classList.add('is-invalid');
                            }
                        });
                    } else {
                        if (generalErrorEdit) {
                            generalErrorEdit.textContent = data.message || 'Terjadi kesalahan saat memperbarui jurnal.';
                            generalErrorEdit.style.display = 'block';
                        }
                    }
                    submitBtnEdit.disabled = false;
                    submitBtnEdit.innerHTML = originalBtnHtml;
                    return;
                }

                if (typeof window.showAppToast === 'function') {
                    window.showAppToast(data.message || 'Jurnal kegiatan berhasil diperbarui!', 'success');
                }

                const bsModal = bootstrap.Modal.getInstance(modalEditEl);
                if (bsModal) bsModal.hide();

                setTimeout(() => window.location.reload(), 600);

            } catch (err) {
                console.error(err);
                if (generalErrorEdit) {
                    generalErrorEdit.textContent = 'Gagal terhubung ke server. Silakan coba lagi.';
                    generalErrorEdit.style.display = 'block';
                }
                submitBtnEdit.disabled = false;
                submitBtnEdit.innerHTML = originalBtnHtml;
            }
        });
    }

    // 3. Konfirmasi Hapus Jurnal
    const btnHapus = document.getElementById('btnKonfirmasiHapus');
    const modalHapusEl = document.getElementById('modalHapusJurnal');

    if (btnHapus) {
        btnHapus.addEventListener('click', async function () {
            if (!deleteJurnalId) return;

            const originalBtnHtml = btnHapus.innerHTML;
            btnHapus.disabled = true;
            btnHapus.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...';

            try {
                const response = await fetch(`{{ url('siswa/jurnal') }}/${deleteJurnalId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    if (typeof window.showAppToast === 'function') {
                        window.showAppToast(data.message || 'Gagal menghapus jurnal.', 'danger');
                    }
                    btnHapus.disabled = false;
                    btnHapus.innerHTML = originalBtnHtml;
                    return;
                }

                if (typeof window.showAppToast === 'function') {
                    window.showAppToast(data.message || 'Jurnal kegiatan berhasil dihapus!', 'success');
                }

                const bsModal = bootstrap.Modal.getInstance(modalHapusEl);
                if (bsModal) bsModal.hide();

                setTimeout(() => window.location.reload(), 600);

            } catch (err) {
                console.error(err);
                if (typeof window.showAppToast === 'function') {
                    window.showAppToast('Gagal terhubung ke server.', 'danger');
                }
                btnHapus.disabled = false;
                btnHapus.innerHTML = originalBtnHtml;
            }
        });
    }
});
</script>
@endsection

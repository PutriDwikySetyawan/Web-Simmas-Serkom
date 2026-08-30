{{-- resources/views/guru/kunjungan.blade.php --}}
@extends('layouts.guru')

@section('title', 'Kunjungan Lapangan')
@section('page-title', 'Kunjungan Lapangan')

@section('styles')
<style>
    :root {
        --kj-radius-sm: 10px;
        --kj-radius-md: 14px;
        --kj-radius-lg: 18px;
        --kj-shadow-rest: 0 1px 2px rgba(16, 24, 40, 0.04);
        --kj-shadow-hover: 0 8px 24px -8px rgba(16, 24, 40, 0.14);
        --kj-transition: 180ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (prefers-reduced-motion: reduce) {
        * { animation-duration: 0.001ms !important; transition-duration: 0.001ms !important; }
    }

    .kj-subtitle {
        color: var(--guru-muted);
        font-size: 0.9rem;
        line-height: 1.55;
        max-width: 62ch;
    }

    /* ================= HEADER ================= */
    .kj-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }

    #btnBukaTambahKunjungan {
        border-radius: var(--kj-radius-sm);
        font-weight: 600;
        font-size: 0.88rem;
        padding: 0.6rem 1.15rem;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.06);
        transition: transform var(--kj-transition), box-shadow var(--kj-transition);
    }

    #btnBukaTambahKunjungan:hover {
        transform: translateY(-1px);
        box-shadow: var(--kj-shadow-hover);
    }

    /* ================= STAT CARDS ================= */
    .kj-stat-card {
        position: relative;
        background: #fff;
        border: 1px solid var(--guru-border);
        border-radius: var(--kj-radius-md);
        padding: 1.15rem 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.95rem;
        overflow: hidden;
        box-shadow: var(--kj-shadow-rest);
        transition: transform var(--kj-transition), box-shadow var(--kj-transition);
    }

    .kj-stat-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.35;
    }

    .kj-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--kj-shadow-hover);
    }

    .kj-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        transition: transform var(--kj-transition);
    }

    .kj-stat-card:hover .kj-stat-icon { transform: scale(1.06); }

    .kj-stat-icon.blue   { background: var(--guru-primary-soft); color: var(--guru-primary); }
    .kj-stat-icon.orange { background: #fff4e5; color: #d98324; }
    .kj-stat-icon.green  { background: #e7f8ef; color: #1c9c5b; }

    .kj-stat-card:has(.kj-stat-icon.blue)   { color: var(--guru-primary); }
    .kj-stat-card:has(.kj-stat-icon.orange) { color: #d98324; }
    .kj-stat-card:has(.kj-stat-icon.green)  { color: #1c9c5b; }

    .kj-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--guru-ink);
        line-height: 1.15;
        font-variant-numeric: tabular-nums;
    }

    .kj-stat-label {
        font-size: 0.78rem;
        color: var(--guru-muted);
        font-weight: 500;
    }

    /* ================= TIMELINE CARD ================= */
    .kj-timeline-card {
        background: #fff;
        border: 1px solid var(--guru-border);
        border-radius: var(--kj-radius-lg);
        padding: 1.6rem;
    }

    .kj-timeline-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--guru-ink);
        margin-bottom: 1.35rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .kj-timeline-title .badge {
        font-weight: 600;
        font-size: 0.72rem;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
    }

    .kj-timeline {
        position: relative;
        padding-left: 2.5rem;
    }

    .kj-timeline::before {
        content: "";
        position: absolute;
        left: 15px;
        top: 6px;
        bottom: 6px;
        width: 2px;
        background: linear-gradient(to bottom, var(--guru-border), transparent 98%);
    }

    .kj-timeline-item {
        position: relative;
        padding-bottom: 1.6rem;
        opacity: 0;
        transform: translateY(8px);
        animation: kjFadeIn 420ms ease forwards;
        animation-delay: calc(var(--kj-i, 0) * 70ms);
    }

    @keyframes kjFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    .kj-timeline-item:last-child { padding-bottom: 0; }

    .kj-timeline-dot {
        position: absolute;
        left: -2.5rem;
        top: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.92rem;
        z-index: 1;
        box-shadow: 0 0 0 4px #fff;
    }

    .kj-timeline-content {
        background: #fafbfd;
        border: 1px solid var(--guru-border);
        border-radius: 12px;
        padding: 1.05rem 1.2rem;
        transition: border-color var(--kj-transition), box-shadow var(--kj-transition), transform var(--kj-transition);
    }

    .kj-timeline-content:hover {
        border-color: color-mix(in srgb, var(--guru-primary) 35%, var(--guru-border));
        box-shadow: var(--kj-shadow-hover);
        transform: translateY(-1px);
    }

    .kj-timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.45rem;
    }

    .kj-dudi-name {
        font-weight: 700;
        color: var(--guru-ink);
        font-size: 0.96rem;
        letter-spacing: -0.01em;
    }

    .kj-tanggal {
        font-size: 0.76rem;
        color: var(--guru-muted);
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        background: #fff;
        border: 1px solid var(--guru-border);
        padding: 0.2rem 0.55rem;
        border-radius: 999px;
    }

    .kj-catatan {
        font-size: 0.86rem;
        color: var(--guru-ink);
        line-height: 1.6;
        margin-bottom: 0.75rem;
    }

    .kj-timeline-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .kj-btn-foto {
        font-size: 0.76rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        border: none;
        transition: background var(--kj-transition), transform var(--kj-transition);
    }

    .kj-btn-foto:hover { background: #e0e7ff; transform: translateY(-1px); }

    .kj-btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--guru-border);
        background: #fff;
        color: var(--guru-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: color var(--kj-transition), border-color var(--kj-transition), transform var(--kj-transition);
    }

    .kj-btn-icon.edit:hover { color: var(--guru-primary); border-color: var(--guru-primary); transform: translateY(-1px); }
    .kj-btn-icon.del:hover  { color: var(--guru-danger); border-color: var(--guru-danger); transform: translateY(-1px); }

    .kj-btn-icon:focus-visible,
    .kj-btn-foto:focus-visible,
    #btnBukaTambahKunjungan:focus-visible,
    .kj-upload-box:focus-within {
        outline: 2px solid var(--guru-primary);
        outline-offset: 2px;
    }

    /* ================= EMPTY STATE ================= */
    .kj-empty {
        padding: 3.75rem 1rem;
        text-align: center;
    }

    .kj-empty-icon {
        position: relative;
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
        margin: 0 auto 1.1rem;
    }

    .kj-empty-icon::after {
        content: "";
        position: absolute;
        inset: -10px;
        border: 1.5px dashed color-mix(in srgb, var(--guru-primary) 35%, transparent);
        border-radius: 50%;
    }

    .kj-empty-title {
        font-weight: 700;
        color: var(--guru-ink);
        margin-bottom: 0.3rem;
        font-size: 0.98rem;
    }

    .kj-empty-desc {
        color: var(--guru-muted);
        font-size: 0.86rem;
        max-width: 360px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ================= UPLOAD FOTO ================= */
    .kj-upload-box {
        border: 1.5px dashed var(--guru-border);
        border-radius: var(--kj-radius-sm);
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        color: var(--guru-muted);
        font-size: 0.82rem;
        transition: all var(--kj-transition);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }

    .kj-upload-box:hover {
        border-color: var(--guru-primary);
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
    }

    .kj-upload-box i {
        font-size: 1.35rem;
    }

    .kj-upload-preview {
        width: 100%;
        display: none;
        align-items: center;
        gap: 0.65rem;
        text-align: left;
        margin-top: 0.15rem;
    }

    .kj-upload-preview.show { display: flex; }

    .kj-upload-preview img {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--guru-border);
        flex-shrink: 0;
    }

    .kj-upload-filename {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--guru-ink);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ================= MODAL HAPUS (custom alert) ================= */
    .kj-alert-icon {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: var(--guru-danger-soft);
        color: var(--guru-danger);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 1rem;
    }

    /* ================= MODAL GENERAL POLISH ================= */
    .modal-content {
        border-radius: var(--kj-radius-lg);
        border: none;
        box-shadow: 0 24px 48px -12px rgba(16, 24, 40, 0.25);
    }

    .modal .form-control,
    .modal .form-select {
        border-radius: var(--kj-radius-sm);
        border-color: var(--guru-border);
        font-size: 0.88rem;
    }

    .modal .form-control:focus,
    .modal .form-select:focus {
        border-color: var(--guru-primary);
        box-shadow: 0 0 0 3px var(--guru-primary-soft);
    }

    .modal .btn {
        border-radius: var(--kj-radius-sm);
        font-weight: 600;
        font-size: 0.86rem;
    }

    #kjFotoModalImg {
    max-height: 340px;
    max-width: 380px;
    width: auto;
    object-fit: contain;
    border: 1px solid var(--guru-border);
}
</style>
@endsection

@section('content')

<div class="kj-header">
    <p class="kj-subtitle mb-0">
        Modul pencatatan dan dokumentasi monitoring langsung guru ke lokasi industri mitra (DUDI)
        dalam bentuk visual timeline kronologis.
    </p>
    <button type="button" class="btn btn-primary flex-shrink-0" id="btnBukaTambahKunjungan"
            data-bs-toggle="modal" data-bs-target="#modalKunjungan">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kunjungan
    </button>
</div>

{{-- ============================================================ --}}
{{-- STAT RINGKASAN --}}
{{-- ============================================================ --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="kj-stat-card">
            <div class="kj-stat-icon blue"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
                <div class="kj-stat-value">{{ $totalKunjungan }}</div>
                <div class="kj-stat-label">Total Kunjungan &mdash; Seluruh riwayat tercatat</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="kj-stat-card">
            <div class="kj-stat-icon orange"><i class="bi bi-calendar-event-fill"></i></div>
            <div>
                <div class="kj-stat-value">{{ $kunjunganBulanIni }}</div>
                <div class="kj-stat-label">Bulan Ini &mdash; Kunjungan periode berjalan</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="kj-stat-card">
            <div class="kj-stat-icon green"><i class="bi bi-building"></i></div>
            <div>
                <div class="kj-stat-value">{{ $totalDudiDikunjungi }}</div>
                <div class="kj-stat-label">DUDI Dikunjungi &mdash; Mitra industri unik</div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- TIMELINE KUNJUNGAN --}}
{{-- ============================================================ --}}
<div class="kj-timeline-card">
    @if ($kunjunganList->isEmpty())
        <div class="kj-empty">
            <div class="kj-empty-icon"><i class="bi bi-geo-alt"></i></div>
            <div class="kj-empty-title">Belum ada riwayat kunjungan</div>
            <p class="kj-empty-desc mb-0">
                Catat kunjungan lapangan pertama Anda ke tempat magang siswa dengan tombol &ldquo;Tambah Kunjungan&rdquo;.
            </p>
        </div>
    @else
        <div class="kj-timeline-title">
            Riwayat Kunjungan
            <span class="badge">{{ $kunjunganList->count() }} catatan</span>
        </div>
        <div class="kj-timeline">
            @foreach ($kunjunganList as $kunjungan)
                <div class="kj-timeline-item" style="--kj-i: {{ $loop->index }};">
                    <div class="kj-timeline-dot"><i class="bi bi-building"></i></div>
                    <div class="kj-timeline-content">
                        <div class="kj-timeline-header">
                            <div class="kj-dudi-name">{{ $kunjungan->tempatMagang->nama_perusahaan ?? '-' }}</div>
                            <div class="kj-tanggal">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($kunjungan->tanggal)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        <p class="kj-catatan mb-2">{{ $kunjungan->catatan }}</p>
                        <div class="kj-timeline-actions">
                           @if ($kunjungan->photo_url)
                            <button type="button" class="kj-btn-foto"
                                    onclick="lihatFotoKunjungan('{{ asset('storage/' . $kunjungan->photo_url) }}', '{{ $kunjungan->tempatMagang->nama_perusahaan ?? '-' }} - {{ \Carbon\Carbon::parse($kunjungan->tanggal)->translatedFormat('d F Y') }}')">
                                <i class="bi bi-image me-1"></i> Dokumentasi Kunjungan
                            </button>
                        @endif

                            <button type="button" class="kj-btn-icon edit btn-edit-kunjungan"
                                    data-id="{{ $kunjungan->id }}"
                                    data-tempat-magang-id="{{ $kunjungan->tempat_magang_id }}"
                                    data-tanggal="{{ $kunjungan->tanggal->format('Y-m-d') }}"
                                    data-catatan="{{ $kunjungan->catatan }}"
                                    data-bs-toggle="modal" data-bs-target="#modalKunjungan"
                                    title="Edit" aria-label="Edit kunjungan">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button type="button" class="kj-btn-icon del btn-hapus-kunjungan"
                                    data-id="{{ $kunjungan->id }}"
                                    data-dudi="{{ $kunjungan->tempatMagang->nama_perusahaan ?? '-' }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($kunjungan->tanggal)->translatedFormat('d F Y') }}"
                                    data-bs-toggle="modal" data-bs-target="#modalHapusKunjungan"
                                    title="Hapus" aria-label="Hapus kunjungan">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ============================================================ --}}
{{-- MODAL: FORM TAMBAH / EDIT KUNJUNGAN --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalKunjungan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="kjModalTitle">
                        <i class="bi bi-geo-alt text-primary me-1"></i>
                        Catat Kunjungan Industri
                    </h5>
                    <p class="text-muted small mb-0" id="kjModalSubtitle">
                        Dokumentasikan hasil monitoring guru ke tempat magang
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formKunjungan" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="kjKunjunganId" value="">

                    <div class="mb-3">
                        <label for="kjDudi" class="form-label fw-semibold small">Pilih Perusahaan DUDI</label>
                        <select class="form-select" id="kjDudi" name="tempat_magang_id" required>
                            <option value="" disabled selected>Pilih tempat magang...</option>
                            @foreach ($dudiList as $dudi)
                                <option value="{{ $dudi->id }}">{{ $dudi->nama_perusahaan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kjTanggal" class="form-label fw-semibold small">Tanggal Kunjungan</label>
                        <input type="date" class="form-control" id="kjTanggal" name="tanggal" required>
                    </div>

                    <div class="mb-3">
                        <label for="kjCatatan" class="form-label fw-semibold small">Catatan Evaluasi / Hasil Monitoring</label>
                        <textarea class="form-control" id="kjCatatan" name="catatan" rows="4" required
                                  placeholder="Monitoring perkembangan pengerjaan modul backend siswa, koordinasi dengan PIC industri terkait kehadiran dan kedisiplinan kerja."></textarea>
                    </div>

                    {{-- Upload foto hanya berlaku saat tambah baru — endpoint update() tidak menerima foto --}}
                    <div class="mb-2" id="kjUploadWrapper">
                        <label class="form-label fw-semibold small">
                            Foto Dokumentasi Lapangan <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <label for="kjFoto" class="kj-upload-box d-block mb-0" id="kjUploadBox">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <span id="kjUploadHint">Klik untuk upload foto bersama pembimbing industri</span>
                            <div class="kj-upload-preview" id="kjUploadPreview">
                                <img id="kjUploadThumb" src="" alt="">
                                <span class="kj-upload-filename" id="kjFotoNama"></span>
                            </div>
                        </label>
                        <input type="file" id="kjFoto" name="photo" accept="image/*" class="d-none">
                    </div>

                    <div class="invalid-feedback d-block" id="kjFormError"></div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary px-4" id="btnSimpanKunjungan">
                    <span class="btn-text">Simpan Kunjungan</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: KONFIRMASI HAPUS KUNJUNGAN --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalHapusKunjungan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center pt-4">
                <div class="kj-alert-icon"><i class="bi bi-trash"></i></div>
                <h6 class="fw-bold mb-2">Hapus Catatan Kunjungan?</h6>
                <p class="text-muted small mb-0">
                    Apakah Anda yakin ingin menghapus catatan kunjungan ke
                    <strong id="khDudi">-</strong> pada tanggal <strong id="khTanggal">-</strong>?
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger px-3" id="btnKonfirmasiHapus">
                    <span class="btn-text">Ya, Hapus</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: PREVIEW FOTO DOKUMENTASI KUNJUNGAN --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalLihatFotoKunjungan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold mb-0" id="kjFotoModalTitle">Dokumentasi Kunjungan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img src="" id="kjFotoModalImg" alt="Dokumentasi Kunjungan" class="img-fluid rounded shadow-sm">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modalKunjungan  = document.getElementById('modalKunjungan');
    const formKunjungan   = document.getElementById('formKunjungan');
    const btnSimpan       = document.getElementById('btnSimpanKunjungan');
    const btnHapus        = document.getElementById('btnKonfirmasiHapus');
    const kjFormError     = document.getElementById('kjFormError');
    const kjUploadWrapper = document.getElementById('kjUploadWrapper');
    const kjUploadHint    = document.getElementById('kjUploadHint');
    const kjUploadPreview = document.getElementById('kjUploadPreview');
    const kjUploadThumb   = document.getElementById('kjUploadThumb');

    let mode = 'tambah'; // 'tambah' | 'edit'
    let currentKunjunganId = null;
    let currentHapusId = null;

    // ============================================================
    // Tombol "Tambah Kunjungan" (header)
    // ============================================================
    document.getElementById('btnBukaTambahKunjungan').addEventListener('click', function () {
        resetFormTambah();
    });

    function resetFormTambah() {
        mode = 'tambah';
        currentKunjunganId = null;

        document.getElementById('kjModalTitle').innerHTML =
            '<i class="bi bi-geo-alt text-primary me-1"></i> Catat Kunjungan Industri';
        document.getElementById('kjModalSubtitle').textContent =
            'Dokumentasikan hasil monitoring guru ke tempat magang';

        formKunjungan.reset();
        resetUploadPreview();
        kjFormError.textContent = '';
        kjUploadWrapper.style.display = ''; // foto hanya tampil saat tambah baru
    }

    // ============================================================
// PREVIEW FOTO DOKUMENTASI KUNJUNGAN — TAMPIL DI MODAL,
// BUKAN MEMBUKA TAB BARU
// ============================================================
function lihatFotoKunjungan(url, title) {
    document.getElementById('kjFotoModalImg').src = url;
    document.getElementById('kjFotoModalTitle').textContent = title || 'Dokumentasi Kunjungan';
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLihatFotoKunjungan')).show();
}
window.lihatFotoKunjungan = lihatFotoKunjungan;

    // ============================================================
    // Tombol Edit (per item timeline)
    // ============================================================
    document.querySelectorAll('.btn-edit-kunjungan').forEach(function (btn) {
        btn.addEventListener('click', function () {
            mode = 'edit';
            currentKunjunganId = this.dataset.id;

            document.getElementById('kjModalTitle').innerHTML =
                '<i class="bi bi-pencil text-primary me-1"></i> Edit Catatan Kunjungan';
            document.getElementById('kjModalSubtitle').textContent =
                'Perbarui log kunjungan ke tempat magang';

            document.getElementById('kjDudi').value = this.dataset.tempatMagangId;
            document.getElementById('kjTanggal').value = this.dataset.tanggal;
            document.getElementById('kjCatatan').value = this.dataset.catatan;
            document.getElementById('kjFoto').value = '';
            resetUploadPreview();
            kjFormError.textContent = '';

            // update() tidak menerima foto — sembunyikan input upload saat edit
            kjUploadWrapper.style.display = 'none';
        });
    });

    // Preview foto yang dipilih (nama + thumbnail)
    document.getElementById('kjFoto').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) { resetUploadPreview(); return; }

        document.getElementById('kjFotoNama').textContent = file.name;
        kjUploadThumb.src = URL.createObjectURL(file);
        kjUploadHint.classList.add('d-none');
        kjUploadPreview.classList.add('show');
    });

    function resetUploadPreview() {
        document.getElementById('kjFotoNama').textContent = '';
        kjUploadThumb.src = '';
        kjUploadHint.classList.remove('d-none');
        kjUploadPreview.classList.remove('show');
    }

    modalKunjungan.addEventListener('hidden.bs.modal', function () {
        formKunjungan.reset();
        resetUploadPreview();
        kjFormError.textContent = '';
    });

    // ============================================================
    // Helper: parse response dengan aman (anti-crash kalau body kosong/non-JSON)
    // ============================================================
    async function parseResponseSafely(response) {
        // DELETE / beberapa endpoint bisa balas 204 No Content -> tidak ada body
        if (response.status === 204) {
            return {};
        }
        const text = await response.text();
        if (!text) return {};
        try {
            return JSON.parse(text);
        } catch (e) {
            // Body bukan JSON valid (mis. HTML error page) -> jangan crash,
            // lempar objek generik biar tetap ketangkep di catch sebagai error biasa
            throw { message: 'Response tidak valid dari server.' };
        }
    }

    // Helper: tutup modal dengan aman, tidak pernah throw ke pemanggilnya
    function closeModalSafely(modalEl) {
        try {
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        } catch (e) {
            console.warn('Gagal menutup modal secara normal:', e);
            // fallback manual kalau instance Bootstrap bermasalah
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        }
    }

    // ============================================================
    // Simpan Tambah (POST + FormData, karena ada upload foto)
    // ============================================================
    function simpanTambah() {
        const formData = new FormData(formKunjungan);

        fetch(`{{ url('guru/kunjungan') }}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
        })
        .then(async (response) => {
            const data = await parseResponseSafely(response);
            if (!response.ok) throw data;
            return data;
        })
        .then((data) => {
            // Tampilkan sukses & jadwalkan reload DULUAN,
            // supaya kalaupun proses tutup modal gagal, user tetap lihat notifikasi yang benar.
            toggleLoading(btnSimpan, false);
            showToast(data.message || 'Kunjungan berhasil dicatat.', 'success');
            setTimeout(() => window.location.reload(), 800);
            closeModalSafely(modalKunjungan);
        })
        .catch((err) => {
            toggleLoading(btnSimpan, false);
            handleFormError(err);
        });
    }

    // ============================================================
    // Simpan Edit (PUT + JSON, karena update() tidak proses file)
    // ============================================================
    function simpanEdit() {
        const payload = {
            tempat_magang_id: document.getElementById('kjDudi').value,
            tanggal: document.getElementById('kjTanggal').value,
            catatan: document.getElementById('kjCatatan').value,
        };

        fetch(`{{ url('guru/kunjungan') }}/${currentKunjunganId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload),
        })
        .then(async (response) => {
            const data = await parseResponseSafely(response);
            if (!response.ok) throw data;
            return data;
        })
        .then((data) => {
            toggleLoading(btnSimpan, false);
            showToast(data.message || 'Catatan kunjungan berhasil diperbarui.', 'success');
            setTimeout(() => window.location.reload(), 800);
            closeModalSafely(modalKunjungan);
        })
        .catch((err) => {
            toggleLoading(btnSimpan, false);
            handleFormError(err);
        });
    }

    function handleFormError(err) {
        if (err && err.errors) {
            const firstError = Object.values(err.errors)[0][0];
            kjFormError.textContent = firstError;
        } else {
            showToast((err && err.message) || 'Terjadi kesalahan saat menyimpan data.', 'danger');
        }
    }

    btnSimpan.addEventListener('click', function () {
        const dudi = document.getElementById('kjDudi').value;
        const tanggal = document.getElementById('kjTanggal').value;
        const catatan = document.getElementById('kjCatatan').value.trim();

        if (!dudi || !tanggal || !catatan) {
            kjFormError.textContent = 'Pilih DUDI, tanggal, dan catatan wajib diisi.';
            return;
        }
        kjFormError.textContent = '';

        toggleLoading(btnSimpan, true);

        if (mode === 'edit') {
            simpanEdit();
        } else {
            simpanTambah();
        }
    });

    // ============================================================
    // Konfirmasi Hapus
    // ============================================================
    document.querySelectorAll('.btn-hapus-kunjungan').forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentHapusId = this.dataset.id;
            document.getElementById('khDudi').textContent = this.dataset.dudi;
            document.getElementById('khTanggal').textContent = this.dataset.tanggal;
        });
    });

    btnHapus.addEventListener('click', function () {
        if (!currentHapusId) return;

        toggleLoading(btnHapus, true);

        fetch(`{{ url('guru/kunjungan') }}/${currentHapusId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(async (response) => {
            const data = await parseResponseSafely(response);
            if (!response.ok) throw data;
            return data;
        })
        .then((data) => {
            toggleLoading(btnHapus, false);
            showToast(data.message || 'Catatan kunjungan berhasil dihapus.', 'success');
            setTimeout(() => window.location.reload(), 800);
            closeModalSafely(document.getElementById('modalHapusKunjungan'));
        })
        .catch((err) => {
            toggleLoading(btnHapus, false);
            showToast((err && err.message) || 'Terjadi kesalahan saat menghapus data.', 'danger');
        });
    });

    // ============================================================
    // HELPER UMUM
    // ============================================================
    function toggleLoading(button, isLoading) {
        button.disabled = isLoading;
        button.querySelector('.btn-text').classList.toggle('d-none', isLoading);
        button.querySelector('.spinner-border').classList.toggle('d-none', !isLoading);
    }

    function showToast(message, type = 'success') {
        if (typeof window.showAppToast === 'function') {
            window.showAppToast(message, type);
            return;
        }
        alert(message);
    }
});
</script>
@endsection
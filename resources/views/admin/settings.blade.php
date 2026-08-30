@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

{{-- ================================================================
     CSS KHUSUS HALAMAN PENGATURAN SISTEM
     ================================================================ --}}
@push('styles')
<style>

    /* ===== 1. PANEL UTAMA (card pembungkus tab + form) ===== */
    .settings-panel {
        background: #fff;
        border: 1px solid var(--simmas-border);
        border-radius: 16px;
        padding: 0;
        max-width: 720px;
    }

    /* ===== 2. NAV TAB (Identitas Aplikasi / Halaman Depan / Data Sekolah) ===== */
    .settings-tabs {
        display: flex;
        gap: 4px;
        padding: 10px 10px 0 10px;
        border-bottom: 1px solid var(--simmas-border);
    }
    .settings-tabs .nav-link {
        border: none;
        border-radius: 10px 10px 0 0;
        padding: 10px 18px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--simmas-muted);
        background: transparent;
    }
    .settings-tabs .nav-link.active {
        color: var(--simmas-blue);
        background: var(--simmas-blue-light);
    }

    /* ===== 3. ISI FORM PER TAB ===== */
    .settings-body {
        padding: 24px;
    }
    .settings-field {
        margin-bottom: 16px;
    }
    .settings-field label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--simmas-ink);
        margin-bottom: 6px;
    }
    .settings-field .form-control,
    .settings-field .form-select {
        border-radius: 10px;
        border-color: var(--simmas-border);
        font-size: 0.86rem;
    }
    .settings-field .form-control:focus {
        border-color: var(--simmas-blue);
        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }
    .settings-field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .settings-hint {
        font-size: 0.74rem;
        color: var(--simmas-muted);
        margin-top: 4px;
    }

    /* ===== 4. FOOTER FORM (tombol simpan) ===== */
    .settings-footer {
        display: flex;
        justify-content: flex-end;
        padding: 16px 24px;
        border-top: 1px solid var(--simmas-border);
    }
    .settings-footer .btn-save {
        background: var(--simmas-blue);
        border: none;
        color: #fff;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .settings-footer .btn-save:hover { background: var(--simmas-blue-dark); }
    .settings-footer .btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

    /* ===== 5. TOAST NOTIFIKASI SUKSES ===== */
    .settings-toast {
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
    .settings-toast.is-active { display: block; }

    @media (max-width: 576px) {
        .settings-field-row { grid-template-columns: 1fr; }
    }
</style>
@endpush


@section('content')

{{-- ================================================================
     FORM UTAMA — 1 form membungkus ke-3 tab, submit sekali lewat AJAX
     ================================================================ --}}
<form id="formPengaturan">
    @csrf
    @method('PUT')

    <div class="settings-panel">

        {{-- ============================================================
             1. NAV TAB
             ============================================================ --}}
        <ul class="nav settings-tabs" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-identitas-btn" data-bs-toggle="tab"
                        data-bs-target="#tab-identitas" type="button" role="tab">
                    Identitas Aplikasi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-hero-btn" data-bs-toggle="tab"
                        data-bs-target="#tab-hero" type="button" role="tab">
                    Halaman Depan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-sekolah-btn" data-bs-toggle="tab"
                        data-bs-target="#tab-sekolah" type="button" role="tab">
                    Data Sekolah
                </button>
            </li>
        </ul>

        <div class="tab-content settings-body">

            {{-- ========================================================
                 2. TAB 1: IDENTITAS APLIKASI
                 Field: nama_aplikasi, kepanjangan, deskripsi_aplikasi
                 ======================================================== --}}
            <div class="tab-pane fade show active" id="tab-identitas" role="tabpanel">

                <div class="settings-field-row">
                    <div class="settings-field">
                        <label for="nama_aplikasi">Nama Aplikasi</label>
                        <input type="text" class="form-control" id="nama_aplikasi"
                               name="nama_aplikasi" value="{{ old('nama_aplikasi', $settings->nama_aplikasi ?? '') }}"
                               placeholder="SIMMAS" required>
                        <p class="text-danger small mt-1 d-none" data-error-for="nama_aplikasi"></p>
                    </div>

                    <div class="settings-field">
                        <label for="kepanjangan">Kepanjangan</label>
                        <input type="text" class="form-control" id="kepanjangan"
                               name="kepanjangan" value="{{ old('kepanjangan', $settings->kepanjangan ?? '') }}"
                               placeholder="Sistem Informasi Manajemen Magang Siswa" required>
                        <p class="text-danger small mt-1 d-none" data-error-for="kepanjangan"></p>
                    </div>
                </div>

                <div class="settings-field">
                    <label for="deskripsi_aplikasi">Deskripsi Aplikasi</label>
                    <textarea class="form-control" id="deskripsi_aplikasi" name="deskripsi_aplikasi"
                              rows="3" placeholder="Platform terpusat pengelolaan magang SMK. Mudah, modern, dan efisien."
                              required>{{ old('deskripsi_aplikasi', $settings->deskripsi_aplikasi ?? '') }}</textarea>
                    <p class="settings-hint">Deskripsi singkat, tampil di footer/meta halaman publik.</p>
                    <p class="text-danger small mt-1 d-none" data-error-for="deskripsi_aplikasi"></p>
                </div>

            </div>

            {{-- ========================================================
                 3. TAB 2: HALAMAN DEPAN
                 Field: hero_judul, hero_deskripsi
                 (dipakai section Hero di Landing Page publik)
                 ======================================================== --}}
            <div class="tab-pane fade" id="tab-hero" role="tabpanel">

                <div class="settings-field">
                    <label for="hero_judul">Judul Utama (Hero)</label>
                    <input type="text" class="form-control" id="hero_judul"
                           name="hero_judul" value="{{ old('hero_judul', $settings->hero_judul ?? '') }}"
                           placeholder="Kelola Magang Siswa Lebih Mudah" required>
                    <p class="text-danger small mt-1 d-none" data-error-for="hero_judul"></p>
                </div>

                <div class="settings-field">
                    <label for="hero_deskripsi">Deskripsi Hero</label>
                    <textarea class="form-control" id="hero_deskripsi" name="hero_deskripsi"
                              rows="3" placeholder="Deskripsi yang tampil di bawah judul utama landing page"
                              required>{{ old('hero_deskripsi', $settings->hero_deskripsi ?? '') }}</textarea>
                    <p class="settings-hint">Tampil di halaman depan publik (sebelum login).</p>
                    <p class="text-danger small mt-1 d-none" data-error-for="hero_deskripsi"></p>
                </div>

            </div>

            {{-- ========================================================
                 4. TAB 3: DATA SEKOLAH
                 Field: nama_sekolah, website_sekolah, nama_kepala_sekolah,
                        nip_kepala_sekolah, alamat_sekolah, no_telepon_sekolah
                 ======================================================== --}}
            <div class="tab-pane fade" id="tab-sekolah" role="tabpanel">

                <div class="settings-field-row">
                    <div class="settings-field">
                        <label for="nama_sekolah">Nama Sekolah</label>
                        <input type="text" class="form-control" id="nama_sekolah"
                               name="nama_sekolah" value="{{ old('nama_sekolah', $settings->nama_sekolah ?? '') }}"
                               placeholder="SMK Negeri 1 Contoh" required>
                        <p class="text-danger small mt-1 d-none" data-error-for="nama_sekolah"></p>
                    </div>

                    <div class="settings-field">
                        <label for="website_sekolah">Website Resmi</label>
                        <input type="url" class="form-control" id="website_sekolah"
                               name="website_sekolah" value="{{ old('website_sekolah', $settings->website_sekolah ?? '') }}"
                               placeholder="https://smkcontoh.sch.id">
                        <p class="text-danger small mt-1 d-none" data-error-for="website_sekolah"></p>
                    </div>
                </div>

                <div class="settings-field-row">
                    <div class="settings-field">
                        <label for="nama_kepala_sekolah">Nama Kepala Sekolah</label>
                        <input type="text" class="form-control" id="nama_kepala_sekolah"
                               name="nama_kepala_sekolah" value="{{ old('nama_kepala_sekolah', $settings->nama_kepala_sekolah ?? '') }}"
                               placeholder="Nama lengkap beserta gelar" required>
                        <p class="text-danger small mt-1 d-none" data-error-for="nama_kepala_sekolah"></p>
                    </div>

                    <div class="settings-field">
                        <label for="nip_kepala_sekolah">NIP Kepala Sekolah</label>
                        <input type="text" class="form-control" id="nip_kepala_sekolah"
                               name="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah', $settings->nip_kepala_sekolah ?? '') }}"
                               placeholder="NIP" required>
                        <p class="text-danger small mt-1 d-none" data-error-for="nip_kepala_sekolah"></p>
                    </div>
                </div>

                <div class="settings-field">
                    <label for="alamat_sekolah">Alamat Lengkap Sekolah</label>
                    <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah"
                              rows="2" required>{{ old('alamat_sekolah', $settings->alamat_sekolah ?? '') }}</textarea>
                    <p class="text-danger small mt-1 d-none" data-error-for="alamat_sekolah"></p>
                </div>

                <div class="settings-field">
                    <label for="no_telepon_sekolah">Nomor Telepon</label>
                    <input type="text" class="form-control" id="no_telepon_sekolah"
                           name="no_telepon_sekolah" value="{{ old('no_telepon_sekolah', $settings->no_telepon_sekolah ?? '') }}"
                           placeholder="Nomor telepon/WA sekolah" required>
                    <p class="text-danger small mt-1 d-none" data-error-for="no_telepon_sekolah"></p>
                </div>

            </div>

        </div>

        {{-- ============================================================
             5. FOOTER — TOMBOL SIMPAN (mengirim SEMUA field dari 3 tab
                sekaligus, walau user cuma buka 1 tab)
             ============================================================ --}}
        <div class="settings-footer">
            <button type="submit" class="btn-save" id="btnSimpanPengaturan">
                <i class="bi bi-save2"></i>
                <span id="btnSimpanPengaturanLabel">Simpan Pengaturan</span>
            </button>
        </div>

    </div>
</form>

{{-- ================================================================
     6. TOAST NOTIFIKASI
     ================================================================ --}}
<div class="settings-toast" id="settingsToast"></div>

@endsection


{{-- ================================================================
     7. JS: submit form via fetch (AJAX) ke endpoint JSON,
        validasi error per-field, dan toast sukses
     ================================================================ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const form       = document.getElementById('formPengaturan');
    const btnSimpan  = document.getElementById('btnSimpanPengaturan');
    const btnLabel   = document.getElementById('btnSimpanPengaturanLabel');
    const toast      = document.getElementById('settingsToast');

    function tampilkanToast(pesan) {
        toast.textContent = pesan;
        toast.classList.add('is-active');
        setTimeout(() => toast.classList.remove('is-active'), 3000);
    }

    function resetError() {
        form.querySelectorAll('[data-error-for]').forEach(el => {
            el.textContent = '';
            el.classList.add('d-none');
        });
    }

    function tampilkanError(errors) {
        let firstInvalidTabId = null;

        Object.keys(errors).forEach(field => {
            const el = form.querySelector(`[data-error-for="${field}"]`);
            const input = document.getElementById(field);

            if (el) {
                el.textContent = errors[field][0];
                el.classList.remove('d-none');
            }
            if (input) {
                input.classList.add('is-invalid');
                const tabPane = input.closest('.tab-pane');
                if (tabPane && !firstInvalidTabId) {
                    firstInvalidTabId = tabPane.id;
                }
            }
        });

        // Beralih ke tab yang ada error jika user sedang di tab lain
        if (firstInvalidTabId) {
            const triggerEl = document.querySelector(`button[data-bs-target="#${firstInvalidTabId}"]`);
            if (triggerEl) {
                const tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }

        tampilkanToast('Periksa kembali data yang dimasukkan.', 'danger');
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        resetError();
        form.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('is-invalid'));

        btnSimpan.disabled = true;
        btnLabel.textContent = 'Menyimpan...';

        try {
            const response = await fetch("{{ route('admin.settings.update') }}", {
                method: 'POST', // method spoofing PUT lewat @method('PUT') di form
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });

            const data = await response.json();

            if (response.status === 422) {
                tampilkanError(data.errors || {});
                return;
            }

            if (!response.ok) {
                tampilkanToast(data.message ?? 'Gagal menyimpan pengaturan.', 'danger');
                return;
            }

            tampilkanToast(data.message ?? 'Pengaturan berhasil disimpan.', 'success');

        } catch (error) {
            tampilkanToast('Gagal menghubungi server. Periksa koneksi Anda.', 'danger');
        } finally {
            btnSimpan.disabled = false;
            btnLabel.textContent = 'Simpan Pengaturan';
        }
    });

});
</script>
@endpush
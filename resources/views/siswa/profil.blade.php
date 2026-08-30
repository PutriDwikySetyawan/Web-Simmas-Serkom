@extends('layouts.siswa')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

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

    /* ================= PROFIL HEADER CARD ================= */
    .sw-profile-header {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-lg);
        padding: 1.75rem 2rem;
        box-shadow: var(--sw-shadow);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }

    .sw-profile-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--sw-primary), var(--sw-primary-dark));
        color: #fff;
        font-size: 1.85rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px -4px rgba(59, 91, 251, 0.4);
        flex-shrink: 0;
    }

    .sw-profile-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--sw-ink);
        margin-bottom: 0.25rem;
    }

    .sw-profile-email {
        font-size: 0.86rem;
        color: var(--sw-muted);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.6rem;
    }

    .sw-profile-tags {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .sw-tag {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .sw-tag.gray {
        background: #F3F4F6;
        color: #4B5563;
    }

    .sw-tag.green {
        background: #E7F8EF;
        color: #1C9C5B;
    }

    /* ================= SECTION CARDS ================= */
    .sw-card {
        background: #fff;
        border: 1px solid var(--sw-border);
        border-radius: var(--sw-radius-lg);
        padding: 1.5rem 1.75rem;
        box-shadow: var(--sw-shadow);
        margin-bottom: 1.5rem;
    }

    .sw-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--sw-border);
        margin-bottom: 1.25rem;
    }

    .sw-card-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--sw-ink);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    .sw-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--sw-primary-soft);
        color: var(--sw-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    /* ================= FORM CONTROLS ================= */
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

    .simmas-form-control:disabled,
    .simmas-form-control[readonly] {
        background: #F8FAFC;
        color: #64748B;
        cursor: not-allowed;
    }

    .simmas-form-error {
        font-size: 0.74rem;
        color: #DC2626;
        margin-top: 0.3rem;
        display: none;
    }

    .sw-btn-save {
        background: var(--sw-primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.7rem 1.75rem;
        font-size: 0.88rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        box-shadow: 0 4px 14px -3px rgba(59, 91, 251, 0.4);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .sw-btn-save:hover {
        background: var(--sw-primary-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .sw-btn-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Info Table / List */
    .sw-info-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .sw-info-item {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 0.75rem 0.9rem;
        background: #F8FAFC;
        border-radius: 10px;
        font-size: 0.84rem;
        gap: 1rem;
    }

    .sw-info-item-label {
        font-weight: 600;
        color: var(--sw-muted);
        flex-shrink: 0;
    }

    .sw-info-item-value {
        font-weight: 700;
        color: var(--sw-ink);
        text-align: right;
    }
</style>
@endsection

@section('content')

{{-- ============================================================ --}}
{{-- 1. HEADER PROFIL SISWA --}}
{{-- ============================================================ --}}
<div class="sw-profile-header">
    <div class="sw-profile-avatar">
        {{ strtoupper(substr($profile->nama ?? 'S', 0, 1)) }}
    </div>
    <div>
        <h4 class="sw-profile-title">{{ $profile->nama }}</h4>
        <div class="sw-profile-email">
            <i class="bi bi-envelope"></i> {{ $profile->email }}
        </div>
        <div class="sw-profile-tags">
            <span class="sw-tag">
                <i class="bi bi-person-badge"></i> Siswa Peserta Magang
            </span>
            <span class="sw-tag gray">
                <i class="bi bi-card-text"></i> NIS: {{ $siswa->nis ?? '-' }}
            </span>
            <span class="sw-tag gray">
                <i class="bi bi-mortarboard"></i> Kelas: {{ $siswa->kelas ?? '-' }}
            </span>
            <span class="sw-tag green">
                <i class="bi bi-check-circle-fill"></i> Akun Aktif
            </span>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ============================================================ --}}
    {{-- KOLOM KIRI: DETAIL PENEMPATAN & STATS --}}
    {{-- ============================================================ --}}
    <div class="col-lg-5">

        {{-- Card Penempatan Magang --}}
        <div class="sw-card">
            <div class="sw-card-header">
                <h6 class="sw-card-title">
                    <div class="sw-card-icon"><i class="bi bi-building"></i></div>
                    Informasi Penempatan Magang
                </h6>
            </div>

            <div class="sw-info-list">
                @if($siswa->penempatan && $siswa->penempatan->tempatMagang)
                    @php
                        $tm = $siswa->penempatan->tempatMagang;
                        $guru = $siswa->penempatan->guru;
                    @endphp
                    <div class="sw-info-item">
                        <span class="sw-info-item-label">Tempat DUDI</span>
                        <span class="sw-info-item-value text-primary">{{ $tm->nama_perusahaan }}</span>
                    </div>
                    <div class="sw-info-item">
                        <span class="sw-info-item-label">Alamat</span>
                        <span class="sw-info-item-value">{{ $tm->alamat ?? '-' }}</span>
                    </div>
                    <div class="sw-info-item">
                        <span class="sw-info-item-label">Periode</span>
                        <span class="sw-info-item-value">
                            {{ \Carbon\Carbon::parse($siswa->penempatan->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($siswa->penempatan->tanggal_selesai)->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <div class="sw-info-item">
                        <span class="sw-info-item-label">Pembimbing Sekolah</span>
                        <span class="sw-info-item-value">{{ $guru->profile->nama ?? 'Belum ditentukan' }}</span>
                    </div>
                @else
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
                        <p class="small mb-0">Anda belum memiliki penempatan DUDI aktif atau pengajuan magang Anda masih dalam proses peninjauan.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card Ringkasan Aktivitas --}}
        <div class="sw-card">
            <div class="sw-card-header">
                <h6 class="sw-card-title">
                    <div class="sw-card-icon"><i class="bi bi-activity"></i></div>
                    Aktivitas Magang Anda
                </h6>
            </div>

            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="p-3 rounded bg-light border">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Total Hadir</div>
                        <div class="fs-4 fw-bold text-success">{{ $totalHadir }} <span class="small fw-normal text-muted" style="font-size: 0.8rem;">hari</span></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded bg-light border">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Jurnal Terkirim</div>
                        <div class="fs-4 fw-bold text-primary">{{ $totalJurnal }} <span class="small fw-normal text-muted" style="font-size: 0.8rem;">laporan</span></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- KOLOM KANAN: FORM UPDATE PROFIL & UBAH PASSWORD --}}
    {{-- ============================================================ --}}
    <div class="col-lg-7">
        <div class="sw-card">
            <div class="sw-card-header">
                <h6 class="sw-card-title">
                    <div class="sw-card-icon"><i class="bi bi-shield-lock"></i></div>
                    Pengaturan Profil & Keamanan
                </h6>
            </div>

            <form id="formUpdateProfil" method="POST" action="{{ route('siswa.profil.update') }}">
                @csrf

                {{-- Alert Flash Message --}}
                @if(session('success'))
                    <div class="alert alert-success py-2 small mb-3 d-flex align-items-center gap-2 rounded-3">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger py-2 small mb-3 d-flex align-items-center gap-2 rounded-3">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Alert General Error --}}
                <div class="alert alert-danger py-2 small mb-3 simmas-form-error" id="formGeneralError"></div>

                {{-- SECTION 1: INFORMASI AKUN --}}
                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.88rem;">1. Informasi Dasar Siswa</h6>

                <div class="mb-3">
                    <label class="simmas-form-label" for="nama">NAMA LENGKAP <span class="text-danger">*</span></label>
                    <input type="text"
                           name="nama"
                           id="nama"
                           class="simmas-form-control"
                           value="{{ old('nama', $profile->nama) }}"
                           required>
                    <div class="simmas-form-error" id="error_nama"></div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="simmas-form-label" for="email">ALAMAT EMAIL</label>
                        <input type="email"
                               id="email"
                               class="simmas-form-control"
                               value="{{ $profile->email }}"
                               readonly
                               title="Email akun dikelola oleh administrator sekolah">
                    </div>

                    <div class="col-md-6">
                        <label class="simmas-form-label" for="nis">NOMOR INDUK SISWA (NIS)</label>
                        <input type="text"
                               id="nis"
                               class="simmas-form-control"
                               value="{{ $siswa->nis ?? '-' }}"
                               readonly
                               title="NIS terdaftar di data pokok sekolah">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="simmas-form-label" for="kelas">KELAS / JURUSAN</label>
                    <input type="text"
                           id="kelas"
                           class="simmas-form-control"
                           value="{{ $siswa->kelas ?? '-' }}"
                           readonly
                           title="Kelas siswa terdaftar resmi">
                </div>

                <hr class="my-4" style="border-color: var(--sw-border);">

                {{-- SECTION 2: GANTI KATA SANDI --}}
                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.88rem;">2. Ganti Kata Sandi (Opsional)</h6>
                <p class="text-muted small mb-3">Kosongkan bagian ini jika Anda tidak ingin mengubah kata sandi saat ini.</p>

                <div class="mb-3">
                    <label class="simmas-form-label" for="password_lama">KATA SANDI SAAT INI</label>
                    <div class="input-group">
                        <input type="password"
                               name="password_lama"
                               id="password_lama"
                               class="simmas-form-control form-control"
                               placeholder="Masukkan kata sandi saat ini untuk verifikasi">
                        <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="password_lama" style="border-color: var(--sw-border);">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="simmas-form-error" id="error_password_lama"></div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="simmas-form-label" for="password_baru">KATA SANDI BARU</label>
                        <div class="input-group">
                            <input type="password"
                                   name="password_baru"
                                   id="password_baru"
                                   class="simmas-form-control form-control"
                                   placeholder="Minimal 8 karakter">
                            <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="password_baru" style="border-color: var(--sw-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="simmas-form-error" id="error_password_baru"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="simmas-form-label" for="password_baru_confirmation">KONFIRMASI KATA SANDI BARU</label>
                        <div class="input-group">
                            <input type="password"
                                   name="password_baru_confirmation"
                                   id="password_baru_confirmation"
                                   class="simmas-form-control form-control"
                                   placeholder="Ulangi kata sandi baru">
                            <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="password_baru_confirmation" style="border-color: var(--sw-border);">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="sw-btn-save" id="btnSubmitProfil">
                        <i class="bi bi-check2-circle"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formUpdateProfil');
    const submitBtn = document.getElementById('btnSubmitProfil');
    const generalError = document.getElementById('formGeneralError');

    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            if (!input) return;
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    });

    if (!form) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Clear error states
        if (generalError) {
            generalError.style.display = 'none';
            generalError.textContent = '';
        }
        document.querySelectorAll('.simmas-form-error').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });
        document.querySelectorAll('.simmas-form-control').forEach(el => {
            el.classList.remove('is-invalid');
        });

        const formData = new FormData(form);
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

        try {
            const response = await fetch(form.action, {
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
                        const errorEl = document.getElementById(`error_${field}`);
                        const inputEl = document.getElementById(field);
                        if (errorEl) {
                            errorEl.textContent = data.errors[field][0];
                            errorEl.style.display = 'block';
                        }
                        if (inputEl) {
                            inputEl.classList.add('is-invalid');
                        }
                    });
                } else {
                    if (generalError) {
                        generalError.textContent = data.message || 'Terjadi kesalahan saat memperbarui profil.';
                        generalError.style.display = 'block';
                    }
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                return;
            }

            // Sukses
            if (typeof window.showAppToast === 'function') {
                window.showAppToast(data.message || 'Pengaturan profil berhasil disimpan!', 'success');
            } else {
                alert(data.message || 'Pengaturan profil berhasil disimpan!');
            }

            // Kosongkan form password jika tadi diisi
            const passLama = document.getElementById('password_lama');
            const passBaru = document.getElementById('password_baru');
            const passKonf = document.getElementById('password_baru_confirmation');
            if (passLama) passLama.value = '';
            if (passBaru) passBaru.value = '';
            if (passKonf) passKonf.value = '';

            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;

            setTimeout(() => {
                window.location.reload();
            }, 700);

        } catch (err) {
            console.error(err);
            if (generalError) {
                generalError.textContent = 'Gagal terhubung ke server. Silakan coba lagi.';
                generalError.style.display = 'block';
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    });
});
</script>
@endsection


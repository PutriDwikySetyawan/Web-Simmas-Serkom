@extends('layouts.guest')

@section('hide_navbar', true)
@section('hide_footer', true)

@section('title', 'Lupa Kata Sandi - ' . config('simmas.nama_aplikasi', 'SIMMAS'))

@section('meta_description', 'Bantuan pemulihan kata sandi akun SIMMAS bagi Siswa Magang, Guru Pembimbing, dan Administrator.')

@push('styles')
<style>
    /* =========================================================
       RESET & WRAPPER
    ========================================================= */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        min-height: 100%;
    }

    body {
        overflow-x: hidden;
        background: #f8fafc;
    }

    main {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .auth-wrap, .auth-wrap * {
        box-sizing: border-box;
    }

    .auth-wrap {
        width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: row;
        margin: 0;
        padding: 0;
    }

    /* =========================================================
       PANEL KIRI (BRANDING)
    ========================================================= */
    .auth-brand {
        position: relative;
        overflow: hidden;
        width: 45%;
        min-width: 45%;
        flex: 0 0 45%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 48px 56px;
        background: linear-gradient(160deg, var(--simmas-blue, #3b82f6) 0%, var(--simmas-blue-dark, #1d4ed8) 100%);
        color: #fff;
    }

    .auth-brand__ring {
        position: absolute;
        border-radius: 50%;
        border: 40px solid rgba(255, 255, 255, 0.06);
        pointer-events: none;
        z-index: 0;
    }

    .auth-brand__ring--top {
        top: -120px;
        right: -120px;
        width: 300px;
        height: 300px;
    }

    .auth-brand__ring--bottom {
        bottom: -160px;
        left: -100px;
        width: 360px;
        height: 360px;
    }

    .auth-brand__logo {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        font-size: 1.15rem;
        color: #fff;
        text-decoration: none;
    }

    .auth-brand__logo-mark {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.18);
        font-size: 1.2rem;
    }

    .auth-brand__body {
        position: relative;
        z-index: 1;
        margin: auto 0;
    }

    .auth-brand__eyebrow {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 8px;
    }

    .auth-brand__title {
        font-size: clamp(1.8rem, 3.5vw, 2.5rem);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin-bottom: 16px;
    }

    .auth-brand__desc {
        font-size: 0.95rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.85);
        max-width: 420px;
    }

    .auth-brand__footer {
        position: relative;
        z-index: 1;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.65);
    }

    /* =========================================================
       PANEL KANAN (FORM)
    ========================================================= */
    .auth-form-panel {
        width: 55%;
        flex: 1 1 55%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px;
        background: #fff;
    }

    .auth-form-card {
        width: 100%;
        max-width: 440px;
    }

    .auth-title {
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--simmas-ink, #0f172a);
        margin-bottom: 6px;
        letter-spacing: -0.02em;
    }

    .auth-subtitle {
        font-size: 0.88rem;
        color: var(--simmas-muted, #64748b);
        margin-bottom: 28px;
        line-height: 1.5;
    }

    .auth-input-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--simmas-ink, #0f172a);
        margin-bottom: 6px;
        display: block;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .auth-form-control {
        border-radius: 10px;
        border: 1.5px solid var(--simmas-border, #e2e8f0);
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.15s ease;
        background: #fff;
        width: 100%;
    }

    .auth-form-control:focus {
        border-color: var(--simmas-blue, #3b82f6);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        outline: none;
    }

    .auth-btn-submit {
        background: var(--simmas-blue, #3b82f6);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 0.9rem;
        font-weight: 700;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
    }

    .auth-btn-submit:hover {
        background: var(--simmas-blue-dark, #1d4ed8);
        color: #fff;
    }

    .auth-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--simmas-muted, #64748b);
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .auth-back-link:hover {
        color: var(--simmas-blue, #3b82f6);
    }

    .auth-info-box {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        margin-top: 24px;
    }

    .auth-info-box-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .auth-info-box-desc {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 0;
    }

    @media (max-width: 991px) {
        .auth-wrap {
            flex-direction: column;
        }
        .auth-brand {
            width: 100%;
            min-width: 100%;
            flex: 0 0 auto;
            min-height: auto;
            padding: 32px 24px;
        }
        .auth-form-panel {
            width: 100%;
            flex: 1 1 auto;
            min-height: auto;
            padding: 36px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-wrap">

    {{-- ======================================================== --}}
    {{-- PANEL KIRI: BRANDING & INFORMASI SEKOLAH --}}
    {{-- ======================================================== --}}
    <div class="auth-brand">
        <div class="auth-brand__ring auth-brand__ring--top"></div>
        <div class="auth-brand__ring auth-brand__ring--bottom"></div>

        <a href="{{ url('/') }}" class="auth-brand__logo">
            <div class="auth-brand__logo-mark">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span>{{ config('simmas.nama_aplikasi', 'SIMMAS') }}</span>
        </a>

        <div class="auth-brand__body">
            <p class="auth-brand__eyebrow">Pusat Bantuan Akun</p>
            <h1 class="auth-brand__title">Pemulihan<br>Kata Sandi.</h1>
            <p class="auth-brand__desc">
                Lupa kata sandi akun Anda? Masukkan alamat email yang terdaftar untuk menerima instruksi pemulihan atau hubungi administrator sekolah.
            </p>
        </div>

        <div class="auth-brand__footer">
            <p class="mb-0">
                &copy; {{ date('Y') }} {{ config('simmas.nama_sekolah', 'SMK Negeri 1 Bangil') }} &mdash; {{ config('simmas.nama_aplikasi', 'SIMMAS') }}.
            </p>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- PANEL KANAN: FORMULIR RESET PASSWORD --}}
    {{-- ======================================================== --}}
    <div class="auth-form-panel">
        <div class="auth-form-card">

            <a href="{{ route('login') }}" class="auth-back-link mb-4">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Masuk
            </a>

            <h2 class="auth-title">Lupa Kata Sandi?</h2>
            <p class="auth-subtitle">
                Masukkan email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
            </p>

            @if(session('status'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4 rounded-3 small">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger mb-4 rounded-3 small">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="auth-input-label">Alamat Email Terdaftar</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control auth-form-control {{ isset($errors) && $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}"
                           placeholder="nama@sekolah.sch.id"
                           required
                           autofocus>
                    @if(isset($errors) && $errors->has('email'))
                        <div class="invalid-feedback small">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <button type="submit" class="auth-btn-submit mb-3">
                    <i class="bi bi-send-fill"></i> Kirim Tautan Pemulihan
                </button>
            </form>

            {{-- BANTUAN KONTAK ADMINISTRATOR --}}
            <div class="auth-info-box">
                <div class="auth-info-box-title">
                    <i class="bi bi-info-circle-fill text-primary"></i> Bantuan Administrator
                </div>
                <p class="auth-info-box-desc">
                    Akun Siswa dan Guru dikelola oleh Administrator <strong>{{ config('simmas.nama_sekolah', 'SMK') }}</strong>. Jika Anda tidak memiliki akses ke email Anda, silakan hubungi admin sekolah di <strong>{{ config('simmas.no_telepon_sekolah', '(0343) 744144') }}</strong>.
                </p>
            </div>

        </div>
    </div>

</div>
@endsection

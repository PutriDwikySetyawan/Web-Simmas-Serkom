@extends('layouts.guest')

@section('hide_navbar', true)
@section('hide_footer', true)

@section('title', 'Masuk - SIMMAS')

@section(
    'meta_description',
    'Masuk ke akun SIMMAS sesuai peran Anda: Administrator, Guru Pembimbing, atau Siswa Magang.'
)

@push('styles')
<style>
    /* =========================================================
       RESET
    ========================================================= */

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        min-height: 100%;
    }

    body {
        overflow-x: hidden;
    }

    main {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    .login-wrap,
    .login-wrap * {
        box-sizing: border-box;
    }

    /* =========================================================
       WRAPPER UTAMA
    ========================================================= */

    .login-wrap {
        width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: row;
        margin: 0;
        padding: 0;
    }

    /* =========================================================
       PANEL KIRI
    ========================================================= */

    .login-brand {
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

        background: linear-gradient(
            160deg,
            var(--simmas-blue) 0%,
            var(--simmas-blue-dark) 100%
        );

        color: #fff;
    }

    /* Ring dekorasi */

    .login-brand__ring {
        position: absolute;
        border-radius: 50%;
        border: 40px solid rgba(255, 255, 255, 0.06);
        pointer-events: none;
        z-index: 0;
    }

    .login-brand__ring--top {
        top: -120px;
        right: -120px;
        width: 300px;
        height: 300px;
    }

    .login-brand__ring--bottom {
        bottom: -160px;
        left: -100px;
        width: 360px;
        height: 360px;
    }

    /* Logo */

    .login-brand__logo {
        position: relative;
        z-index: 1;

        display: flex;
        align-items: center;
        gap: 10px;

        font-weight: 800;
        font-size: 1.05rem;
    }

    .login-brand__logo-mark {
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;
        background: rgba(255, 255, 255, 0.15);

        font-size: 1.1rem;
    }

    /* Isi panel */

    .login-brand__body {
        position: relative;
        z-index: 1;
    }

    .login-brand__eyebrow {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;

        color: rgba(255, 255, 255, 0.65);

        margin-bottom: 10px;
    }

    .login-brand__title {
        font-family: var(--font-display);
        font-weight: 800;

        font-size: clamp(2rem, 3.2vw, 2.6rem);
        line-height: 1.1;

        margin-bottom: 14px;
    }

    .login-brand__desc {
        color: rgba(255, 255, 255, 0.75);

        font-size: 0.92rem;
        line-height: 1.65;

        max-width: 340px;

        margin-bottom: 24px;
    }

    /* Checklist */

    .login-brand__checklist {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .login-brand__checklist li {
        display: flex;
        align-items: center;

        gap: 10px;

        font-size: 0.88rem;

        margin-bottom: 10px;
    }

    .login-brand__check {
        width: 20px;
        height: 20px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.15);

        font-size: 0.68rem;
    }

    /* =========================================================
       STATISTIK
    ========================================================= */

    .login-brand__stats {
        position: relative;
        z-index: 1;

        display: flex;
        gap: 40px;
    }

    .login-brand__stat {
        min-width: 90px;
    }

    .login-brand__stat-value {
        font-family: var(--font-display);

        font-weight: 800;
        font-size: 1.4rem;

        display: block;
    }

    .login-brand__stat-label {
        font-size: 0.7rem;

        color: rgba(255, 255, 255, 0.6);
    }

    /* =========================================================
       PANEL KANAN
    ========================================================= */

    .login-form-panel {
        width: 55%;
        min-width: 55%;
        flex: 0 0 55%;

        min-height: 100vh;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 40px 24px;

        background: #fff;
    }

    .login-form-box {
        width: 100%;
        max-width: 380px;
    }

    /* =========================================================
       JUDUL FORM
    ========================================================= */

    .login-form-box__eyebrow {
        font-size: 0.75rem;
        font-weight: 700;

        letter-spacing: 0.06em;
        text-transform: uppercase;

        color: var(--simmas-muted);

        margin-bottom: 8px;
    }

    .login-form-box__title {
        font-family: var(--font-display);

        font-weight: 800;
        font-size: 1.7rem;

        color: var(--simmas-ink);

        margin-bottom: 6px;
    }

    .login-form-box__title span {
        color: var(--simmas-blue);
    }

    .login-form-box__subtitle {
        color: var(--simmas-muted);

        font-size: 0.88rem;

        margin-bottom: 28px;
        line-height: 1.6;
    }

    /* =========================================================
       INPUT
    ========================================================= */

    .login-form-box label {
        display: block;

        font-size: 0.82rem;
        font-weight: 600;

        color: var(--simmas-ink);

        margin-bottom: 6px;
    }

    .login-form-box .form-control {
        width: 100%;

        border: 1px solid var(--simmas-border);

        border-radius: 10px;

        padding: 10px 14px;

        font-size: 0.9rem;
    }

    .login-form-box .form-control:focus {
        border-color: var(--simmas-blue);

        box-shadow: 0 0 0 3px var(--simmas-blue-light);
    }

    /* =========================================================
       PASSWORD
    ========================================================= */

    .login-password-wrap {
        position: relative;
    }

    .login-password-wrap .form-control {
        padding-right: 42px;
    }

    .login-password-toggle {
        position: absolute;

        top: 50%;
        right: 12px;

        transform: translateY(-50%);

        border: none;
        background: transparent;

        color: var(--simmas-muted);

        cursor: pointer;

        padding: 4px;
    }

    .login-password-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .login-forgot-link {
        font-size: 0.8rem;
        font-weight: 600;

        color: var(--simmas-blue);

        text-decoration: none;
    }

    .login-forgot-link:hover {
        text-decoration: underline;
    }

    /* =========================================================
       ERROR
    ========================================================= */

    .login-error-msg {
        font-size: 0.78rem;
        color: #dc2626;

        margin-top: 4px;
        margin-bottom: 0;
    }

    /* =========================================================
       BUTTON LOGIN
    ========================================================= */

    .login-submit-btn {
        width: 100%;

        padding: 11px 0;

        border-radius: 10px;

        font-weight: 700;

        border: none;

        background: var(--simmas-blue);
        color: #fff;

        transition: background 0.2s ease;
    }

    .login-submit-btn:hover {
        background: var(--simmas-blue-dark);
        color: #fff;
    }

    /* =========================================================
       TOMBOL KEMBALI
    ========================================================= */

    .login-back {
        display: flex;
        justify-content: center;

        margin-top: 18px;
    }

    .login-back a {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        font-size: 0.82rem;
        font-weight: 600;

        color: var(--simmas-muted);

        text-decoration: none;

        transition: color 0.2s ease;
    }

    .login-back a:hover {
        color: var(--simmas-blue);
    }

    /* =========================================================
       LOADING
    ========================================================= */

    .login-loading-overlay {
        position: fixed;
        inset: 0;

        background: #fff;

        display: none;

        flex-direction: column;
        align-items: center;
        justify-content: center;

        gap: 14px;

        z-index: 9999;
    }

    .login-loading-overlay.is-active {
        display: flex;
    }

    .login-loading-overlay__spinner {
        width: 40px;
        height: 40px;

        border: 4px solid var(--simmas-blue-light);
        border-top-color: var(--simmas-blue);

        border-radius: 50%;

        animation: loginSpin 0.7s linear infinite;
    }

    .login-loading-overlay__text {
        font-size: 0.88rem;

        color: var(--simmas-muted);

        font-weight: 600;
    }

    @keyframes loginSpin {
        to {
            transform: rotate(360deg);
        }
    }

    /* =========================================================
       AKUN DEMO
    ========================================================= */

    .login-demo-box {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--simmas-border);
    }

    .login-demo-box__title {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--simmas-ink);
        margin-bottom: 2px;
    }

    .login-demo-box__hint {
        font-size: 0.74rem;
        color: var(--simmas-muted);
        margin-bottom: 12px;
    }

    .login-demo-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .login-demo-item {
        display: flex;
        align-items: center;
        justify-content: space-between;

        width: 100%;

        padding: 9px 12px;

        border: 1px solid var(--simmas-border);
        border-radius: 10px;
        background: #fff;

        cursor: pointer;
        text-align: left;

        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .login-demo-item:hover {
        border-color: var(--simmas-blue);
        background: var(--simmas-blue-light);
    }

    /* Kondisi saat item demo sedang diproses (dinonaktifkan sementara agar tidak diklik dobel) */
    .login-demo-item:disabled,
    .login-demo-item.is-loading {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
    }

    .login-demo-item__email {
        font-size: 0.8rem;
        color: var(--simmas-ink);
    }

    .login-demo-item__role {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.04em;

        padding: 3px 8px;
        border-radius: 999px;

        color: #fff;
    }

    .login-demo-item__role--admin {
        background: #dc2626;
    }

    .login-demo-item__role--guru {
        background: #d97706;
    }

    .login-demo-item__role--siswa {
        background: #16a34a;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .login-brand {
            display: none !important;
        }

        .login-form-panel {
            width: 100% !important;
            min-width: 100% !important;
            flex: 0 0 100% !important;
        }
    }

    @media (max-width: 576px) {

        .login-form-panel {
            padding: 30px 20px;
        }

        .login-form-box {
            max-width: 100%;
        }
    }
</style>
@endpush


@section('content')

<div class="login-wrap">

    {{-- =====================================================
         PANEL KIRI
    ====================================================== --}}

    <div class="login-brand">

        {{-- Ring dekorasi --}}
        <span class="login-brand__ring login-brand__ring--top"></span>
        <span class="login-brand__ring login-brand__ring--bottom"></span>

        {{-- Logo --}}
        <div class="login-brand__logo">

            <span class="login-brand__logo-mark">
                <i class="bi bi-mortarboard-fill"></i>
            </span>

            SIMMAS

        </div>


        {{-- Konten utama --}}
        <div class="login-brand__body">

            <p class="login-brand__eyebrow">
                Sistem Informasi Manajemen Magang Siswa
            </p>

            <h1 class="login-brand__title">
                Magang lebih teratur.
            </h1>

            <p class="login-brand__desc">
                Platform manajemen magang siswa SMK yang menghubungkan sekolah,
                guru pembimbing, dan dunia usaha dalam satu sistem terpadu.
            </p>

            <ul class="login-brand__checklist">

                <li>
                    <span class="login-brand__check">
                        <i class="bi bi-check-lg"></i>
                    </span>

                    Penempatan magang terpusat & transparan
                </li>

                <li>
                    <span class="login-brand__check">
                        <i class="bi bi-check-lg"></i>
                    </span>

                    Monitoring kehadiran & jurnal real-time
                </li>

                <li>
                    <span class="login-brand__check">
                        <i class="bi bi-check-lg"></i>
                    </span>

                    Koordinasi sekolah, guru, dan industri
                </li>

            </ul>

        </div>


        {{-- =====================================================
             STATISTIK DARI DATABASE
        ====================================================== --}}

        <div class="login-brand__stats">

            <div class="login-brand__stat">

                <span class="login-brand__stat-value">
                    {{ $jumlahGuru }}
                </span>

                <span class="login-brand__stat-label">
                    Guru Pembimbing
                </span>

            </div>


            <div class="login-brand__stat">

                <span class="login-brand__stat-value">
                    {{ $jumlahSiswa }}
                </span>

                <span class="login-brand__stat-label">
                    Siswa Terdaftar
                </span>

            </div>


            <div class="login-brand__stat">

                <span class="login-brand__stat-value">
                    {{ $jumlahPenempatanMagang }}
                </span>

                <span class="login-brand__stat-label">
                    Penempatan Magang
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         PANEL KANAN
         PENTING: PANEL INI HARUS DI LUAR .login-brand
    ====================================================== --}}

    <div class="login-form-panel">

        <div class="login-form-box">

            <p class="login-form-box__eyebrow">
                Portal Masuk
            </p>

            <h2 class="login-form-box__title">
                Masuk ke <span>akun Anda.</span>
            </h2>

            <p class="login-form-box__subtitle">
                Gunakan email sekolah dan password yang diberikan oleh admin sekolah.
            </p>


            {{-- FORM LOGIN --}}

            <form
                id="loginForm"
                method="POST"
                action="{{ route('login.submit') }}"
            >

                @csrf


                {{-- EMAIL --}}

                <div class="mb-3">

                    <label for="email">
                        Email Sekolah
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email sekolah"
                        required
                        autofocus
                    >

                    @error('email')
                        <p class="login-error-msg">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- PASSWORD --}}

                <div class="mb-3">

                    <div class="login-password-label-row">

                        <label for="password">
                            Password
                        </label>

                        <a
                            href="{{ route('password.request') }}"
                            class="login-forgot-link"
                        >
                            Lupa?
                        </a>

                    </div>


                    <div class="login-password-wrap">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password"
                            minlength="6"
                            required
                        >

                        <button
                            type="button"
                            class="login-password-toggle"
                            id="togglePassword"
                            aria-label="Tampilkan/sembunyikan password"
                        >
                            <i
                                class="bi bi-eye"
                                id="togglePasswordIcon"
                            ></i>
                        </button>

                    </div>


                    @error('password')
                        <p class="login-error-msg">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- BUTTON LOGIN --}}

                <button
                    type="submit"
                    class="login-submit-btn"
                >
                    Masuk Dashboard
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>


            {{-- =================================================
                 AKUN DEMO
                 Klik salah satu akun -> form otomatis diisi lalu
                 langsung ter-submit (auto login), tidak perlu klik
                 tombol "Masuk Dashboard" lagi.
            ================================================== --}}

            <div class="login-demo-box">

                <p class="login-demo-box__title">
                    Akun Demo
                </p>

                <p class="login-demo-box__hint">
                    Klik salah satu akun untuk langsung masuk ke dashboard sesuai role.
                </p>

                <div class="login-demo-list">

                    <button
                        type="button"
                        class="login-demo-item"
                        data-email="admin@simmas.sch.id"
                        data-password="password123"
                    >
                        <span class="login-demo-item__email">admin@simmas.sch.id</span>
                        <span class="login-demo-item__role login-demo-item__role--admin">ADMIN</span>
                    </button>

                    <button
                        type="button"
                        class="login-demo-item"
                        data-email="guru@simmas.sch.id"
                        data-password="password123"
                    >
                        <span class="login-demo-item__email">guru@simmas.sch.id</span>
                        <span class="login-demo-item__role login-demo-item__role--guru">GURU</span>
                    </button>

                    <button
                        type="button"
                        class="login-demo-item"
                        data-email="siswa@simmas.sch.id"
                        data-password="password123"
                    >
                        <span class="login-demo-item__email">siswa@simmas.sch.id</span>
                        <span class="login-demo-item__role login-demo-item__role--siswa">SISWA</span>
                    </button>

                </div>

            </div>


            {{-- =================================================
                 KEMBALI KE LANDING PAGE
            ================================================== --}}

            <div class="login-back">

                <a href="{{ route('landing') }}">

                    <i class="bi bi-arrow-left"></i>

                    Kembali ke Beranda

                </a>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     LOADING OVERLAY
========================================================= --}}

<div
    class="login-loading-overlay"
    id="loginLoadingOverlay"
>

    <span class="login-loading-overlay__spinner"></span>

    <p class="login-loading-overlay__text">
        Memverifikasi akun, mengarahkan ke dashboard...
    </p>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ELEMEN-ELEMEN UTAMA
       Dideklarasikan di paling atas supaya bisa dipakai
       bareng oleh bagian toggle password, akun demo, dan
       loading overlay di bawah.
    ====================================================== */

    const passwordInput = document.getElementById('password');
    const emailInput = document.getElementById('email');
    const loginForm = document.getElementById('loginForm');
    const loadingOverlay = document.getElementById('loginLoadingOverlay');


    /* =====================================================
       TOGGLE PASSWORD
    ====================================================== */

    const toggleBtn = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (toggleBtn && passwordInput && toggleIcon) {

        toggleBtn.addEventListener('click', function () {

            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';

            toggleIcon.classList.toggle(
                'bi-eye',
                !isHidden
            );

            toggleIcon.classList.toggle(
                'bi-eye-slash',
                isHidden
            );

        });

    }


    /* =====================================================
       LOADING SAAT LOGIN (SUBMIT FORM MANUAL)
       Didefinisikan sebelum bagian Akun Demo karena
       listener submit di sini juga dipakai saat akun demo
       men-trigger submit form secara otomatis.
    ====================================================== */

    if (loginForm && loadingOverlay) {

        loginForm.addEventListener('submit', function () {

            loadingOverlay.classList.add('is-active');

        });

    }


    /* =====================================================
       AKUN DEMO - AUTO FILL + AUTO SUBMIT (LANGSUNG REDIRECT)
       Saat salah satu akun demo diklik:
       1. Email & password otomatis diisi sesuai data akun demo
       2. Semua tombol akun demo dikunci sementara (anti double click)
       3. Form login langsung di-submit otomatis
          -> browser mengirim request POST ke route('login.submit')
          -> controller login yang menentukan redirect ke dashboard
             sesuai role (admin/guru/siswa) dari akun tsb
    ====================================================== */

    const demoItems = document.querySelectorAll('.login-demo-item');

    demoItems.forEach(function (item) {

        item.addEventListener('click', function () {

            const email = item.getAttribute('data-email');
            const password = item.getAttribute('data-password');

            if (emailInput) emailInput.value = email;
            if (passwordInput) passwordInput.value = password;

            if (!loginForm) return;

            // Kunci semua tombol akun demo supaya tidak bisa diklik dobel
            // selagi proses submit & redirect sedang berjalan
            demoItems.forEach(function (el) {
                el.disabled = true;
                el.classList.add('is-loading');
            });

            // Submit form secara otomatis.
            // requestSubmit() dipakai (bukan submit() biasa) supaya event
            // 'submit' di atas tetap ter-trigger -> loading overlay muncul,
            // dan validasi HTML5 (required, dsb) tetap jalan seperti submit manual.
            if (typeof loginForm.requestSubmit === 'function') {
                loginForm.requestSubmit();
            } else {
                // Fallback untuk browser lama yang belum dukung requestSubmit()
                if (loadingOverlay) loadingOverlay.classList.add('is-active');
                loginForm.submit();
            }

        });

    });

});

</script>

@endpush
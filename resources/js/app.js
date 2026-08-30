// ============================================
// 1. BOOTSTRAP JS (CSS-nya sudah dipindah ke app.css)
// ============================================
// JS Bootstrap (dropdown, navbar toggle/hamburger, modal, dll)
// Termasuk Popper.js di dalamnya (untuk dropdown & tooltip positioning)
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Expose ke window supaya bisa dipanggil manual di script halaman (blade),
// misalnya untuk new bootstrap.Modal(...) di halaman jurnal guru
window.bootstrap = bootstrap;

// ============================================
// 2. CUSTOM SCRIPT SIMMAS
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        });
    }
});
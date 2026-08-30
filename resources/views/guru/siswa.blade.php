{{-- resources/views/guru/siswa.blade.php --}}
@extends('layouts.guru')

@section('title', 'Siswa Bimbingan')
@section('page-title', 'Siswa Bimbingan')

@section('styles')
<style>
    .sb-subtitle {
        color: var(--guru-muted);
        font-size: 0.88rem;
    }

    /* ================= STAT CARDS ================= */
    .sb-stat-card {
        background: #fff;
        border: 1px solid var(--guru-border);
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }

    .sb-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .sb-stat-icon.blue   { background: var(--guru-primary-soft); color: var(--guru-primary); }
    .sb-stat-icon.orange { background: #fff4e5; color: #d98324; }
    .sb-stat-icon.green  { background: #e7f8ef; color: #1c9c5b; }

    .sb-stat-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--guru-ink);
        line-height: 1.1;
    }

    .sb-stat-label {
        font-size: 0.78rem;
        color: var(--guru-muted);
        font-weight: 500;
    }

    /* ================= TABLE CARD ================= */
    .sb-table-card {
        background: #fff;
        border: 1px solid var(--guru-border);
        border-radius: 14px;
        overflow: hidden;
    }

    .sb-table thead th {
        background: #fafbfd;
        color: var(--guru-muted);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        border-bottom: 1px solid var(--guru-border);
        padding: 0.9rem 1rem;
        white-space: nowrap;
    }

    .sb-table tbody td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid var(--guru-border);
        vertical-align: middle;
    }

    .sb-table tbody tr:last-child td {
        border-bottom: none;
    }

    .sb-table tbody tr:hover {
        background: #fafbff;
    }

    /* Avatar inisial siswa */
    .sb-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .sb-siswa-name {
        font-weight: 600;
        color: var(--guru-ink);
        font-size: 0.9rem;
    }

    .sb-siswa-nis {
        font-size: 0.76rem;
        color: var(--guru-muted);
    }

    .sb-dudi-name {
        font-weight: 600;
        color: var(--guru-ink);
        font-size: 0.88rem;
    }

    .sb-dudi-alamat {
        font-size: 0.76rem;
        color: var(--guru-muted);
    }

    /* Badge kelas */
    .sb-badge-kelas {
        background: #f1f2f6;
        color: var(--guru-ink);
        font-size: 0.76rem;
        font-weight: 600;
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
    }

    /* Badge status */
    .sb-badge-status {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        display: inline-block;
    }

    .sb-badge-status.magang  { background: #fff4e5; color: #d98324; }
    .sb-badge-status.selesai { background: var(--guru-primary-soft); color: var(--guru-primary); }

    /* Lingkaran nilai akhir */
    .sb-nilai-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.88rem;
        background: #e7f8ef;
        color: #1c9c5b;
    }

    .sb-nilai-kosong {
        color: var(--guru-muted);
        font-size: 0.85rem;
    }

    /* Tombol aksi */
    .sb-btn-nilai {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
    }

    /* Empty state */
    .sb-empty {
        padding: 3.5rem 1rem;
        text-align: center;
    }

    .sb-empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--guru-primary-soft);
        color: var(--guru-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 1rem;
    }

    .sb-empty-title {
        font-weight: 700;
        color: var(--guru-ink);
        margin-bottom: 0.25rem;
    }

    .sb-empty-desc {
        color: var(--guru-muted);
        font-size: 0.86rem;
        max-width: 340px;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')

<p class="sb-subtitle mb-3">
    Daftar lengkap seluruh siswa binaan di bawah tanggung jawab Anda, disertai fitur
    penginputan dan revisi nilai akhir magang.
</p>

{{-- ============================================================ --}}
{{-- STAT RINGKASAN --}}
{{-- ============================================================ --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="sb-stat-card">
            <div class="sb-stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="sb-stat-value">{{ $siswaBimbingan->count() }}</div>
                <div class="sb-stat-label">Total Siswa Bimbingan</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4">
        <div class="sb-stat-card">
            <div class="sb-stat-icon orange">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="sb-stat-value">
                    {{ $siswaBimbingan->whereNull('nilai_akhir')->count() }}
                </div>
                <div class="sb-stat-label">Belum Dinilai</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4">
        <div class="sb-stat-card">
            <div class="sb-stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="sb-stat-value">
                    {{ $siswaBimbingan->whereNotNull('nilai_akhir')->count() }}
                </div>
                <div class="sb-stat-label">Sudah Dinilai</div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- TABEL SISWA BIMBINGAN --}}
{{-- ============================================================ --}}
<div class="sb-table-card">
    @if ($siswaBimbingan->isEmpty())
        <div class="sb-empty">
            <div class="sb-empty-icon">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div class="sb-empty-title">Belum ada siswa bimbingan</div>
            <p class="sb-empty-desc mb-0">
                Siswa akan muncul di sini setelah admin menempatkan mereka pada
                bimbingan Anda.
            </p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table sb-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Siswa</th>
                        <th>Kelas</th>
                        <th>Tempat Magang (DUDI)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswaBimbingan as $item)
                        @php
                            $nama = $item->siswa->profile->nama ?? '-';
                            $inisial = collect(explode(' ', trim($nama)))
                                ->map(fn ($k) => mb_substr($k, 0, 1))
                                ->take(2)
                                ->implode('');

                            // Sesuaikan nama kolom/enum status_magang dengan yang ada di database
                            $statusMagang = $item->status_magang ?? 'sedang_magang';
                            $statusLabel = match ($statusMagang) {
                                'sedang_magang' => 'Sedang Magang',
                                'selesai' => 'Selesai Magang',
                                default => 'Sedang Magang',
                            };
                            $statusClass = $statusMagang === 'selesai' ? 'selesai' : 'magang';
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="sb-avatar">{{ strtoupper($inisial) ?: '?' }}</div>
                                    <div>
                                        <div class="sb-siswa-name">{{ $nama }}</div>
                                        <div class="sb-siswa-nis">{{ $item->siswa->nis ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="sb-badge-kelas">{{ $item->siswa->kelas ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="sb-dudi-name">{{ $item->tempatMagang->nama_perusahaan ?? '-' }}</div>
                                <div class="sb-dudi-alamat">{{ $item->tempatMagang->alamat ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="sb-badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-center">
                                @if (!is_null($item->nilai_akhir))
                                    <span class="sb-nilai-circle">{{ $item->nilai_akhir }}</span>
                                @else
                                    <span class="sb-nilai-kosong">-</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <button type="button"
                                        class="btn sb-btn-nilai {{ is_null($item->nilai_akhir) ? 'btn-primary' : 'btn-outline-secondary' }} btn-beri-nilai"
                                        data-siswa-id="{{ $item->siswa->id }}"
                                        data-siswa-nama="{{ $nama }}"
                                        data-tempat-magang="{{ $item->tempatMagang->nama_perusahaan ?? '-' }}"
                                        data-nilai-sekarang="{{ $item->nilai_akhir ?? '' }}"
                                        {{-- Persentase kehadiran & total jurnal disetujui, sesuaikan dengan accessor yang ada --}}
                                        data-kehadiran="{{ $item->persentase_kehadiran ?? 0 }}"
                                        data-total-jurnal="{{ $item->total_jurnal_disetujui ?? 0 }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalPenilaian">
                                    @if (is_null($item->nilai_akhir))
                                        <i class="bi bi-pencil-square me-1"></i> Beri Nilai
                                    @else
                                        <i class="bi bi-arrow-repeat me-1"></i> Revisi
                                    @endif
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ============================================================ --}}
{{-- MODAL: FORM PENILAIAN AKHIR MAGANG --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalPenilaian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold mb-0">
                        <i class="bi bi-clipboard-check text-primary me-1"></i>
                        Penilaian Akhir Magang
                    </h5>
                    <p class="text-muted small mb-0" id="penilaianSubtitle">
                        Input nilai akhir keseluruhan untuk siswa
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bg-light rounded-3 p-3 mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Tempat Magang</span>
                        <span class="fw-semibold" id="infoTempatMagang">-</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Total Jurnal Disetujui</span>
                        <span class="fw-semibold" id="infoJurnal">-</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Kehadiran</span>
                        <span class="fw-semibold" id="infoKehadiran">-</span>
                    </div>
                </div>

                <form id="formPenilaian">
                    @csrf
                    <div class="mb-2">
                        <label for="inputNilaiAkhir" class="form-label fw-semibold">
                            Nilai Akhir <span class="text-muted fw-normal">(Skala 0 - 100)</span>
                        </label>
                        <input type="number"
                               class="form-control form-control-lg text-center"
                               id="inputNilaiAkhir"
                               name="nilai_akhir"
                               min="0" max="100"
                               required>
                        <div class="invalid-feedback" id="errorNilaiAkhir"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success px-4" id="btnSimpanNilai">
                    <span class="btn-text">Simpan Nilai</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalPenilaian   = document.getElementById('modalPenilaian');
    const formPenilaian    = document.getElementById('formPenilaian');
    const inputNilaiAkhir  = document.getElementById('inputNilaiAkhir');
    const errorNilaiAkhir  = document.getElementById('errorNilaiAkhir');
    const btnSimpanNilai   = document.getElementById('btnSimpanNilai');

    let currentSiswaId = null;

    document.querySelectorAll('.btn-beri-nilai').forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentSiswaId = this.dataset.siswaId;

            document.getElementById('penilaianSubtitle').textContent =
                'Input nilai akhir keseluruhan untuk ' + this.dataset.siswaNama;
            document.getElementById('infoTempatMagang').textContent = this.dataset.tempatMagang;
            document.getElementById('infoJurnal').textContent = this.dataset.totalJurnal + ' Disetujui';
            document.getElementById('infoKehadiran').textContent = this.dataset.kehadiran + '%';

            inputNilaiAkhir.value = this.dataset.nilaiSekarang || '';
            inputNilaiAkhir.classList.remove('is-invalid');
            errorNilaiAkhir.textContent = '';
        });
    });

    modalPenilaian.addEventListener('hidden.bs.modal', function () {
        formPenilaian.reset();
        inputNilaiAkhir.classList.remove('is-invalid');
        currentSiswaId = null;
    });

    btnSimpanNilai.addEventListener('click', function () {
        if (!currentSiswaId) return;

        const nilai = inputNilaiAkhir.value;

        if (nilai === '' || nilai < 0 || nilai > 100) {
            inputNilaiAkhir.classList.add('is-invalid');
            errorNilaiAkhir.textContent = 'Nilai harus berupa angka antara 0 sampai 100.';
            return;
        }

        toggleLoading(true);

        fetch(`{{ url('guru/siswa') }}/${currentSiswaId}/nilai`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ nilai_akhir: nilai }),
        })
        .then(async (response) => {
            const data = await response.json();
            if (!response.ok) throw { status: response.status, data };
            return data;
        })
        .then((data) => {
            toggleLoading(false);
            bootstrap.Modal.getInstance(modalPenilaian).hide();
            showToast(data.message || 'Nilai akhir berhasil disimpan.', 'success');
            setTimeout(() => window.location.reload(), 800);
        })
        .catch((err) => {
            toggleLoading(false);
            if (err.status === 422 && err.data.errors) {
                inputNilaiAkhir.classList.add('is-invalid');
                errorNilaiAkhir.textContent = err.data.errors.nilai_akhir?.[0]
                    ?? 'Nilai yang dimasukkan tidak valid.';
            } else {
                showToast('Terjadi kesalahan saat menyimpan nilai.', 'danger');
            }
        });
    });

    function toggleLoading(isLoading) {
        btnSimpanNilai.disabled = isLoading;
        btnSimpanNilai.querySelector('.btn-text').classList.toggle('d-none', isLoading);
        btnSimpanNilai.querySelector('.spinner-border').classList.toggle('d-none', !isLoading);
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
@extends('layouts.app')
@section('title','Cara Penilaian')
@section('page-title','Cara Penilaian')

@section('content')
@php
    $currentMethod = old('laporan_scoring_method', $setting->laporan_scoring_method ?? 'weighted_kinerja_kegiatan');
    if ($currentMethod === 'average_kinerja_kegiatan') {
        $currentMethod = 'weighted_kinerja_kegiatan';
    }

    $bobotKinerja = old('laporan_bobot_kinerja', $setting->laporan_bobot_kinerja ?? 70);
    $bobotKegiatan = old('laporan_bobot_kegiatan', $setting->laporan_bobot_kegiatan ?? 30);
@endphp

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-9">
        <div class="card">
            <div class="card-header py-2 fw-semibold">
                <i class="bi bi-calculator me-2"></i>Pengaturan Cara Penilaian
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.penilaian-metode.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Nilai Akhir</label>
                        <select name="laporan_scoring_method" id="metodePenilaian" class="form-select @error('laporan_scoring_method') is-invalid @enderror">
                            <option value="weighted_kinerja_kegiatan" {{ $currentMethod === 'weighted_kinerja_kegiatan' ? 'selected' : '' }}>
                                Bobot Kinerja + Kegiatan (Rekomendasi)
                            </option>
                            <option value="weighted_kategori" {{ $currentMethod === 'weighted_kategori' ? 'selected' : '' }}>
                                Rata-rata per Kategori
                            </option>
                        </select>
                        @error('laporan_scoring_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jika memilih Bobot Kinerja + Kegiatan, nilai akhir dihitung dari persentase dua komponen utama.</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bobot Kinerja (%)</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="laporan_bobot_kinerja"
                                id="bobotKinerja"
                                class="form-control @error('laporan_bobot_kinerja') is-invalid @enderror"
                                value="{{ $bobotKinerja }}">
                            @error('laporan_bobot_kinerja')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bobot Kegiatan (%)</label>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="laporan_bobot_kegiatan"
                                id="bobotKegiatan"
                                class="form-control @error('laporan_bobot_kegiatan') is-invalid @enderror"
                                value="{{ $bobotKegiatan }}">
                            @error('laporan_bobot_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info mb-3" id="formulaPreview">
                        Total Nilai = ({{ number_format((float) $bobotKinerja, 2, '.', '') }}% Total Kinerja) + ({{ number_format((float) $bobotKegiatan, 2, '.', '') }}% Total Kegiatan)
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fw-semibold">Total Bobot:</span>
                        <span class="badge bg-secondary" id="totalBobotBadge">0%</span>
                        <small class="text-muted" id="totalBobotHint">Total ideal adalah 100%.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Cara Penilaian
                        </button>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const metode = document.getElementById('metodePenilaian');
        const bobotKinerja = document.getElementById('bobotKinerja');
        const bobotKegiatan = document.getElementById('bobotKegiatan');
        const formulaPreview = document.getElementById('formulaPreview');
        const totalBobotBadge = document.getElementById('totalBobotBadge');
        const totalBobotHint = document.getElementById('totalBobotHint');

        const toNumber = (value) => {
            const parsed = parseFloat(value);
            return Number.isFinite(parsed) ? parsed : 0;
        };

        const updatePreview = () => {
            const nilaiKinerja = toNumber(bobotKinerja.value);
            const nilaiKegiatan = toNumber(bobotKegiatan.value);
            const total = nilaiKinerja + nilaiKegiatan;
            const metodeByJenis = metode.value === 'weighted_kinerja_kegiatan';

            bobotKinerja.readOnly = !metodeByJenis;
            bobotKegiatan.readOnly = !metodeByJenis;

            if (metodeByJenis) {
                formulaPreview.className = 'alert alert-info mb-3';
                formulaPreview.textContent = `Total Nilai = (${nilaiKinerja.toFixed(2)}% Total Kinerja) + (${nilaiKegiatan.toFixed(2)}% Total Kegiatan)`;
            } else {
                formulaPreview.className = 'alert alert-secondary mb-3';
                formulaPreview.textContent = 'Mode ini memakai rata-rata per kategori bernilai. Bobot Kinerja/Kegiatan tidak digunakan.';
            }

            totalBobotBadge.textContent = `${total.toFixed(2)}%`;
            if (!metodeByJenis) {
                totalBobotBadge.className = 'badge bg-secondary';
                totalBobotHint.textContent = 'Pada mode ini, total bobot tidak dipakai.';
                return;
            }

            if (Math.abs(total - 100) <= 0.01) {
                totalBobotBadge.className = 'badge bg-success';
                totalBobotHint.textContent = 'Total bobot sudah tepat 100%.';
            } else {
                totalBobotBadge.className = 'badge bg-danger';
                totalBobotHint.textContent = 'Total bobot harus 100% agar dapat disimpan.';
            }
        };

        [metode, bobotKinerja, bobotKegiatan].forEach((el) => {
            el.addEventListener('input', updatePreview);
            el.addEventListener('change', updatePreview);
        });

        updatePreview();
    });
</script>
@endpush
@endsection

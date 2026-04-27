@extends('layouts.app')
@section('title','Input Penilaian')
@section('page-title','Input Nilai Pengabdian')
@section('content')

@php
    $routePrefix = $routePrefix ?? 'admin';
    $isLocked = $isLocked ?? false;
    $canEditLockedScores = $canEditLockedScores ?? false;
    $lockedKompetensiIds = collect($lockedKompetensiIds ?? [])->map(fn($id) => (int) $id)->all();
    $hasPendingUnlockRequest = $hasPendingUnlockRequest ?? false;
    $hasEditableKompetensi = false;
@endphp

{{-- Step 1: Pilih Karyawan & Tahun --}}
<div class="card mb-4">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-person-check me-2"></i>Pilih Karyawan & Tahun Ajaran</div>
    <div class="card-body">
        <form method="GET" action="{{ route($routePrefix . '.transaksi.create') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold">Karyawan <span class="text-danger">*</span></label>
                <select name="karyawan_id" class="form-select" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($karyawanList as $k)
                    <option value="{{ $k->id }}" {{ (isset($selectedKaryawan) && $selectedKaryawan?->id == $k->id) ? 'selected' : '' }}>
                        {{ $k->kode_karyawan }} — {{ $k->nama_karyawan }}
                        {{ $k->pangkalan ? '('.$k->pangkalan->nama_pangkalan.')' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                <select name="tahun_penilaian_id" class="form-select" required>
                    <option value="">-- Pilih Tahun --</option>
                    @foreach($tahunList as $t)
                    <option value="{{ $t->id }}" {{ (isset($selectedTahun) && $selectedTahun?->id == $t->id) ? 'selected' : ($tahunAktif?->id == $t->id && !isset($selectedTahun) ? 'selected' : '') }}>
                        {{ $t->periode_penilaian }} {{ $t->is_active ? '(Aktif)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Buka Scoresheet
                </button>
            </div>
        </form>
    </div>
</div>

@if($selectedKaryawan && $selectedTahun)
{{-- Step 2: Scoresheet --}}
<div class="card">
    <div class="card-header py-2">
        <span class="fw-semibold"><i class="bi bi-clipboard-data me-2"></i>Blanko Penilaian</span>
        <span class="ms-2 text-muted">—</span>
        <span class="ms-2 fw-bold text-primary">{{ $selectedKaryawan->nama_karyawan }}</span>
        <span class="ms-1 badge bg-secondary">{{ $selectedKaryawan->kode_karyawan }}</span>
        @if($selectedKaryawan->pangkalan)
            <span class="ms-2 text-muted" style="font-size:.82rem;">{{ $selectedKaryawan->pangkalan->nama_pangkalan }}</span>
        @endif
        <span class="float-end text-muted" style="font-size:.82rem;">Tahun: {{ $selectedTahun->periode_penilaian }}</span>
    </div>
    <div class="card-body pb-2">
        @if($selectedKaryawan->pangkalan_id)
        <div class="alert alert-info py-2">
            <i class="bi bi-info-circle me-1"></i> Karyawan dengan pangkalan dinilai menggunakan kategori <strong>kinerja sesuai mapping pangkalan</strong> dan <strong>kategori kegiatan wajib</strong>.
        </div>
        @endif

        <div class="alert alert-light border py-2">
            <i class="bi bi-asterisk text-danger me-1"></i> Nilai kosong berarti <strong>tidak dinilai</strong>. Nilai <strong>0 - 100</strong> adalah nilai valid.
        </div>

        @if(!$isLocked)
        <div class="alert alert-secondary py-2">
            <i class="bi bi-lock me-1"></i> Indikator yang sudah memiliki nilai akan <strong>terkunci otomatis</strong>. Admin dapat melakukan unlock untuk revisi.
        </div>
        @endif

        @if($isLocked)
        <div class="alert alert-warning py-2">
            <i class="bi bi-lock me-1"></i> Nilai untuk karyawan dan tahun ini sedang <strong>LOCKED</strong>. Silakan ajukan unlock terlebih dahulu.

            @if($routePrefix === 'kepala')
                <hr class="my-2">
                @if($hasPendingUnlockRequest)
                    <div class="small mb-0">
                        <i class="bi bi-hourglass-split me-1"></i> Pengajuan unlock sudah dikirim dan sedang menunggu persetujuan admin.
                    </div>
                @else
                    <form method="POST" action="{{ route('kepala.transaksi.request-unlock') }}" class="mt-2">
                        @csrf
                        <input type="hidden" name="karyawan_id" value="{{ $selectedKaryawan->id }}">
                        <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun->id }}">
                        <label class="form-label fw-semibold small mb-1">Alasan pengajuan unlock</label>
                        <textarea name="alasan" class="form-control form-control-sm mb-2" rows="2" maxlength="500" placeholder="Tulis alasan unlock..." required></textarea>
                        <button type="submit" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-unlock me-1"></i>Ajukan Unlock ke Admin
                        </button>
                    </form>
                @endif
            @endif
        </div>
        @endif

        <form method="POST" action="{{ route($routePrefix . '.transaksi.store') }}">
            @csrf
            <input type="hidden" name="karyawan_id" value="{{ $selectedKaryawan->id }}">
            <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun->id }}">

            @foreach($kategoriList as $kategori)
            <div class="mb-4">
                <div class="d-flex align-items-center mb-2 gap-2">
                    <span class="badge bg-dark px-3 py-2">{{ $kategori->kode_kategori }}</span>
                    <h6 class="mb-0 fw-bold">{{ $kategori->kategori }}</h6>
                    <span class="badge {{ $kategori->jenis === 'kegiatan' ? 'bg-warning text-dark' : 'bg-primary' }} ms-1">{{ ucfirst($kategori->jenis) }}</span>
                    @if($kategori->is_wajib)
                        <span class="badge bg-danger">Wajib</span>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60">Kode</th>
                                <th>Indikator Kompetensi</th>
                                <th width="160" class="text-center">Nilai (0 – 100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori->kompetensi as $komp)
                            @php
                                $existing = $existingNilai[$komp->id] ?? null;
                                $isKompetensiLocked = (!$canEditLockedScores && in_array((int) $komp->id, $lockedKompetensiIds, true));
                                $disableInput = $isLocked || $isKompetensiLocked;
                                if (!$disableInput) {
                                    $hasEditableKompetensi = true;
                                }
                            @endphp
                            <tr>
                                <td><span class="badge bg-secondary">{{ $komp->kode_kompetensi }}</span></td>
                                <td>
                                    {{ $komp->kompetensi }}
                                    @if($isKompetensiLocked)
                                        <span class="badge bg-secondary ms-1">Locked</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <input type="number"
                                           name="nilai[{{ $komp->id }}]"
                                           class="form-control form-control-sm text-center mx-auto"
                                           style="max-width:90px;"
                                           min="0" max="100" step="1"
                                           value="{{ $existing !== null ? $existing : '' }}"
                                                                                 placeholder="Kosongkan / 0-100"
                                         {{ $disableInput ? 'disabled' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

            @if($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal" {{ ($isLocked || !$hasEditableKompetensi) ? 'disabled' : '' }}>
                    <i class="bi bi-save me-1"></i>Simpan Penilaian
                </button>
                <a href="{{ route($routePrefix . '.transaksi.index', ['tahun_penilaian_id' => $selectedTahun->id]) }}"
                   class="btn btn-secondary">Kembali</a>
            </div>

            <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmSubmitModalLabel">Konfirmasi Simpan Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Pastikan nilai yang diinput sudah benar. Lanjutkan simpan penilaian?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-1"></i>Ya, Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@extends('layouts.app')
@section('title','Input Penilaian')
@section('page-title','Input Nilai Pengabdian')
@section('content')

{{-- Step 1: Pilih Karyawan & Tahun --}}
<div class="card mb-4">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-person-check me-2"></i>Pilih Karyawan & Tahun Ajaran</div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.transaksi.create') }}" class="row g-3 align-items-end">
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
        <form method="POST" action="{{ route('admin.transaksi.store') }}">
            @csrf
            <input type="hidden" name="karyawan_id" value="{{ $selectedKaryawan->id }}">
            <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun->id }}">

            @foreach($kategoriList as $kategori)
            <div class="mb-4">
                <div class="d-flex align-items-center mb-2 gap-2">
                    <span class="badge bg-dark px-3 py-2">{{ $kategori->kode_kategori }}</span>
                    <h6 class="mb-0 fw-bold">{{ $kategori->kategori }}</h6>
                    <span class="badge bg-info text-dark ms-1">Bobot {{ $kategori->bobot }}%</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60">Kode</th>
                                <th>Indikator Kompetensi</th>
                                <th width="130" class="text-center">Nilai (0 – 100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategori->kompetensi as $komp)
                            @php $existing = $existingNilai[$komp->id] ?? null; @endphp
                            <tr>
                                <td><span class="badge bg-secondary">{{ $komp->kode_kompetensi }}</span></td>
                                <td>{{ $komp->kompetensi }}</td>
                                <td class="text-center">
                                    <input type="number"
                                           name="nilai[{{ $komp->id }}]"
                                           class="form-control form-control-sm text-center mx-auto"
                                           style="max-width:90px;"
                                           min="0" max="100" step="1"
                                           value="{{ $existing !== null ? $existing : '' }}"
                                           placeholder="–">
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
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Simpan Penilaian
                </button>
                <a href="{{ route('admin.transaksi.index', ['tahun_penilaian_id' => $selectedTahun->id]) }}"
                   class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

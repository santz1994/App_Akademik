@extends('layouts.app')
@section('title','Edit Transaksi')
@section('page-title','Transaksi › Edit')
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil me-2"></i>Form Edit Transaksi Penilaian</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.transaksi.update', $transaksi) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Transaksi</label>
                <input type="text" class="form-control bg-light" value="{{ $transaksi->kode_transaksi }}" readonly>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Karyawan <span class="text-danger">*</span></label>
                    <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror">
                        @foreach($karyawan as $k)
                        <option value="{{ $k->id }}" {{ old('karyawan_id', $transaksi->karyawan_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->kode_karyawan }} — {{ $k->nama_karyawan }}
                        </option>
                        @endforeach
                    </select>
                    @error('karyawan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                    <select name="tahun_penilaian_id" class="form-select @error('tahun_penilaian_id') is-invalid @enderror">
                        @foreach($tahunList as $t)
                        <option value="{{ $t->id }}" {{ old('tahun_penilaian_id', $transaksi->tahun_penilaian_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->periode_penilaian }} {{ $t->is_active ? '(Aktif)' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('tahun_penilaian_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kompetensi <span class="text-danger">*</span></label>
                <select name="kompetensi_id" class="form-select @error('kompetensi_id') is-invalid @enderror">
                    @foreach($kompetensi as $k)
                    <option value="{{ $k->id }}" {{ old('kompetensi_id', $transaksi->kompetensi_id) == $k->id ? 'selected' : '' }}>
                        {{ $k->kode_kompetensi }} — {{ $k->kompetensi }} ({{ $k->kategoriKinerja->kategori ?? '' }})
                    </option>
                    @endforeach
                </select>
                @error('kompetensi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nilai (0–100)</label>
                    <input type="number" name="nilai" class="form-control" step="0.01" min="0" max="100"
                           value="{{ old('nilai', $transaksi->nilai) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Performance Rating</label>
                    <select name="performance_rating_id" class="form-select">
                        <option value="">-- Pilih Rating --</option>
                        @foreach($rating as $r)
                        <option value="{{ $r->id }}" {{ old('performance_rating_id', $transaksi->performance_rating_id) == $r->id ? 'selected' : '' }}>
                            {{ $r->rating }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

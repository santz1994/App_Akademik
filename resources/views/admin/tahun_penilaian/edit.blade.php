@extends('layouts.app')
@section('title','Edit Tahun Penilaian')
@section('page-title','Tahun Penilaian › Edit')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil me-2"></i>Form Edit Tahun Penilaian</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tahun-penilaian.update', $tahunPenilaian) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Periode Penilaian <span class="text-danger">*</span></label>
                <input type="text" name="periode_penilaian" class="form-control @error('periode_penilaian') is-invalid @enderror"
                       value="{{ old('periode_penilaian', $tahunPenilaian->periode_penilaian) }}">
                @error('periode_penilaian')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $tahunPenilaian->keterangan) }}</textarea>
            </div>
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                           {{ old('is_active', $tahunPenilaian->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="isActive">Tahun Penilaian Aktif</label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.tahun-penilaian.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

@extends('layouts.app')
@section('title','Edit Kategori Kinerja')
@section('page-title','Kategori Kinerja › Edit')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil me-2"></i>Form Edit Kategori Kinerja</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kategori-kinerja.update', $kategoriKinerja) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Kategori</label>
                <input type="text" class="form-control bg-light" value="{{ $kategoriKinerja->kode_kategori }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                       value="{{ old('kategori', $kategoriKinerja->kategori) }}">
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Weighted / Bobot (%)</label>
                <div class="input-group">
                    <input type="number" name="bobot" step="0.01" min="0" max="100"
                           class="form-control @error('bobot') is-invalid @enderror"
                           value="{{ old('bobot', $kategoriKinerja->bobot) }}">
                    <span class="input-group-text">%</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.kategori-kinerja.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

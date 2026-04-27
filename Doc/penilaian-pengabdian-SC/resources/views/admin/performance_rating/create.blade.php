@extends('layouts.app')
@section('title','Tambah Performance Rating')
@section('page-title','Performance Rating › Add Data')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-plus-circle me-2"></i>Form Tambah Performance Rating</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.performance-rating.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Rating</label>
                <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Rating <span class="text-danger">*</span></label>
                <input type="text" name="rating" class="form-control @error('rating') is-invalid @enderror"
                       value="{{ old('rating') }}" placeholder="Contoh: Sangat Baik">
                @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi rating">{{ old('keterangan') }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.performance-rating.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

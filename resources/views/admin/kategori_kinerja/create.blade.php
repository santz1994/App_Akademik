@extends('layouts.app')
@section('title','Tambah Kategori')
@section('page-title','Kategori › Add Data')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-plus-circle me-2"></i>Form Tambah Kategori</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kategori-kinerja.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Kategori</label>
                <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                       value="{{ old('kategori') }}" placeholder="Nama kategori">
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jenis Kategori <span class="text-danger">*</span></label>
                <select name="jenis" id="jenisKategori" class="form-select @error('jenis') is-invalid @enderror">
                    <option value="kinerja" {{ old('jenis') === 'kinerja' ? 'selected' : '' }}>Kinerja</option>
                    <option value="kegiatan" {{ old('jenis') === 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                </select>
                @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <input type="hidden" name="bobot" value="{{ old('bobot', 0) }}">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.kategori-kinerja.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

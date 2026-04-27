@extends('layouts.app')
@section('title','Tambah Kompetensi')
@section('page-title','Kompetensi › Add Data')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-plus-circle me-2"></i>Form Tambah Kompetensi</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kompetensi.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Kompetensi</label>
                <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kriteria Kompetensi <span class="text-danger">*</span></label>
                <select name="kategori_kinerja_id" class="form-select @error('kategori_kinerja_id') is-invalid @enderror">
                    <option value="">-- Pilih dari Data Kategori Kinerja --</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_kinerja_id')==$k->id ? 'selected' : '' }}>
                        {{ $k->kode_kategori }} — {{ $k->kategori }}
                    </option>
                    @endforeach
                </select>
                @error('kategori_kinerja_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Kompetensi <span class="text-danger">*</span></label>
                <input type="text" name="kompetensi" class="form-control @error('kompetensi') is-invalid @enderror"
                       value="{{ old('kompetensi') }}" placeholder="Deskripsi kompetensi">
                @error('kompetensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.kompetensi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

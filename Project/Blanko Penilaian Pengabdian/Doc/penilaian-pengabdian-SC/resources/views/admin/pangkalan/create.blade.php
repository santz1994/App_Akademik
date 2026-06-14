@extends('layouts.app')
@section('title','Tambah Pangkalan')
@section('page-title','Tambah Pangkalan Job')
@section('content')

<div class="card mx-auto" style="max-width:600px">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-building-add me-2"></i>Form Tambah Pangkalan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pangkalan.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Pangkalan</label>
                <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
                <div class="form-text">Kode digenerate otomatis.</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Pangkalan / Job <span class="text-danger">*</span></label>
                <input type="text" name="nama_pangkalan" class="form-control @error('nama_pangkalan') is-invalid @enderror"
                       value="{{ old('nama_pangkalan') }}" placeholder="mis. MA AL-HUDA AL-ILAHIYAH" autofocus>
                @error('nama_pangkalan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pimpinan Pos</label>
                <input type="text" name="pimpinan_pos" class="form-control @error('pimpinan_pos') is-invalid @enderror"
                       value="{{ old('pimpinan_pos') }}" placeholder="mis. FATHUL MU'IN, S.Pd.">
                @error('pimpinan_pos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                          placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.pangkalan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

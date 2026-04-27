@extends('layouts.app')
@section('title','Edit Pangkalan')
@section('page-title','Edit Pangkalan Job')
@section('content')

<div class="card mx-auto" style="max-width:600px">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Form Edit Pangkalan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pangkalan.update', $pangkalan) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Pangkalan</label>
                <input type="text" class="form-control bg-light" value="{{ $pangkalan->kode_pangkalan }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Pangkalan / Job <span class="text-danger">*</span></label>
                <input type="text" name="nama_pangkalan" class="form-control @error('nama_pangkalan') is-invalid @enderror"
                       value="{{ old('nama_pangkalan', $pangkalan->nama_pangkalan) }}" autofocus>
                @error('nama_pangkalan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pimpinan Pos</label>
                <input type="text" name="pimpinan_pos" class="form-control @error('pimpinan_pos') is-invalid @enderror"
                       value="{{ old('pimpinan_pos', $pangkalan->pimpinan_pos) }}">
                @error('pimpinan_pos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pangkalan->keterangan) }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.pangkalan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

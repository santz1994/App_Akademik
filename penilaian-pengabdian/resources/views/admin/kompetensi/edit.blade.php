@extends('layouts.app')
@section('title','Edit Kompetensi')
@section('page-title','Kompetensi › Edit')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil me-2"></i>Form Edit Kompetensi</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kompetensi.update', $kompetensi) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Kompetensi</label>
                <input type="text" class="form-control bg-light" value="{{ $kompetensi->kode_kompetensi }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori Kompetensi <span class="text-danger">*</span></label>
                @php
                    $selectedKategori = collect(old('kategori_kinerja_ids', $kompetensi->kategoriKinerja->pluck('id')->all()))
                        ->map(fn($v) => (string) $v)
                        ->all();
                @endphp
                <select name="kategori_kinerja_ids[]" class="form-select @error('kategori_kinerja_ids') is-invalid @enderror @error('kategori_kinerja_ids.*') is-invalid @enderror" multiple size="6">
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ in_array((string)$k->id, $selectedKategori, true) ? 'selected' : '' }}>
                        {{ $k->kode_kategori }} — {{ $k->kategori }} ({{ ucfirst($k->jenis) }})
                    </option>
                    @endforeach
                </select>
                @error('kategori_kinerja_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('kategori_kinerja_ids.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Gunakan Ctrl/Cmd untuk memilih lebih dari satu kategori.</small>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Kompetensi <span class="text-danger">*</span></label>
                <input type="text" name="kompetensi" class="form-control @error('kompetensi') is-invalid @enderror"
                       value="{{ old('kompetensi', $kompetensi->kompetensi) }}">
                @error('kompetensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.kompetensi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

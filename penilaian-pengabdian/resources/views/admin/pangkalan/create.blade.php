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
                <label class="form-label fw-semibold">Kepala Pimpinan Pos</label>
                <select name="kepala_user_id" class="form-select @error('kepala_user_id') is-invalid @enderror">
                    <option value="">— Tidak ditetapkan —</option>
                    @foreach($userList as $u)
                    <option value="{{ $u->id }}" {{ old('kepala_user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->username }})
                    </option>
                    @endforeach
                </select>
                @error('kepala_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Pilih user yang akan menjadi kepala pimpinan pos. User akan otomatis berstatus Kepala.</small>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                          placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status Pangkalan <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                    <option value="1" {{ (string)old('is_active', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ (string)old('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Kategori Kinerja Terkait</label>
                <div class="border rounded p-2" style="max-height:210px; overflow:auto;">
                    @forelse($kategoriKinerjaList as $kategori)
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="kategori_kinerja_ids[]"
                                   value="{{ $kategori->id }}"
                                   id="kategori_{{ $kategori->id }}"
                                   {{ in_array($kategori->id, old('kategori_kinerja_ids', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="kategori_{{ $kategori->id }}">
                                <span class="badge bg-secondary me-1">{{ $kategori->kode_kategori }}</span>
                                {{ $kategori->kategori }}
                            </label>
                        </div>
                    @empty
                        <small class="text-muted">Belum ada kategori kinerja.</small>
                    @endforelse
                </div>
                @error('kategori_kinerja_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('kategori_kinerja_ids.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <small class="text-muted d-block mt-1">Kategori kegiatan wajib akan tetap ditambahkan otomatis saat penilaian.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.pangkalan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

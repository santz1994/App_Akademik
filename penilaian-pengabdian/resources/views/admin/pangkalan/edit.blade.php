@extends('layouts.app')
@section('title','Edit Pangkalan')
@section('page-title','Edit Pangkalan Job')
@section('content')

<div class="card mx-auto" style="max-width:600px">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil-square me-2"></i>Form Edit Pangkalan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pangkalan.update', $pangkalan) }}">
            @csrf @method('PUT')
            @php
                $selectedKategoriIds = old('kategori_kinerja_ids', $pangkalan->kategoriKinerja->pluck('id')->all());
            @endphp
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
                <label class="form-label fw-semibold">Kepala Pimpinan Pos</label>
                <select name="kepala_user_id" class="form-select @error('kepala_user_id') is-invalid @enderror">
                    <option value="">— Tidak ditetapkan —</option>
                    @foreach($userList as $u)
                    <option value="{{ $u->id }}" {{ (string)old('kepala_user_id', $pangkalan->kepala_user_id) === (string)$u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->username }})
                    </option>
                    @endforeach
                </select>
                @error('kepala_user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if(!$pangkalan->kepala_user_id && $pangkalan->pimpinan_pos)
                    <small class="text-warning"><i class="bi bi-info-circle me-1"></i>Pimpinan pos lama: <strong>{{ $pangkalan->pimpinan_pos }}</strong>. Silakan pilih user yang sesuai.</small>
                @else
                    <small class="text-muted">Pilih user yang akan menjadi kepala pimpinan pos. User akan otomatis berstatus Kepala.</small>
                @endif
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pangkalan->keterangan) }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status Pangkalan <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                    <option value="1" {{ (string)old('is_active', $pangkalan->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ (string)old('is_active', $pangkalan->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_wajib" value="1" id="isWajib"
                           {{ old('is_wajib', $pangkalan->is_wajib) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="isWajib">Pangkalan Wajib untuk Semua Karyawan</label>
                </div>
                <small class="text-muted">Jika diaktifkan, semua karyawan aktif otomatis terdaftar di pangkalan ini. Pangkalan wajib tidak muncul di pilihan manual karyawan.</small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Kategori Kinerja & Kegiatan Terkait</label>
                <div class="border rounded p-2" style="max-height:400px; overflow:auto;">
                    @php
                        $grouped = $kategoriKinerjaList->groupBy('jenis');
                    @endphp
                    @foreach(['kinerja' => 'Kategori Kinerja', 'kegiatan' => 'Kategori Kegiatan'] as $jenis => $label)
                        @if(isset($grouped[$jenis]) && $grouped[$jenis]->isNotEmpty())
                            <div class="mb-2">
                                <div class="fw-bold text-primary small mb-1" style="border-bottom:1px solid #e2e8f0; padding-bottom:2px;">
                                    <i class="bi bi-{{ $jenis === 'kinerja' ? 'briefcase' : 'clipboard-check' }} me-1"></i>{{ $label }}
                                </div>
                                @foreach($grouped[$jenis] as $kategori)
                                    @php
                                        $isChecked = in_array($kategori->id, $selectedKategoriIds);
                                    @endphp
                                    <div class="form-check ms-2 mb-1">
                                        <input class="form-check-input kategori-check"
                                               type="checkbox"
                                               name="kategori_kinerja_ids[]"
                                               value="{{ $kategori->id }}"
                                               id="kategori_{{ $kategori->id }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label" for="kategori_{{ $kategori->id }}">
                                            {{ $kategori->kategori }}
                                            @if($kategori->is_wajib)
                                                <span class="badge bg-danger ms-1" style="font-size:.65rem;">Wajib</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
                @error('kategori_kinerja_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('kategori_kinerja_ids.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <small class="text-muted d-block mt-1">Pilih kategori kinerja dan kegiatan yang terkait dengan pangkalan ini. Penanggung jawab penilaian mengikuti Kepala Pimpinan Pos yang ditetapkan.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.pangkalan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

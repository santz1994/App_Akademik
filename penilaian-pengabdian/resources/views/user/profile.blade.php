@extends('layouts.app')
@section('title','Profil Saya')
@section('page-title','Profil Saya')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show py-2" role="alert">
    <i class="bi bi-check-circle me-2"></i>{!! session('success') !!}
    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">
        {{-- Info Karyawan --}}
        @if($karyawan)
        <div class="card mb-3">
            <div class="card-header py-2 fw-semibold">
                <i class="bi bi-person-badge me-2"></i>Data Karyawan
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:.8rem;">Kode Karyawan</label>
                        <div class="fw-semibold">{{ $karyawan->kode_karyawan }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:.8rem;">Nama Karyawan</label>
                        <div class="fw-semibold">{{ $karyawan->nama_karyawan }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:.8rem;">Pangkalan Job</label>
                        <div>{{ $karyawan->pangkalan ? $karyawan->pangkalan->kode_pangkalan . ' — ' . $karyawan->pangkalan->nama_pangkalan : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted" style="font-size:.8rem;">Status</label>
                        <div>
                            <span class="badge {{ $karyawan->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Edit Profil --}}
        <div class="card">
            <div class="card-header py-2 fw-semibold">
                <i class="bi bi-pencil-square me-2"></i>Edit Profil
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.profile.update') }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp', $karyawan->no_hp ?? '') }}" placeholder="08xxxxxxxxxx">
                            @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kontak Darurat</label>
                            <input type="text" name="kontak_darurat" class="form-control @error('kontak_darurat') is-invalid @enderror"
                                   value="{{ old('kontak_darurat', $karyawan->kontak_darurat ?? '') }}" placeholder="Nama - No. HP">
                            @error('kontak_darurat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-semibold mb-3"><i class="bi bi-lock me-2"></i>Ubah Password</h6>
                    <small class="text-muted d-block mb-3">Kosongkan jika tidak ingin mengubah password.</small>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 6 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

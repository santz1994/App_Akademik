@extends('layouts.app')
@section('title','Tambah User')
@section('page-title','User Account › Add Data')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-person-plus me-2"></i>Form Tambah User</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">ID User</label>
                <input type="text" class="form-control bg-light" value="Auto Generate" readonly>
                <small class="text-muted">ID otomatis digenerate oleh sistem</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama User <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Nama lengkap">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}" placeholder="username">
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@domain.com">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 6 karakter">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    <option value="">-- Pilih Level --</option>
                    <option value="admin"  {{ old('role')=='admin'  ? 'selected' : '' }}>Admin</option>
                    <option value="user"   {{ old('role')=='user'   ? 'selected' : '' }}>User</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Pangkalan Job</label>
                <select name="pangkalan_id" class="form-select @error('pangkalan_id') is-invalid @enderror">
                    <option value="">— Tidak ditetapkan —</option>
                    @foreach($pangkalanList as $p)
                    <option value="{{ $p->id }}" {{ old('pangkalan_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->kode_pangkalan }} — {{ $p->nama_pangkalan }}
                    </option>
                    @endforeach
                </select>
                @error('pangkalan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Pangkalan tempat user ini bekerja.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

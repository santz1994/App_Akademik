@extends('layouts.app')
@section('title','Edit User')
@section('page-title','User Account › Edit')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil me-2"></i>Form Edit User</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">ID User</label>
                <input type="text" class="form-control bg-light" value="USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama User <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username', $user->username) }}">
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col">
                    <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 6 karakter">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    <option value="admin" {{ old('role',$user->role)=='admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user"  {{ old('role',$user->role)=='user'  ? 'selected' : '' }}>User</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

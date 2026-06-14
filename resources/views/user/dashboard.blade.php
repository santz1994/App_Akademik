@extends('layouts.app')
@section('title','Dashboard User')
@section('page-title','Dashboard')
@section('content')

<div class="card mb-4">
    <div class="card-body py-3">
        <h6 class="fw-bold mb-1"><i class="bi bi-hand-wave text-warning me-2"></i>Selamat datang, {{ $user->name }}!</h6>
        <p class="mb-0 text-muted" style="font-size:.875rem;">Anda login sebagai <span class="badge badge-user">User</span>.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center p-3">
            @php
                $dashFotoUrl = null;
                if ($user->karyawan && !empty($user->karyawan->foto_path)) {
                    $dashFotoPath = ltrim((string) $user->karyawan->foto_path, '/');
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($dashFotoPath)) {
                        $dashFotoUrl = asset('storage/' . $dashFotoPath);
                    }
                }
            @endphp
            @if($dashFotoUrl)
                <img src="{{ $dashFotoUrl }}" alt="Foto" style="width:100px;height:120px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0;margin:0 auto;">
            @else
                <i class="bi bi-person-circle fs-1 text-primary mb-2"></i>
            @endif
            <div class="fw-semibold mt-2">{{ $user->name }}</div>
            <small class="text-muted">{{ $user->username }}</small>
            <small class="d-block text-muted">{{ $user->email }}</small>
            <a href="{{ route('user.profile') }}" class="btn btn-sm btn-outline-primary mt-2">
                <i class="bi bi-pencil me-1"></i>Edit Profil
            </a>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header py-2 fw-semibold"><i class="bi bi-info-circle me-2"></i>Informasi Akun</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted" width="140">Nama</td><td>: <strong>{{ $user->name }}</strong></td></tr>
                    <tr><td class="text-muted">Username</td><td>: {{ $user->username }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>: {{ $user->email }}</td></tr>
                    <tr><td class="text-muted">Role</td><td>: <span class="badge badge-user">User</span></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
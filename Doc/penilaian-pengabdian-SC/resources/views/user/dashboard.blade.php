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
            <i class="bi bi-person-circle fs-1 text-primary mb-2"></i>
            <div class="fw-semibold">{{ $user->name }}</div>
            <small class="text-muted">{{ $user->username }}</small>
            <small class="d-block text-muted">{{ $user->email }}</small>
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
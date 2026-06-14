@extends('layouts.app')
@section('title','List User')
@section('page-title','User Account')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-people me-2"></i>List User</span>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Add Data
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th width="80">ID User</th>
                        <th>Nama User</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Karyawan Terhubung</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $u)
                    <tr>
                        <td>{{ $users->firstItem() + $i }}</td>
                        <td><code class="text-muted" style="font-size:.75rem;">USR-{{ str_pad($u->id, 4, '0', STR_PAD_LEFT) }}</code></td>
                        <td>{{ $u->name }}</td>
                        <td><code>{{ $u->username }}</code></td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge badge-{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
                        <td>
                            @if($u->karyawan)
                                <span class="badge bg-success" style="font-size:.72rem;">
                                    <i class="bi bi-person-check me-1"></i>{{ $u->karyawan->nama_karyawan }}
                                </span>
                                <small class="d-block text-muted" style="font-size:.72rem;">{{ $u->karyawan->kode_karyawan }}</small>
                            @else
                                <span class="text-muted" style="font-size:.8rem;">— Belum terhubung</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-warning btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-action"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer">{{ $users->links() }}</div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title','List User')
@section('page-title','User Account')
@section('content')

<form method="GET" action="{{ route('admin.users.index') }}" class="mb-3 d-flex align-items-center gap-2 flex-wrap">
    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Filter Pangkalan:</label>
    <select name="pangkalan_id" class="form-select form-select-sm" style="max-width:260px;" onchange="this.form.submit()">
        <option value="">-- Semua Pangkalan --</option>
        @foreach($pangkalanList as $p)
        <option value="{{ $p->id }}" {{ (string)$selectedPangkalan === (string)$p->id ? 'selected' : '' }}>
            {{ $p->kode_pangkalan }} — {{ $p->nama_pangkalan }}
        </option>
        @endforeach
    </select>
    @include('components.per-page-select')
    @if($selectedPangkalan || request()->filled('per_page'))
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-people me-2"></i>List User</span>
        <div class="d-flex align-items-center gap-2">
            <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="d-flex gap-1 align-items-center">
                @csrf
                <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" style="max-width:180px;" required>
                <button type="submit" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-upload me-1"></i>Import
                </button>
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Data
            </a>
        </div>
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
                        <th>Jenis Account</th>
                        <th>Pangkalan Job</th>
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
                            @if($u->is_kepala)
                                <span class="badge bg-warning text-dark">Kepala Pimpinan Pos</span>
                            @else
                                <span class="text-muted" style="font-size:.8rem;">User Biasa</span>
                            @endif
                        </td>
                        <td>
                            @if($u->pangkalan)
                                <span class="badge bg-light text-dark border">{{ $u->pangkalan->kode_pangkalan }}</span>
                                <small class="d-block">{{ $u->pangkalan->nama_pangkalan }}</small>
                            @else
                                <span class="text-muted" style="font-size:.8rem;">—</span>
                            @endif
                        </td>
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
                    <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data user.</td></tr>
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

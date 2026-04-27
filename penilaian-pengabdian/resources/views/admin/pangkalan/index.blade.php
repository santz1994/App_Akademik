@extends('layouts.app')
@section('title','Pangkalan Job Pengabdian')
@section('page-title','Pangkalan Job Pengabdian')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show py-2" role="alert">
    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-building me-2"></i>List Pangkalan Job</span>
        <div class="d-flex align-items-center gap-2">
            @include('components.per-page-select', ['standalone' => true])
            <a href="{{ route('admin.pangkalan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Data
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="40">No</th>
                        <th width="90">Kode</th>
                        <th>Nama Pangkalan / Job</th>
                        <th>Pimpinan Pos</th>
                        <th>Kategori Kinerja Terkait</th>
                        <th width="80" class="text-center">Karyawan</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $p)
                    <tr>
                        <td>{{ $data->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $p->kode_pangkalan }}</span></td>
                        <td class="fw-semibold">{{ $p->nama_pangkalan }}</td>
                        <td>{{ $p->pimpinan_pos ?? '-' }}</td>
                        <td>
                            <div class="mb-1">
                                <span class="badge bg-info text-dark">{{ $p->kategori_kinerja_count }} kategori</span>
                            </div>
                            @if($p->kategoriKinerja->isNotEmpty())
                                <small class="text-muted">{{ $p->kategoriKinerja->pluck('kategori')->implode(', ') }}</small>
                            @else
                                <small class="text-muted">Belum ditentukan</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $p->karyawan_count }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.pangkalan.edit', $p) }}" class="btn btn-warning btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.pangkalan.destroy', $p) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus pangkalan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-action"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pangkalan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($data->hasPages())
    <div class="card-footer">{{ $data->links() }}</div>
    @endif
</div>
@endsection

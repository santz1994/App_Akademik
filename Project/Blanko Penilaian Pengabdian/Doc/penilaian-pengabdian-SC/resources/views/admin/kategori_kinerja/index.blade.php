@extends('layouts.app')
@section('title','Kategori Kinerja')
@section('page-title','Kategori Kinerja')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-tag me-2"></i>List Kategori Kinerja</span>
        <a href="{{ route('admin.kategori-kinerja.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Add Data
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>No</th><th>Kode Kategori</th><th>Kategori</th><th>Bobot (%)</th><th>Kompetensi</th><th width="120">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($data as $i => $d)
                <tr>
                    <td>{{ $data->firstItem() + $i }}</td>
                    <td><span class="badge bg-secondary">{{ $d->kode_kategori }}</span></td>
                    <td>{{ $d->kategori }}</td>
                    <td><span class="badge bg-info text-dark">{{ $d->bobot }}%</span></td>
                    <td>{{ $d->kompetensi_count }}</td>
                    <td>
                        <a href="{{ route('admin.kategori-kinerja.edit', $d) }}" class="btn btn-warning btn-action"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.kategori-kinerja.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-action"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($data->hasPages())<div class="card-footer">{{ $data->links() }}</div>@endif
</div>
@endsection

@extends('layouts.app')
@section('title','Tahun Penilaian')
@section('page-title','Tahun Penilaian')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-calendar3 me-2"></i>List Tahun Penilaian</span>
        <div class="d-flex align-items-center gap-2">
            @include('components.per-page-select', ['standalone' => true])
            <a href="{{ route('admin.tahun-penilaian.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Data
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>No</th><th>ID</th><th>Periode Penilaian</th><th>Keterangan</th><th>Status</th><th width="120">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($data as $i => $d)
                <tr>
                    <td>{{ $data->firstItem() + $i }}</td>
                    <td><code class="text-muted" style="font-size:.75rem;">TPD-{{ str_pad($d->id, 3, '0', STR_PAD_LEFT) }}</code></td>
                    <td><strong>{{ $d->periode_penilaian }}</strong></td>
                    <td>{{ $d->keterangan ?? '-' }}</td>
                    <td>
                        @if($d->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.tahun-penilaian.edit', $d) }}" class="btn btn-warning btn-action"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('admin.tahun-penilaian.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Hapus?')">
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

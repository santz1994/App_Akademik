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

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('admin.pangkalan.index') }}" class="d-flex align-items-end gap-2 flex-wrap">
            <div>
                <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Status:</label>
                <select name="status_aktif" class="form-select form-select-sm" style="min-width:145px;">
                    <option value="">-- Semua --</option>
                    <option value="aktif" {{ ($filterStatusAktif ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ ($filterStatusAktif ?? '') === 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            @include('components.per-page-select')
            @if(request()->hasAny(['status_aktif', 'per_page']))
                <a href="{{ route('admin.pangkalan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-building me-2"></i>List Pangkalan Job</span>
        <div class="d-flex align-items-center gap-2">
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
                        <th>Kepala Pimpinan Pos</th>
                        <th>Kategori Kinerja Terkait</th>
                        <th width="80" class="text-center">Karyawan</th>
                        <th width="80" class="text-center">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $p)
                    <tr>
                        <td>{{ $data->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $p->kode_pangkalan }}</span></td>
                        <td class="fw-semibold">{{ $p->nama_pangkalan }}</td>
                        <td>
                            @if($p->kepalaUser)
                                {{ $p->kepalaUser->name }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="badge bg-info text-dark">{{ $p->kategori_kinerja_count }} kategori</span>
                            </div>
                            @if($p->kategoriKinerja->isNotEmpty())
                                @foreach($p->kategoriKinerja->groupBy('jenis') as $jenis => $kats)
                                    <div class="small mb-1">
                                        <span class="badge {{ $jenis === 'kinerja' ? 'bg-primary' : 'bg-warning text-dark' }} me-1" style="font-size:.6rem;">{{ ucfirst($jenis) }}</span>
                                        @foreach($kats as $kat)
                                            <span class="text-muted" style="font-size:.75rem;">{{ $kat->kategori }}</span>{{ $loop->last ? '' : ', ' }}
                                        @endforeach
                                    </div>
                                @endforeach
                            @else
                                <small class="text-muted">Belum ditentukan</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $p->karyawan_count }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $p->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $p->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.pangkalan.toggle-status', $p) }}" class="d-inline"
                                  onsubmit="return confirm('{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }} pangkalan ini?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-{{ $p->is_active ? 'outline-secondary' : 'outline-success' }} btn-action"
                                        title="{{ $p->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi bi-{{ $p->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                </button>
                            </form>
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
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pangkalan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($data->hasPages())
    <div class="card-footer">{{ $data->links() }}</div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.card.mb-3 form');
        if (form) {
            form.querySelectorAll('select').forEach(function (sel) {
                sel.addEventListener('change', function () { form.submit(); });
            });
        }
    });
</script>
@endpush
@endsection

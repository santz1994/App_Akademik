@extends('layouts.app')
@section('title', 'Reward & Punishment')
@section('page-title', 'Pengaturan Reward & Punishment')
@section('content')

<div class="card">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-gift me-2"></i>Daftar Reward & Punishment</span>
        <a href="{{ route('admin.reward-punishment.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Baru
        </a>
    </div>
    <div class="card-body">
        {{-- Filter --}}
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama/kode/grade..."
                       value="{{ $search }}">
            </div>
            <div class="col-md-3">
                <select name="tipe" class="form-select form-select-sm">
                    <option value="">Semua Tipe</option>
                    <option value="reward" {{ $filterTipe === 'reward' ? 'selected' : '' }}>Reward</option>
                    <option value="punishment" {{ $filterTipe === 'punishment' ? 'selected' : '' }}>Punishment</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Grade</th>
                        <th>Nama</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->kode }}</td>
                            <td>
                                @if($item->tipe === 'reward')
                                    <span class="badge bg-success"><i class="bi bi-gift me-1"></i>Reward</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Punishment</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $item->grade }}</span></td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->satuan ?? '-' }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.reward-punishment.edit', $item) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.reward-punishment.destroy', $item) }}"
                                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data reward/punishment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </div>
</div>

@endsection

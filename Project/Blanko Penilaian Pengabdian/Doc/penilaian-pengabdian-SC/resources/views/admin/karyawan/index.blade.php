@extends('layouts.app')
@section('title','List Karyawan')
@section('page-title','Data Karyawan')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-person-badge me-2"></i>List Karyawan</span>
        <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Add Data
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Karyawan</th>
                        <th>Nama Karyawan</th>
                        <th>Pangkalan Job</th>
                        <th>Tugas Khusus</th>
                        <th>Tahun Penilaian</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $i => $k)
                    <tr>
                        <td>{{ $karyawan->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary">{{ $k->kode_karyawan }}</span></td>
                        <td class="fw-semibold">{{ $k->nama_karyawan }}</td>
                        <td>
                            @if($k->pangkalan)
                            <small class="badge bg-info text-dark">{{ $k->pangkalan->kode_pangkalan }}</small>
                            {{ $k->pangkalan->nama_pangkalan }}
                            @else <span class="text-muted">-</span> @endif
                        </td>
                        <td class="text-truncate" style="max-width:160px;">{{ $k->tugas_khusus ?? '-' }}</td>
                        <td>{{ $k->tahunPenilaian->periode_penilaian ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.karyawan.edit', $k) }}" class="btn btn-warning btn-action">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.karyawan.destroy', $k) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus karyawan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-action"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data karyawan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($karyawan->hasPages())
    <div class="card-footer">{{ $karyawan->links() }}</div>
    @endif
</div>
@endsection

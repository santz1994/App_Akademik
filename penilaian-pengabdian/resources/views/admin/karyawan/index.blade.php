@extends('layouts.app')
@section('title','List Karyawan')
@section('page-title','Data Karyawan')
@push('styles')
<style>
    .karyawan-clickable { cursor: pointer; }
    .karyawan-clickable:hover { background-color: #f8fafc; }
</style>
@endpush
@section('content')

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('admin.karyawan.index') }}" class="d-flex align-items-end gap-2 flex-wrap">
            <div>
                <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Cari:</label>
                <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control form-control-sm" style="min-width:220px;"
                       placeholder="Nama, kode, atau nomor induk">
            </div>

            <div>
                <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Pangkalan:</label>
                <select name="pangkalan_id" class="form-select form-select-sm" style="min-width:220px;">
                    <option value="">-- Semua Pangkalan --</option>
                    @foreach($pangkalanList as $p)
                        <option value="{{ $p->id }}" {{ (string)($filterPangkalan ?? '') === (string)$p->id ? 'selected' : '' }}>
                            {{ $p->kode_pangkalan }} - {{ $p->nama_pangkalan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Status:</label>
                <select name="status_aktif" class="form-select form-select-sm" style="min-width:145px;">
                    <option value="">-- Semua --</option>
                    <option value="aktif" {{ ($filterStatusAktif ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ ($filterStatusAktif ?? '') === 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div>
                <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Jabatan:</label>
                <select name="status_kepala" class="form-select form-select-sm" style="min-width:165px;">
                    <option value="">-- Semua --</option>
                    <option value="kepala" {{ ($filterStatusKepala ?? '') === 'kepala' ? 'selected' : '' }}>Kepala</option>
                    <option value="nonkepala" {{ ($filterStatusKepala ?? '') === 'nonkepala' ? 'selected' : '' }}>Bukan Kepala</option>
                </select>
            </div>

            @include('components.per-page-select')

            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-search me-1"></i>Terapkan
            </button>

            @if(request()->hasAny(['q', 'pangkalan_id', 'status_aktif', 'status_kepala', 'per_page']))
                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-person-badge me-2"></i>List Karyawan</span>
        <div class="d-flex align-items-center gap-2">
            <form method="POST" action="{{ route('admin.karyawan.import') }}" enctype="multipart/form-data" class="d-flex gap-1 align-items-center">
                @csrf
                <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" style="max-width:180px;" required>
                <button type="submit" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-upload me-1"></i>Import
                </button>
            </form>
            <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Data
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Karyawan</th>
                        <th>Nomor Induk</th>
                        <th>Nomor Surat Tugas</th>
                        <th>Tanggal Surat Tugas</th>
                        <th>Status</th>
                        <th>Pangkalan Job</th>
                        <th>Tahun Penilaian</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $i => $k)
                    <tr class="karyawan-clickable" data-preview-url="{{ route('admin.karyawan.profile-pdf', $k) }}">
                        <td>{{ $karyawan->firstItem() + $i }}</td>
                        <td>
                            @php
                                $fotoPath = ltrim((string) ($k->foto_path ?? ''), '/');
                                $fotoUrl = null;
                                if ($fotoPath !== '' && \Illuminate\Support\Facades\Storage::disk('public')->exists($fotoPath)) {
                                    $fotoUrl = asset('storage/' . $fotoPath);
                                }
                            @endphp
                            @if($fotoUrl)
                                <img src="{{ $fotoUrl }}" alt="Foto {{ $k->nama_karyawan }}"
                                     style="width:40px;height:53px;object-fit:cover;border-radius:4px;border:1px solid #e2e8f0;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">
                                {{ $k->nama_karyawan }}
                                @if($k->user?->is_kepala)
                                    <span class="badge bg-warning text-dark ms-1">Kepala</span>
                                @endif
                            </div>
                            <small class="text-muted">{{ $k->kode_karyawan }}</small>
                        </td>
                        <td>{{ $k->nomor_induk ?: '-' }}</td>
                        <td>{{ $k->nomor_surat_tugas ?: '-' }}</td>
                        <td>{{ $k->tanggal_surat_tugas ? $k->tanggal_surat_tugas->format('d-m-Y') : '-' }}</td>
                        <td>
                            <span class="badge {{ $k->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $k->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            @if($k->pangkalan)
                            <small class="badge bg-info text-dark">{{ $k->pangkalan->kode_pangkalan }}</small>
                            {{ $k->pangkalan->nama_pangkalan }}
                            @else <span class="text-muted">-</span> @endif
                        </td>
                        <td>{{ $k->tahunPenilaian->periode_penilaian ?? '-' }}</td>
                        <td class="no-preview">
                            <a href="{{ route('admin.karyawan.profile-pdf', $k) }}" target="_blank" class="btn btn-outline-danger btn-action" title="Preview PDF Profil">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.karyawan.toggle-status', $k) }}" class="d-inline"
                                  onsubmit="return confirm('{{ $k->is_active ? 'Nonaktifkan' : 'Aktifkan' }} karyawan ini?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-{{ $k->is_active ? 'outline-secondary' : 'outline-success' }} btn-action"
                                        title="{{ $k->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi bi-{{ $k->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                </button>
                            </form>
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
                    <tr><td colspan="13" class="text-center text-muted py-4">Belum ada data karyawan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($karyawan->hasPages())
    <div class="card-footer">{{ $karyawan->links() }}</div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('tr.karyawan-clickable').forEach(function (row) {
            row.addEventListener('click', function (event) {
                if (event.target.closest('a, button, input, form, .no-preview')) {
                    return;
                }

                const previewUrl = row.getAttribute('data-preview-url');
                if (previewUrl) {
                    window.open(previewUrl, '_blank');
                }
            });
        });
    });
</script>
@endpush
@endsection

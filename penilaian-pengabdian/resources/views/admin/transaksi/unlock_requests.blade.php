@extends('layouts.app')
@section('title','Request Unlock Nilai')
@section('page-title','Request Unlock Nilai')
@section('content')

@php
    $setting = $setting ?? \App\Models\SettingLembaga::where('is_active', true)->latest()->first()
        ?? \App\Models\SettingLembaga::latest()->first();
    $lockEnabled = (bool) ($setting->lock_enabled ?? true);
@endphp

{{-- Global Lock Toggle --}}
<div class="card mb-3 border-{{ $lockEnabled ? 'warning' : 'success' }}">
    <div class="card-body py-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:{{ $lockEnabled ? '#fff3cd' : '#d1e7dd' }};">
                    <i class="bi bi-{{ $lockEnabled ? 'lock text-warning' : 'unlock text-success' }}" style="font-size:1.4rem;"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Status Sistem Kunci Nilai</h6>
                    <small class="text-muted">
                        @if($lockEnabled)
                            <span class="text-warning fw-semibold">AKTIF</span> — Semua nilai terkunci. Karyawan harus ajukan unlock request untuk mengubah nilai.
                        @else
                            <span class="text-success fw-semibold">NONAKTIF</span> — Sistem kunci dinonaktifkan. Semua nilai dapat diubah langsung tanpa perlu unlock.
                        @endif
                    </small>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.transaksi.toggle-lock') }}" id="toggleLockForm">
                @csrf
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="lockToggle"
                        {{ $lockEnabled ? 'checked' : '' }}
                        onchange="confirmToggle(this)">
                    <label class="form-check-label fw-semibold" for="lockToggle" style="cursor:pointer;">
                        {{ $lockEnabled ? 'Kunci Aktif' : 'Kunci Nonaktif' }}
                    </label>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Unlock Requests Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-list-check me-2"></i>Daftar Pengajuan Unlock</span>
        <div class="d-flex align-items-center gap-2">
            @include('components.per-page-select', ['standalone' => true])
            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-secondary">Kembali Transaksi</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Karyawan</th>
                        <th>Tahun</th>
                        <th>Pemohon</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th width="240">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $i => $r)
                    <tr>
                        <td>{{ $requests->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold">{{ $r->karyawan?->nama_karyawan ?? '-' }}</div>
                            <small class="text-muted">{{ $r->karyawan?->kode_karyawan ?? '-' }}</small>
                        </td>
                        <td>{{ $r->tahunPenilaian?->periode_penilaian ?? '-' }}</td>
                        <td>{{ $r->requestedBy?->name ?? '-' }}</td>
                        <td style="max-width:320px;">{{ $r->alasan }}</td>
                        <td>
                            @if($r->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($r->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($r->status === 'pending')
                                @if($lockEnabled)
                                <form method="POST" action="{{ route('admin.transaksi.review-unlock-request', $r) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Approve unlock ini?')">
                                        <i class="bi bi-check2-circle"></i> Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.transaksi.review-unlock-request', $r) }}" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Reject unlock ini?')">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                </form>
                                @else
                                    <span class="badge bg-info">Sistem Kunci Nonaktif</span>
                                @endif
                            @else
                                <small class="text-muted">Diproses: {{ optional($r->reviewed_at)->format('d/m/Y H:i') }}</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pengajuan unlock.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($requests->hasPages())
    <div class="card-footer">{{ $requests->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function confirmToggle(checkbox) {
    const action = checkbox.checked ? 'AKTIFKAN' : 'NONAKTIFKAN';
    const msg = checkbox.checked
        ? 'Aktifkan sistem kunci nilai?\n\nSemua nilai akan terkunci dan karyawan harus ajukan unlock request untuk mengubah.'
        : 'Nonaktifkan sistem kunci nilai?\n\nSemua nilai akan bisa diubah langsung tanpa perlu unlock.';
    if (!confirm(msg)) {
        checkbox.checked = !checkbox.checked;
        return;
    }
    document.getElementById('toggleLockForm').submit();
}
</script>
@endpush

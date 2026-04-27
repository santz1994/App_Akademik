@extends('layouts.app')
@section('title','Request Unlock Nilai')
@section('page-title','Request Unlock Nilai')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-unlock me-2"></i>Daftar Pengajuan Unlock</span>
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

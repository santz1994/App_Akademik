@extends('layouts.app')
@section('title','Mutasi Antar Pangkalan Job')
@section('page-title','Mutasi Antar Pangkalan Job')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show py-2" role="alert">
    <i class="bi bi-check-circle me-1"></i> {!! session('success') !!}
    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="mb-2 d-flex justify-content-end">
    @include('components.per-page-select', ['standalone' => true])
</div>

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('admin.mutasi.pangkalan') }}" class="d-flex align-items-end gap-2 flex-wrap">
            <div>
                <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Cari:</label>
                <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control form-control-sm" style="min-width:200px;"
                       placeholder="Nama atau kode karyawan">
            </div>
            <div>
                <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Pangkalan:</label>
                <select name="pangkalan_id" class="form-select form-select-sm" style="min-width:200px;">
                    <option value="">-- Semua Pangkalan --</option>
                    @foreach($pangkalanList as $p)
                        <option value="{{ $p->id }}" {{ (string)($filterPangkalan ?? '') === (string)$p->id ? 'selected' : '' }}>
                            {{ $p->kode_pangkalan }} - {{ $p->nama_pangkalan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-search me-1"></i>Terapkan
            </button>
            @if(request()->hasAny(['q', 'pangkalan_id']))
                <a href="{{ route('admin.mutasi.pangkalan') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Bulk Assign Form --}}
<form method="POST" action="{{ route('admin.mutasi.bulk-assign-pangkalan') }}" id="bulkForm">
@csrf

{{-- Action Bar --}}
<div class="card mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="fw-semibold me-1" style="font-size:.85rem;">
                <i class="bi bi-people me-1"></i>
                <span id="selectedCount">0</span> karyawan dipilih
            </span>
            <div class="vr mx-1"></div>
            <label class="fw-semibold mb-0" style="font-size:.85rem; white-space:nowrap;">
                Pindahkan ke Pangkalan:
            </label>
            <select name="pangkalan_id" class="form-select form-select-sm" style="width:auto; min-width:200px;" required>
                <option value=""> Pilih Pangkalan </option>
                @foreach($pangkalanList as $p)
                <option value="{{ $p->id }}">
                    {{ $p->kode_pangkalan }} — {{ $p->nama_pangkalan }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm" id="btnBulk" disabled>
                <i class="bi bi-arrow-left-right me-1"></i>Terapkan
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm ms-1" id="btnClear">
                <i class="bi bi-x-lg me-1"></i>Batal Pilih
            </button>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header py-2">
        <span class="fw-semibold"><i class="bi bi-building me-2"></i>Daftar Karyawan & Pangkalan Job</span>
        <span class="badge bg-primary ms-2">{{ $karyawan->total() }} Karyawan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">
                            <input class="form-check-input" type="checkbox" id="checkAll" title="Pilih Semua">
                        </th>
                        <th>Kode Karyawan</th>
                        <th>Nama Karyawan</th>
                        <th>Pangkalan Job Saat Ini</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $i => $k)
                    <tr class="row-item">
                        <td class="text-center">
                            <input class="form-check-input row-check" type="checkbox"
                                   name="karyawan_ids[]" value="{{ $k->id }}">
                        </td>
                        <td><span class="badge bg-secondary">{{ $k->kode_karyawan }}</span></td>
                        <td class="fw-semibold">{{ $k->nama_karyawan }}</td>
                        <td>
                            @if($k->pangkalan)
                                <span class="badge bg-info text-dark">{{ $k->pangkalan->kode_pangkalan }}</span>
                                {{ $k->pangkalan->nama_pangkalan }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data karyawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($karyawan->hasPages())
    <div class="card-footer">{{ $karyawan->links() }}</div>
    @endif
</div>

</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');
    const rowChecks = document.querySelectorAll('.row-check');
    const selectedCount = document.getElementById('selectedCount');
    const btnBulk = document.getElementById('btnBulk');
    const btnClear = document.getElementById('btnClear');

    function updateUI() {
        const checked = document.querySelectorAll('.row-check:checked');
        selectedCount.textContent = checked.length;
        btnBulk.disabled = checked.length === 0;
    }

    checkAll.addEventListener('change', function () {
        rowChecks.forEach(ch => ch.checked = checkAll.checked);
        updateUI();
    });

    rowChecks.forEach(ch => ch.addEventListener('change', updateUI));

    btnClear.addEventListener('click', function () {
        rowChecks.forEach(ch => ch.checked = false);
        checkAll.checked = false;
        updateUI();
    });
});
</script>
@endpush

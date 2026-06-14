@extends('layouts.app')
@section('title','Mutasi Karyawan')
@section('page-title','Mutasi Karyawan')
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

{{-- Bulk Assign Form --}}
<form method="POST" action="{{ route('admin.mutasi.bulk-assign') }}" id="bulkForm">
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
                Pindahkan ke Tahun:
            </label>
            <select name="tahun_penilaian_id" class="form-select form-select-sm" style="width:auto; min-width:160px;" required>
                <option value=""> Pilih Tahun </option>
                @foreach($tahunList as $t)
                <option value="{{ $t->id }}">
                    {{ $t->periode_penilaian }}{{ $t->is_active ? ' (Aktif)' : '' }}
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
        <span class="fw-semibold"><i class="bi bi-arrow-left-right me-2"></i>Mutasi Karyawan  Penentuan Tahun Ajaran</span>
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
                        <th>Alamat</th>
                        <th>Tahun Ajaran Saat Ini</th>
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
                        <td class="text-truncate" style="max-width:220px;">{{ $k->alamat ?? '-' }}</td>
                        <td>
                            @if($k->tahunPenilaian)
                                {{ $k->tahunPenilaian->periode_penilaian }}
                                @if($k->tahunPenilaian->is_active)
                                    <span class="badge bg-success ms-1" style="font-size:.65rem;">Aktif</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data karyawan.</td>
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

<script>
const checkAll  = document.getElementById('checkAll');
const btnBulk   = document.getElementById('btnBulk');
const btnClear  = document.getElementById('btnClear');
const counter   = document.getElementById('selectedCount');

function updateState() {
    const checks = document.querySelectorAll('.row-check');
    const checked = document.querySelectorAll('.row-check:checked');
    counter.textContent = checked.length;
    btnBulk.disabled = checked.length === 0;
    checkAll.indeterminate = checked.length > 0 && checked.length < checks.length;
    checkAll.checked = checks.length > 0 && checked.length === checks.length;
    document.querySelectorAll('.row-item').forEach(tr => {
        tr.classList.toggle('table-active', tr.querySelector('.row-check').checked);
    });
}

checkAll.addEventListener('change', () => {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll.checked);
    updateState();
});

document.querySelectorAll('.row-check').forEach(cb => cb.addEventListener('change', updateState));

btnClear.addEventListener('click', () => {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
    checkAll.checked = false;
    updateState();
});

document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.row-check:checked');
    if (checked.length === 0) { e.preventDefault(); alert('Pilih minimal 1 karyawan.'); }
});
</script>
@endsection

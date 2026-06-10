@extends('layouts.app')
@section('title','Dashboard Kepala')
@section('page-title','Dashboard Kepala Pimpinan Pos')
@section('content')

{{-- Pangkalan Selector --}}
@if($pangkalanList->count() > 1)
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('kepala.dashboard') }}" class="d-flex align-items-end gap-2 flex-wrap">
            <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-building me-1"></i>Pangkalan:</label>
            <select name="pangkalan_id" class="form-select form-select-sm" style="max-width:300px;" onchange="this.form.submit()">
                <option value="">-- Semua Pangkalan --</option>
                @foreach($pangkalanList as $p)
                <option value="{{ $p->id }}" {{ (string)($selectedPangkalanId ?? '') === (string)$p->id ? 'selected' : '' }}>
                    {{ $p->kode_pangkalan }} — {{ $p->nama_pangkalan }}
                </option>
                @endforeach
            </select>
        </form>
    </div>
</div>
@endif

<div class="card mb-4">
    <div class="card-body py-3">
        <h6 class="fw-bold mb-1"><i class="bi bi-diagram-3 text-primary me-2"></i>Dashboard Kepala Pimpinan Pos</h6>
        <p class="mb-0 text-muted" style="font-size:.875rem;">
            Pangkalan: <strong>{{ $stats['pangkalan'] }}</strong>
            <span class="mx-2">|</span>
            Tahun Aktif: <strong>{{ $stats['tahun_aktif'] }}</strong>
        </p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['total_karyawan'] }}</div>
                <div class="text-muted" style="font-size:.8rem;">Karyawan Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success">{{ $stats['total_penilaian'] }}</div>
                <div class="text-muted" style="font-size:.8rem;">Total Data Penilaian</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-warning">{{ $stats['sudah_dinilai'] }}</div>
                <div class="text-muted" style="font-size:.8rem;">Karyawan Sudah Dinilai</div>
            </div>
        </div>
    </div>
</div>

{{-- Daftar Karyawan --}}
<div class="card">
    <div class="card-header py-2 fw-semibold">
        <i class="bi bi-people me-2"></i>Daftar Karyawan Aktif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th width="40">No</th>
                        <th>Nama Karyawan</th>
                        <th>Pangkalan Job</th>
                        <th width="100" class="text-center">Sudah Dinilai</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawanList as $i => $k)
                    @php
                        // Count scored per pangkalan
                        $totalKomp = 0;
                        $scoredCount = 0;
                        if ($k->pangkalans && $k->pangkalans->count() > 0) {
                            foreach ($k->pangkalans as $pk) {
                                $pkKatIds = $pk->kategoriKinerja->pluck('id')->toArray();
                                $pkKompCount = \App\Models\KategoriKinerja::with('kompetensi')
                                    ->whereIn('id', $pkKatIds)
                                    ->get()
                                    ->sum(fn($kat) => $kat->kompetensi->count());
                                $totalKomp += $pkKompCount;
                                $pkScored = $k->transaksi->filter(fn($t) => $t->nilai !== null && (int) ($t->pangkalan_id ?? 0) === (int) $pk->id);
                                $scoredCount += $pkScored->count();
                            }
                        }
                        // "Sudah" if at least some scores entered
                        $isSudahDinilai = $scoredCount > 0;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $k->nama_karyawan }}</div>
                            <small class="text-muted">{{ $k->kode_karyawan }}</small>
                        </td>
                        <td>
                            @foreach($k->pangkalans as $p)
                                <span class="badge bg-light text-dark border">{{ $p->kode_pangkalan }}</span>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @if($isSudahDinilai)
                                <span class="badge bg-success"><i class="bi bi-check me-1"></i>Sudah</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kepala.transaksi.create', ['karyawan_id' => $k->id, 'tahun_penilaian_id' => $tahunId]) }}"
                               class="btn btn-sm btn-outline-primary" style="font-size:.75rem;">
                                <i class="bi bi-pencil me-1"></i>Nilai
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada karyawan aktif di pangkalan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

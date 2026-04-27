@extends('layouts.app')
@section('title','Laporan')
@section('page-title','Laporan Penilaian Pengabdian')
@section('content')

{{-- Filter --}}
<form method="GET" action="{{ route('admin.laporan.index') }}" class="mb-3 d-flex align-items-center gap-2">
    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Tahun Ajaran:</label>
    <select name="tahun_penilaian_id" class="form-select form-select-sm" style="max-width:200px;" onchange="this.form.submit()">
        <option value="">-- Semua Tahun --</option>
        @foreach($tahunList as $t)
        <option value="{{ $t->id }}" {{ $selectedTahun == $t->id ? 'selected' : '' }}>
            {{ $t->periode_penilaian }} {{ $t->is_active ? '(Aktif)' : '' }}
        </option>
        @endforeach
    </select>
    @if($selectedTahun)
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    @endif
</form>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $karyawanList->count() }}</div>
                <div class="text-muted" style="font-size:.8rem;">Total Karyawan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-success">{{ $totalTransaksi }}</div>
                <div class="text-muted" style="font-size:.8rem;">Total Data Penilaian</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-info">{{ $totalKompetensi }}</div>
                <div class="text-muted" style="font-size:.8rem;">Indikator Kompetensi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                @php
                    $rated = $karyawanList->filter(fn($k) => $k->transaksi->count() > 0)->count();
                    $pct   = $karyawanList->count() > 0 ? round($rated / $karyawanList->count() * 100) : 0;
                @endphp
                <div class="fs-3 fw-bold text-warning">{{ $pct }}%</div>
                <div class="text-muted" style="font-size:.8rem;">Sudah Dinilai ({{ $rated }}/{{ $karyawanList->count() }})</div>
            </div>
        </div>
    </div>
</div>

{{-- Rekapitulasi Tabel --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold">
            <i class="bi bi-table me-2"></i>Rekapitulasi Nilai Pengabdian
            @if($selectedTahunData)
                <span class="text-primary ms-1">â€” {{ $selectedTahunData->periode_penilaian }}</span>
            @endif
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle" style="font-size:.82rem;">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2" class="align-middle text-center" width="36">No</th>
                        <th rowspan="2" class="align-middle">Nama Karyawan</th>
                        <th rowspan="2" class="align-middle">Pangkalan Job</th>
                        @foreach($kategoriList as $kat)
                        <th colspan="{{ $kat->kompetensi->count() }}" class="text-center">
                            {{ $kat->kategori }}
                            <div class="badge bg-info text-dark fw-normal" style="font-size:.7rem;">Bobot {{ $kat->bobot }}%</div>
                        </th>
                        @endforeach
                        <th rowspan="2" class="align-middle text-center">Nilai Akhir</th>
                        <th rowspan="2" class="align-middle text-center">Rating</th>
                    </tr>
                    <tr class="table-secondary">
                        @foreach($kategoriList as $kat)
                            @foreach($kat->kompetensi as $komp)
                            <th class="text-center" style="white-space:nowrap; font-size:.75rem; font-weight:600;">
                                {{ $komp->kode_kompetensi }}<br>
                                <span class="fw-normal text-muted" style="font-size:.7rem;">{{ $komp->kompetensi }}</span>
                            </th>
                            @endforeach
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawanList as $i => $k)
                    @php
                        $trx = $k->transaksi->keyBy('kompetensi_id');

                        // Weighted score calculation
                        $nilaiAkhir = null;
                        $weighted = 0; $totalBobot = 0;
                        foreach ($kategoriList as $kat) {
                            $bobot = $kat->bobot;
                            $vals = [];
                            foreach ($kat->kompetensi as $komp) {
                                $t = $trx->get($komp->id);
                                if ($t && $t->nilai !== null) $vals[] = (float) $t->nilai;
                            }
                            if (count($vals) > 0) {
                                $avg = array_sum($vals) / count($vals);
                                $weighted   += $avg * $bobot;
                                $totalBobot += $bobot;
                            }
                        }
                        if ($totalBobot > 0) $nilaiAkhir = $weighted / $totalBobot;

                        $rating = '-'; $ratingColor = 'secondary';
                        if ($nilaiAkhir !== null) {
                            if      ($nilaiAkhir >= 90) { $rating = 'A â€” Sangat Baik';   $ratingColor = 'success'; }
                            elseif  ($nilaiAkhir >= 80) { $rating = 'B â€” Baik';           $ratingColor = 'primary'; }
                            elseif  ($nilaiAkhir >= 70) { $rating = 'C â€” Cukup';          $ratingColor = 'warning'; }
                            elseif  ($nilaiAkhir >= 60) { $rating = 'D â€” Kurang';         $ratingColor = 'danger'; }
                            else                        { $rating = 'E â€” Sangat Kurang';  $ratingColor = 'dark'; }
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>
                            <div class="fw-semibold">{{ $k->nama_karyawan }}</div>
                            <small class="text-muted">{{ $k->kode_karyawan }}</small>
                        </td>
                        <td style="white-space:nowrap;">
                            {{ $k->pangkalan?->nama_pangkalan ?? '-' }}
                        </td>
                        @foreach($kategoriList as $kat)
                            @foreach($kat->kompetensi as $komp)
                            <td class="text-center">
                                @php $t = $trx->get($komp->id); @endphp
                                @if($t && $t->nilai !== null)
                                    <span class="fw-semibold">{{ number_format($t->nilai, 0) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            @endforeach
                        @endforeach
                        <td class="text-center fw-bold fs-6">
                            {{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}
                        </td>
                        <td class="text-center">
                            @if($nilaiAkhir !== null)
                                <span class="badge bg-{{ $ratingColor }}">{{ $rating }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ 3 + $kategoriList->sum(fn($k) => $k->kompetensi->count()) + 2 }}"
                            class="text-center text-muted py-4">
                            Tidak ada data untuk tahun ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

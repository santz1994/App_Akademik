@extends('layouts.app')
@section('title','Laporan')
@section('page-title','Laporan Penilaian Pengabdian')
@section('content')

@php
    $routePrefix = $routePrefix ?? 'admin';
    $totalKaryawanFiltered = $totalKaryawanFiltered ?? $karyawanList->count();
    $ratedCount = $ratedCount ?? 0;
    $reportFormat = array_merge([
        'show_bobot_kategori' => true,
        'show_detail_kompetensi' => true,
        'scoring_method' => 'weighted_kinerja_kegiatan',
        'score_weight_kinerja' => 70,
        'score_weight_kegiatan' => 30,
    ], $reportFormat ?? []);
    $isRingkas = ($jenisLaporan ?? 'ringkas') === 'ringkas';
@endphp

@if($routePrefix === 'admin')
<div class="d-flex justify-content-end gap-2 mb-2">
    <a href="{{ route('admin.penilaian-metode.edit') }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-calculator me-1"></i>Cara Penilaian
    </a>
    <a href="{{ route('admin.laporan.format.edit') }}" class="btn btn-sm btn-outline-dark">
        <i class="bi bi-sliders me-1"></i>Atur Format Cetak
    </a>
</div>
@endif

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
<form method="GET" action="{{ route($routePrefix . '.laporan.index') }}" class="d-flex align-items-end gap-2 flex-wrap">
    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Tahun Ajaran:</label>
    <select name="tahun_penilaian_id" class="form-select form-select-sm" style="max-width:200px;">
        <option value="">-- Semua Tahun --</option>
        @foreach($tahunList as $t)
        <option value="{{ $t->id }}" {{ $selectedTahun == $t->id ? 'selected' : '' }}>
            {{ $t->periode_penilaian }} {{ $t->is_active ? '(Aktif)' : '' }}
        </option>
        @endforeach
    </select>

    @if($routePrefix === 'admin' || ($routePrefix === 'kepala' && ($pangkalanList ?? collect())->count() > 1))
    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Pangkalan:</label>
    <select name="pangkalan_id" id="filterPangkalanSelect" class="form-select form-select-sm" style="max-width:220px;">
        <option value="">-- Semua Pangkalan --</option>
        @foreach($pangkalanList as $p)
            <option value="{{ $p->id }}" {{ (string)$filterPangkalan === (string)$p->id ? 'selected' : '' }}>
                {{ $p->nama_pangkalan }}
            </option>
        @endforeach
    </select>
    @endif

    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Jenis Output:</label>
    <select name="jenis_laporan" class="form-select form-select-sm" style="max-width:180px;">
        <option value="ringkas" {{ ($jenisLaporan ?? 'ringkas') === 'ringkas' ? 'selected' : '' }}>Ringkas</option>
        <option value="rinci" {{ ($jenisLaporan ?? 'ringkas') === 'rinci' ? 'selected' : '' }} {{ ($reportFormat['show_detail_kompetensi'] ?? true) ? '' : 'disabled' }}>Rinci</option>
    </select>

    @include('components.per-page-select')

    @if(request()->hasAny(['tahun_penilaian_id', 'pangkalan_id', 'jenis_laporan', 'per_page']))
        <a href="{{ route($routePrefix . '.laporan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    @endif
</form>
    <small class="text-muted d-block mt-1">Filter akan mempengaruhi tampilan tabel serta output cetak/PDF/Excel/CSV.</small>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $totalKaryawanFiltered }}</div>
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
                    $pct = $totalKaryawanFiltered > 0 ? round($ratedCount / $totalKaryawanFiltered * 100) : 0;
                @endphp
                <div class="fs-3 fw-bold text-warning">{{ $pct }}%</div>
                <div class="text-muted" style="font-size:.8rem;">Sudah Dinilai ({{ $ratedCount }}/{{ $totalKaryawanFiltered }})</div>
            </div>
        </div>
    </div>
</div>

{{-- Rekapitulasi Tabel --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2 flex-wrap gap-2">
        <span class="fw-semibold">
            <i class="bi bi-table me-2"></i>Rekapitulasi Nilai Pengabdian
            @if($selectedTahunData)
                <span class="text-primary ms-1"> {{ $selectedTahunData->periode_penilaian }}</span>
            @endif
        </span>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route($routePrefix . '.laporan.print', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-printer me-1"></i>Cetak
            </a>
            <a href="{{ route($routePrefix . '.laporan.pdf', request()->query()) }}" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-filetype-pdf me-1"></i>PDF
            </a>
            <a href="{{ route($routePrefix . '.laporan.excel', request()->query()) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-excel me-1"></i>Excel
            </a>
            <a href="{{ route($routePrefix . '.laporan.csv', request()->query()) }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-filetype-csv me-1"></i>CSV
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            @php
                // Kinerja kategori: ambil dari mapping pangkalan
                $kinerjaKategoriList = $kategoriList
                    ->filter(fn($k) => strtolower((string) $k->jenis) === 'kinerja')
                    ->values();
                // Kegiatan kategori: ambil dari mapping pangkalan
                $kegiatanKategoriList = $kategoriList
                    ->filter(fn($k) => strtolower((string) $k->jenis) === 'kegiatan')
                    ->values();
                $totalKinerjaCols = $kinerjaKategoriList->sum(fn($kat) => $kat->kompetensi->count());
                $totalKegiatanCols = $kegiatanKategoriList->sum(fn($kat) => $kat->kompetensi->count());
            @endphp
            <table class="table table-bordered table-hover mb-0 align-middle" style="font-size:.82rem;">
                <thead class="table-dark">
                    @if($isRingkas)
                    {{-- Ringkas: 1 row per kategori --}}
                    <tr>
                        <th class="text-center" width="36">No</th>
                        <th>Nama</th>
                        @foreach($kinerjaKategoriList as $kat)
                            <th class="text-center">{{ $kat->kategori }}</th>
                        @endforeach
                        @foreach($kegiatanKategoriList as $kat)
                            <th class="text-center">{{ $kat->kategori }}</th>
                        @endforeach
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Rating</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" width="60">Aksi</th>
                    </tr>
                    @else
                    {{-- Rinci: 3-row header sesuai target --}}
                    {{-- Row 1: Group headers --}}
                    <tr>
                        <th rowspan="3" class="text-center" width="36">No</th>
                        <th rowspan="3" class="align-middle">Nama</th>
                        @if($totalKinerjaCols > 0)
                        <th colspan="{{ $totalKinerjaCols }}" class="text-center">Kinerja</th>
                        @endif
                        @if($totalKegiatanCols > 0)
                        <th colspan="{{ $totalKegiatanCols }}" class="text-center">Kegiatan</th>
                        @endif
                        <th rowspan="3" class="text-center">Nilai Akhir</th>
                        <th rowspan="3" class="text-center">Rating</th>
                        <th rowspan="3" class="text-center">Keterangan</th>
                        <th rowspan="3" class="text-center" width="60">Aksi</th>
                    </tr>
                    {{-- Row 2: Kategori names --}}
                    <tr>
                        @foreach($kinerjaKategoriList as $kat)
                        <th colspan="{{ $kat->kompetensi->count() }}" class="text-center">
                            {{ $kat->kategori }}
                        </th>
                        @endforeach
                        @foreach($kegiatanKategoriList as $kat)
                        <th colspan="{{ $kat->kompetensi->count() }}" class="text-center">
                            {{ $kat->kategori }}
                        </th>
                        @endforeach
                    </tr>
                    {{-- Row 3: Kompetensi names --}}
                    <tr>
                        @foreach($kinerjaKategoriList as $kat)
                            @foreach($kat->kompetensi as $komp)
                            <th class="text-center" style="white-space:nowrap; font-size:.75rem; font-weight:600;">
                                {{ $komp->kompetensi }}
                            </th>
                            @endforeach
                        @endforeach
                        @foreach($kegiatanKategoriList as $kat)
                            @foreach($kat->kompetensi as $komp)
                            <th class="text-center" style="white-space:nowrap; font-size:.75rem; font-weight:600;">
                                {{ $komp->kompetensi }}
                            </th>
                            @endforeach
                        @endforeach
                    </tr>
                    @endif
                </thead>
                <tbody>
                    @php $rowCounter = 0; @endphp
                    @forelse($karyawanList as $i => $k)
                        @php
                            $kategoriUntukKaryawan = \App\Support\LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $k);
                            $applicableKompetensiIds = \App\Support\LaporanScoreCalculator::kompetensiIdsFromKategori($kategoriUntukKaryawan);
                            $allTrx = $k->transaksi->filter(fn($t) => $t->nilai !== null)->keyBy('kompetensi_id');
                            $trxByKompetensi = $allTrx->filter(fn($t) => $applicableKompetensiIds->contains((int) $t->kompetensi_id));

                            $nilaiAkhir = \App\Support\LaporanScoreCalculator::calculate(
                                $kategoriUntukKaryawan,
                                $trxByKompetensi,
                                $reportFormat['scoring_method'],
                                [
                                    'bobot_kinerja' => $reportFormat['score_weight_kinerja'],
                                    'bobot_kegiatan' => $reportFormat['score_weight_kegiatan'],
                                ]
                            );
                            $ratingMeta = \App\Support\LaporanScoreCalculator::ratingMeta($nilaiAkhir);
                            $rating = $ratingMeta['label'];
                            $ratingColor = $ratingMeta['color'];
                            $rowCounter++;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $rowCounter }}</td>
                            <td class="fw-semibold">{{ $k->nama_karyawan }}</td>

                            @if($isRingkas)
                                {{-- Ringkas: per-kategori averages --}}
                                @foreach($kategoriList as $kat)
                                @php
                                    $isApplicable = $kategoriUntukKaryawan->contains(fn($item) => (int) $item->id === (int) $kat->id);
                                    $kategoriValues = $kat->kompetensi
                                        ->map(fn($komp) => ($trxByKompetensi->get($komp->id) && $trxByKompetensi->get($komp->id)->nilai !== null) ? (float) $trxByKompetensi->get($komp->id)->nilai : null)
                                        ->filter(fn($v) => $v !== null)->values();
                                    $kategoriAvg = $kategoriValues->isNotEmpty() ? ($kategoriValues->sum() / $kategoriValues->count()) : null;
                                @endphp
                                <td class="text-center">
                                    @if(!$isApplicable) <span class="text-muted">-</span>
                                    @elseif($kategoriAvg !== null) <span class="fw-semibold">{{ number_format($kategoriAvg, 2) }}</span>
                                    @else <span class="text-muted">-</span>
                                    @endif
                                </td>
                                @endforeach
                            @else
                                {{-- Rinci: kompetensi values per kategori --}}
                                @foreach($kinerjaKategoriList as $kat)
                                    @foreach($kat->kompetensi as $komp)
                                    <td class="text-center">
                                        @php $t = $trxByKompetensi->get($komp->id); @endphp
                                        @if($t && $t->nilai !== null) {{ number_format($t->nilai, 0) }}
                                        @else <span class="text-muted">-</span> @endif
                                    </td>
                                    @endforeach
                                @endforeach
                                @foreach($kegiatanKategoriList as $kat)
                                    @foreach($kat->kompetensi as $komp)
                                    <td class="text-center">
                                        @php $t = $trxByKompetensi->get($komp->id); @endphp
                                        @if($t && $t->nilai !== null) {{ number_format($t->nilai, 0) }}
                                        @else <span class="text-muted">-</span> @endif
                                    </td>
                                    @endforeach
                                @endforeach
                            @endif

                            <td class="text-center fw-bold fs-6">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}</td>
                            <td class="text-center">
                                @if($nilaiAkhir !== null) <span class="badge bg-{{ $ratingColor }}">{{ $rating }}</span>
                                @else <span class="text-muted">-</span> @endif
                            </td>
                            <td style="font-size:.78rem;">
                                @php $rpInfo = \App\Support\LaporanScoreCalculator::getRewardPunishmentInfo($nilaiAkhir); @endphp
                                @if($rpInfo && $rpInfo['items']->isNotEmpty())
                                    @foreach($rpInfo['items'] as $rpItem)
                                        @if(($rpItem->tipe ?? '') === 'punishment')
                                            <span class="text-danger fw-bold">⚠ {{ $rpItem->nama ?? 'Hukuman' }}:</span>
                                            {{ isset($rpItem->jumlah) && $rpItem->jumlah > 0 ? $rpItem->jumlah . ' ' . ($rpItem->satuan ?? '') : ($rpItem->deskripsi ?? '') }}
                                        @else
                                            <span class="text-success fw-bold">✓ {{ $rpItem->nama ?? 'Reward' }}</span>
                                        @endif
                                        @if(!$loop->last)<br>@endif
                                    @endforeach
                                @else <span class="text-muted">-</span> @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route($routePrefix . '.laporan.perorangan-pdf', ['karyawan_id' => $k->id, 'tahun_penilaian_id' => $selectedTahun]) }}"
                                   target="_blank" class="btn btn-outline-danger btn-sm" style="padding:.15rem .4rem; font-size:.72rem;">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td class="text-center text-muted py-4" colspan="20">Tidak ada data untuk tahun ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($karyawanList, 'hasPages') && $karyawanList->hasPages())
    <div class="card-footer">{{ $karyawanList->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.querySelector('.card.mb-3 form');
        if (filterForm) {
            filterForm.querySelectorAll('select').forEach(function (sel) {
                sel.addEventListener('change', function () { filterForm.submit(); });
            });
        }
    });
</script>
@endpush
@endsection

@extends('layouts.app')
@section('title','Laporan')
@section('page-title','Laporan Penilaian Pengabdian')
@section('content')

@php
    $routePrefix = $routePrefix ?? 'admin';
    $showPangkalanFilter = $showPangkalanFilter ?? false;
    $showKaryawanFilter = $showKaryawanFilter ?? false;
    $totalKaryawanFiltered = $totalKaryawanFiltered ?? $karyawanList->count();
    $ratedCount = $ratedCount ?? 0;
    $reportFormat = array_merge([
        'show_bobot_kategori' => true,
        'show_detail_kompetensi' => true,
        'scoring_method' => 'weighted_kategori',
    ], $reportFormat ?? []);
    $isRingkas = ($jenisLaporan ?? 'ringkas') === 'ringkas';
@endphp

@if($routePrefix === 'admin')
<div class="d-flex justify-content-end mb-2">
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

    @if($routePrefix !== 'user')
    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Mode:</label>
    <select name="mode" id="modeFilter" class="form-select form-select-sm" style="max-width:180px;">
        <option value="keseluruhan" {{ $mode === 'keseluruhan' ? 'selected' : '' }}>Keseluruhan</option>
        @if($routePrefix === 'admin')
        <option value="perdireksi" {{ $mode === 'perdireksi' ? 'selected' : '' }}>Per Direksi/Pangkalan</option>
        @endif
        <option value="perorangan" {{ $mode === 'perorangan' ? 'selected' : '' }}>Perorangan</option>
    </select>
    @endif

    @if($routePrefix === 'admin')
    <div id="pangkalanFilterWrap" class="{{ $showPangkalanFilter ? '' : 'd-none' }}">
    <select name="pangkalan_id" id="filterPangkalanSelect" class="form-select form-select-sm" style="max-width:220px;">
        <option value="">-- Pilih Pangkalan --</option>
        @foreach($pangkalanList as $p)
            <option value="{{ $p->id }}" {{ (string)$filterPangkalan === (string)$p->id ? 'selected' : '' }}>
                {{ $p->kode_pangkalan }} — {{ $p->nama_pangkalan }}
            </option>
        @endforeach
    </select>
    </div>
    @endif

    @if($routePrefix !== 'user')
    <div id="karyawanFilterWrap" class="{{ $showKaryawanFilter ? '' : 'd-none' }}">
    <select name="karyawan_id" id="filterKaryawanSelect" class="form-select form-select-sm" style="max-width:240px;">
        <option value="">-- Pilih Karyawan --</option>
        @foreach($karyawanFilterList as $kfilter)
            <option value="{{ $kfilter->id }}" {{ (string)$filterKaryawan === (string)$kfilter->id ? 'selected' : '' }}>
                {{ $kfilter->kode_karyawan }} — {{ $kfilter->nama_karyawan }}
            </option>
        @endforeach
    </select>
    </div>
    @endif

    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Jenis Output:</label>
    <select name="jenis_laporan" class="form-select form-select-sm" style="max-width:180px;">
        <option value="ringkas" {{ ($jenisLaporan ?? 'ringkas') === 'ringkas' ? 'selected' : '' }}>Ringkas</option>
        <option value="rinci" {{ ($jenisLaporan ?? 'ringkas') === 'rinci' ? 'selected' : '' }} {{ ($reportFormat['show_detail_kompetensi'] ?? true) ? '' : 'disabled' }}>Rinci</option>
    </select>

    @include('components.per-page-select')

    <button type="submit" class="btn btn-sm btn-primary">Terapkan</button>
    @if(request()->hasAny(['tahun_penilaian_id', 'mode', 'pangkalan_id', 'karyawan_id', 'jenis_laporan', 'per_page']))
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
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold">
            <i class="bi bi-table me-2"></i>Rekapitulasi Nilai Pengabdian
            @if($selectedTahunData)
                <span class="text-primary ms-1"> {{ $selectedTahunData->periode_penilaian }}</span>
            @endif
        </span>
        <div class="d-flex gap-2">
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
            <table class="table table-bordered table-hover mb-0 align-middle" style="font-size:.82rem;">
                <thead class="table-dark">
                    @if($isRingkas)
                    <tr>
                        <th class="align-middle text-center" width="36">No</th>
                        <th class="align-middle">Nama Karyawan</th>
                        <th class="align-middle">Pangkalan Job</th>
                        @foreach($kategoriList as $kat)
                        <th class="text-center">
                            {{ $kat->kategori }}
                        </th>
                        @endforeach
                        <th class="align-middle text-center">Nilai Akhir</th>
                        <th class="align-middle text-center">Rating</th>
                    </tr>
                    @else
                    <tr>
                        <th rowspan="2" class="align-middle text-center" width="36">No</th>
                        <th rowspan="2" class="align-middle">Nama Karyawan</th>
                        <th rowspan="2" class="align-middle">Pangkalan Job</th>
                        @foreach($kategoriList as $kat)
                        <th colspan="{{ $kat->kompetensi->count() }}" class="text-center">
                            {{ $kat->kategori }}
                            @if($reportFormat['show_bobot_kategori'] ?? true)
                                <div class="badge bg-info text-dark fw-normal" style="font-size:.7rem;">Bobot {{ $kat->bobot }}%</div>
                            @endif
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
                    @endif
                </thead>
                <tbody>
                    @forelse($karyawanList as $i => $k)
                    @php
                        $rowNo = method_exists($karyawanList, 'firstItem') ? ($karyawanList->firstItem() + $i) : ($i + 1);
                        $kategoriUntukKaryawan = \App\Support\LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $k);
                        $applicableKompetensiIds = \App\Support\LaporanScoreCalculator::kompetensiIdsFromKategori($kategoriUntukKaryawan);
                        $trx = $k->transaksi->keyBy('kompetensi_id');
                        $trxByKompetensi = $k->transaksi
                            ->filter(fn($t) => $t->nilai !== null)
                            ->filter(fn($t) => $applicableKompetensiIds->contains((int) $t->kompetensi_id))
                            ->keyBy('kompetensi_id');

                        $nilaiAkhir = \App\Support\LaporanScoreCalculator::calculate($kategoriUntukKaryawan, $trxByKompetensi, $reportFormat['scoring_method']);
                        $ratingMeta = \App\Support\LaporanScoreCalculator::ratingMeta($nilaiAkhir);
                        $rating = $ratingMeta['label'];
                        $ratingColor = $ratingMeta['color'];
                    @endphp
                    <tr>
                        <td class="text-center">{{ $rowNo }}</td>
                        <td>
                            <div class="fw-semibold">{{ $k->nama_karyawan }}</div>
                            <small class="text-muted">{{ $k->kode_karyawan }}</small>
                        </td>
                        <td style="white-space:nowrap;">
                            {{ $k->pangkalan?->nama_pangkalan ?? '-' }}
                        </td>
                        @if($isRingkas)
                            @foreach($kategoriList as $kat)
                            @php
                                $isApplicableKategori = $kategoriUntukKaryawan->contains(fn($item) => (int) $item->id === (int) $kat->id);
                                $kategoriValues = $kat->kompetensi
                                    ->map(function ($komp) use ($trx) {
                                        $t = $trx->get($komp->id);
                                        return ($t && $t->nilai !== null) ? (float) $t->nilai : null;
                                    })
                                    ->filter(fn($nilai) => $nilai !== null)
                                    ->values();
                                $kategoriAvg = $kategoriValues->isNotEmpty()
                                    ? ($kategoriValues->sum() / $kategoriValues->count())
                                    : null;
                            @endphp
                            <td class="text-center">
                                @if(!$isApplicableKategori)
                                    <span class="text-muted">-</span>
                                @elseif($kategoriAvg !== null)
                                    <span class="fw-semibold">{{ number_format($kategoriAvg, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            @endforeach
                        @else
                        @foreach($kategoriList as $kat)
                            @foreach($kat->kompetensi as $komp)
                            <td class="text-center">
                                @php $t = $trx->get($komp->id); @endphp
                                @if(!$applicableKompetensiIds->contains((int) $komp->id))
                                    <span class="text-muted">-</span>
                                @elseif($t && $t->nilai !== null)
                                    <span class="fw-semibold">{{ number_format($t->nilai, 0) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            @endforeach
                        @endforeach
                        @endif
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
                        <td colspan="{{ $isRingkas ? (3 + $kategoriList->count() + 2) : (3 + $kategoriList->sum(fn($k) => $k->kompetensi->count()) + 2) }}"
                            class="text-center text-muted py-4">
                            Tidak ada data untuk tahun ini.
                        </td>
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

@if($routePrefix !== 'user')
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modeSelect = document.getElementById('modeFilter');
        const pangkalanWrap = document.getElementById('pangkalanFilterWrap');
        const karyawanWrap = document.getElementById('karyawanFilterWrap');
        const pangkalanSelect = document.getElementById('filterPangkalanSelect');
        const karyawanSelect = document.getElementById('filterKaryawanSelect');

        if (!modeSelect) {
            return;
        }

        const toggleFilterVisibility = () => {
            const mode = modeSelect.value;
            const showPangkalan = mode === 'perdireksi';
            const showKaryawan = mode === 'perorangan';

            if (pangkalanWrap) {
                pangkalanWrap.classList.toggle('d-none', !showPangkalan);
            }
            if (karyawanWrap) {
                karyawanWrap.classList.toggle('d-none', !showKaryawan);
            }

            if (pangkalanSelect) {
                pangkalanSelect.disabled = !showPangkalan;
                if (!showPangkalan) {
                    pangkalanSelect.value = '';
                }
            }

            if (karyawanSelect) {
                karyawanSelect.disabled = !showKaryawan;
                if (!showKaryawan) {
                    karyawanSelect.value = '';
                }
            }
        };

        modeSelect.addEventListener('change', toggleFilterVisibility);
        toggleFilterVisibility();
    });
</script>
@endpush
@endif
@endsection

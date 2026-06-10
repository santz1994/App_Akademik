@extends('layouts.app')
@section('title','Laporan Perorangan')
@section('page-title','Laporan Nilai Perorangan')
@section('content')

@php
    $routePrefix = $routePrefix ?? 'admin';
    $karyawanFilterList = $karyawanFilterList ?? collect();
    $selectedTahun = $selectedTahun ?? null;
    $selectedTahunData = $selectedTahunData ?? null;
    $tahunList = $tahunList ?? collect();
    $jenisLaporan = $jenisLaporan ?? 'rinci';
@endphp

@if($routePrefix === 'admin')
<div class="d-flex justify-content-end gap-2 mb-2">
    <a href="{{ route('admin.laporan.format.edit') }}" class="btn btn-sm btn-outline-dark">
        <i class="bi bi-sliders me-1"></i>Atur Format Cetak
    </a>
</div>
@endif

{{-- Filter --}}
<div class="card mb-3">
    <div class="card-body py-2">
<form method="GET" action="{{ route($routePrefix . '.laporan.perorangan') }}" class="d-flex align-items-end gap-2 flex-wrap" id="peroranganFilterForm">
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
    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-person me-1"></i>Karyawan:</label>
    <select name="karyawan_id" class="form-select form-select-sm" style="max-width:280px;">
        <option value="">-- Pilih Karyawan --</option>
        @foreach($karyawanFilterList as $kfilter)
            <option value="{{ $kfilter->id }}" {{ (string)request('karyawan_id') === (string)$kfilter->id ? 'selected' : '' }}>
                {{ $kfilter->kode_karyawan }} — {{ $kfilter->nama_karyawan }}
            </option>
        @endforeach
    </select>
    @endif

    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Jenis:</label>
    <select name="jenis_laporan" class="form-select form-select-sm" style="max-width:150px;">
        <option value="ringkas" {{ $jenisLaporan === 'ringkas' ? 'selected' : '' }}>Ringkas</option>
        <option value="rinci" {{ $jenisLaporan === 'rinci' ? 'selected' : '' }}>Rinci</option>
    </select>

    @if(request()->hasAny(['tahun_penilaian_id', 'karyawan_id', 'jenis_laporan']))
        <a href="{{ route($routePrefix . '.laporan.perorangan') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    @endif
</form>
    <small class="text-muted d-block mt-1">Pilih karyawan untuk melihat detail nilai perorangan.</small>
    </div>
</div>

{{-- Perorangan Detail --}}
@if(!empty($peroranganKaryawan))
@php
    $pk = $peroranganKaryawan;
    $pkKategoriList = $peroranganKategoriList;
    $pkTrx = $peroranganTrxByKompetensi;
    $pkNilaiAkhir = $peroranganNilaiAkhir;
    $pkRatingMeta = $peroranganRatingMeta;
    $pkSetting = $peroranganSetting;
    $pkReportFormat = $peroranganReportFormat;
    $pkPerPangkalanData = $peroranganPerPangkalanData ?? null;
    $pkAllPangkalan = $peroranganAllPangkalan ?? collect();
    $kinerjaKategori = $pkKategoriList->filter(fn($k) => strtolower((string) $k->jenis) === 'kinerja')->values();
    $kegiatanKategori = $pkKategoriList->filter(fn($k) => strtolower((string) $k->jenis) === 'kegiatan')->values();
    $isPeroranganRingkas = $jenisLaporan === 'ringkas';
@endphp
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center py-2 flex-wrap gap-2">
        <span class="fw-semibold">
            <i class="bi bi-person-lines-fill me-2"></i>Detail Nilai Perorangan ({{ $isPeroranganRingkas ? 'Ringkas' : 'Rinci' }})
            <span class="text-primary ms-1">{{ $pk->nama_karyawan }}</span>
        </span>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route($routePrefix . '.laporan.perorangan-pdf', ['karyawan_id' => $pk->id, 'tahun_penilaian_id' => $selectedTahun, 'jenis_laporan' => 'ringkas']) }}"
               target="_blank" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-filetype-pdf me-1"></i>PDF Ringkas
            </a>
            <a href="{{ route($routePrefix . '.laporan.perorangan-pdf', ['karyawan_id' => $pk->id, 'tahun_penilaian_id' => $selectedTahun, 'jenis_laporan' => 'rinci']) }}"
               target="_blank" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-filetype-pdf me-1"></i>PDF Rinci
            </a>
        </div>
    </div>
    <div class="card-body">
        {{-- Info Karyawan --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0" style="font-size:.85rem;">
                    <tr><td class="text-muted" width="130">Nama Karyawan</td><td>: <strong>{{ $pk->nama_karyawan }}</strong></td></tr>
                    <tr>
                        <td class="text-muted">Pangkalan Job</td>
                        <td>:
                            @php
                                $displayPangkalans = $pkAllPangkalan->isNotEmpty()
                                    ? $pkAllPangkalan->filter(fn($p) => !(bool) $p->is_wajib)
                                    : collect();
                                if ($displayPangkalans->isEmpty() && $pk->pangkalan) {
                                    $displayPangkalans = collect([$pk->pangkalan]);
                                }
                            @endphp
                            @if($displayPangkalans->isNotEmpty())
                                {{ $displayPangkalans->map(fn($p) => $p->nama_pangkalan)->implode(', ') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted">Nomor Induk</td><td>: {{ $pk->nomor_induk ?: '-' }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>: <span class="badge {{ $pk->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $pk->is_active ? 'Aktif' : 'Tidak Aktif' }}</span></td></tr>
                </table>
            </div>
        </div>

        @if($isPeroranganRingkas)
        {{-- RINGKAS: Kinerja per pangkalan, Kegiatan per kategori --}}
        @if($pkPerPangkalanData && count($pkPerPangkalanData['perPangkalan']) > 0)
        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-briefcase me-1"></i>Nilai Kinerja</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm mb-0" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="text-center">No</th>
                        <th>Pangkalan</th>
                        <th width="120" class="text-center">Rata-rata Kinerja</th>
                        <th width="120" class="text-center">Rata-rata Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pkPerPangkalanData['perPangkalan'] as $idx => $ppData)
                    @php
                        $kegiatanAvgPk = null;
                        if (isset($ppData['kegiatanDetails']) && count($ppData['kegiatanDetails']) > 0) {
                            $kv = collect($ppData['kegiatanDetails'])->pluck('average')->filter(fn($v) => $v !== null)->values();
                            $kegiatanAvgPk = $kv->isNotEmpty() ? $kv->sum() / $kv->count() : null;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            @if($ppData['pangkalan'])
                                {{ $ppData['pangkalan']->nama_pangkalan }}
                            @else
                                Pangkalan #{{ $ppData['pangkalan_id'] }}
                            @endif
                        </td>
                        <td class="text-center fw-bold">
                            {{ $ppData['kinerjaAvg'] !== null ? number_format($ppData['kinerjaAvg'], 2) : '-' }}
                        </td>
                        <td class="text-center fw-bold">
                            {{ $kegiatanAvgPk !== null ? number_format($kegiatanAvgPk, 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @else
        {{-- RINCI: Per-pangkalan breakdown for multi-pangkalan, flat for single --}}
        @if($kinerjaKategori->isNotEmpty())
        @if($pkPerPangkalanData && count($pkPerPangkalanData['perPangkalan']) > 1)
        {{-- Multi-pangkalan: show per-pangkalan sections --}}
        @foreach($pkPerPangkalanData['perPangkalan'] as $ppData)
        @php
            // Use per-pangkalan transaksi only (no fallback to global data to prevent cross-pangkalan data leak)
            $pangkalanTrx = $peroranganTrxByPangkalan[$ppData['pangkalan_id']] ?? collect();
        @endphp
        <div class="card mb-3 border-primary">
            <div class="card-header py-2 bg-primary bg-opacity-10 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-primary">
                    <i class="bi bi-building me-1"></i>
                    @if($ppData['pangkalan'])
                        {{ $ppData['pangkalan']->nama_pangkalan }}
                    @else
                        Pangkalan #{{ $ppData['pangkalan_id'] }}
                    @endif
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0" style="font-size:.82rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Indikator Kompetensi</th>
                                <th width="80" class="text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($ppData['kategoriDetails'] as $kd)
                            @foreach($kd['kategori']->kompetensi as $komp)
                                @php
                                    $t = $pangkalanTrx->get((int) $komp->id . ':' . (int) $kd['kategori']->id);
                                    $nilai = ($t && $t->nilai !== null) ? (float) $t->nilai : null;
                                @endphp
                                <tr>
                                    <td>{{ $komp->kompetensi }}</td>
                                    <td class="text-center fw-semibold">{{ $nilai !== null ? number_format($nilai, 0) : '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-primary">
                                <td class="fw-bold">Rata-rata {{ $kd['kategori']->kategori }}</td>
                                <td class="text-center fw-bold">{{ $kd['average'] !== null ? number_format($kd['average'], 2) : '-' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table class="table table-bordered table-sm mb-0" style="font-size:.82rem;">
                        <tbody>
                            <tr class="table-primary">
                                <td class="fw-bold">Rata-Rata {{ $ppData['pangkalan'] ? $ppData['pangkalan']->nama_pangkalan : 'Pangkalan #' . $ppData['pangkalan_id'] }}</td>
                                <td class="text-center fw-bold">{{ $ppData['kinerjaAvg'] !== null ? number_format($ppData['kinerjaAvg'], 2) : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
        @else
        {{-- Single pangkalan: flat layout --}}
        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-briefcase me-1"></i>Nilai Kinerja</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm mb-0" style="font-size:.82rem;">
                <thead class="table-light">
                    <tr>
                        <th>Indikator Kompetensi</th>
                        <th width="80" class="text-center">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($kinerjaKategori as $kat)
                    @php $kategoriNilai = []; @endphp
                    @foreach($kat->kompetensi as $komp)
                        @php
                            $t = $pkTrx->get((int) $komp->id . ':' . (int) $kat->id);
                            $nilai = ($t && $t->nilai !== null) ? (float) $t->nilai : null;
                            if ($nilai !== null) { $kategoriNilai[] = $nilai; }
                        @endphp
                        <tr>
                            <td>{{ $komp->kompetensi }}</td>
                            <td class="text-center fw-semibold">{{ $nilai !== null ? number_format($nilai, 0) : '-' }}</td>
                        </tr>
                    @endforeach
                    @if(count($kategoriNilai) > 0)
                    <tr class="table-primary">
                        <td class="fw-bold">Rata-rata {{ $kat->kategori }}</td>
                        <td class="text-center fw-bold">{{ number_format(array_sum($kategoriNilai) / count($kategoriNilai), 2) }}</td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @endif

        {{-- Kegiatan section: only show for single pangkalan (multi-pangkalan shows kegiatan per-pangkalan above) --}}
        @if($kegiatanKategori->isNotEmpty() && (!$pkPerPangkalanData || count($pkPerPangkalanData['perPangkalan'] ?? []) <= 1))
        <h6 class="fw-bold text-success mb-2"><i class="bi bi-clipboard-check me-1"></i>Nilai Kegiatan</h6>
        @foreach($kegiatanKategori as $kat)
        <div class="mb-2">
            <div class="d-flex align-items-center mb-1" style="font-size:.85rem;">
                <strong>{{ $kat->kategori }}</strong>
                @if(false && $kat->is_wajib)
                    <span class="badge bg-danger ms-2">Wajib</span>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" style="font-size:.82rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Indikator Kompetensi</th>
                            <th width="80" class="text-center">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $kategoriNilai = []; @endphp
                    @foreach($kat->kompetensi as $komp)
                        @php
                            $compositeKey = (int) $komp->id . ':' . (int) $kat->id;
                            $t = $pkTrx->get($compositeKey) ?? $pkTrx->get($komp->id);
                            $nilai = ($t && $t->nilai !== null && (int) $t->kategori_kinerja_id === (int) $kat->id) ? (float) $t->nilai : null;
                            if ($nilai !== null) { $kategoriNilai[] = $nilai; }
                        @endphp
                        <tr>
                            <td>{{ $komp->kompetensi }}</td>
                            <td class="text-center fw-semibold">{{ $nilai !== null ? number_format($nilai, 0) : '-' }}</td>
                        </tr>
                    @endforeach
                    <tr class="table-success">
                        <td class="fw-bold">Rata-rata {{ $kat->kategori }}</td>
                        <td class="text-center fw-bold">{{ count($kategoriNilai) > 0 ? number_format(array_sum($kategoriNilai) / count($kategoriNilai), 2) : '-' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
        @endif
        @endif

        {{-- Summary --}}
        <div class="row g-3">
            @php
                $avgKinerja = $pkPerPangkalanData['kinerjaFinal'] ?? null;
                $avgKegiatan = $pkPerPangkalanData['kegiatanAvg'] ?? null;
            @endphp
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center py-3">
                    <div style="font-size:.75rem; text-transform:uppercase;">Nilai Akhir Kinerja</div>
                    <div class="fs-3 fw-bold">{{ $avgKinerja !== null ? number_format($avgKinerja, 2) : '-' }}</div>
                    @if($pkPerPangkalanData && count($pkPerPangkalanData['perPangkalan']) > 1)
                        <div style="font-size:.7rem; opacity:.8;">Rata-rata {{ count($pkPerPangkalanData['perPangkalan']) }} pangkalan</div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center py-3">
                    <div style="font-size:.75rem; text-transform:uppercase;">Nilai Akhir Kegiatan</div>
                    <div class="fs-3 fw-bold">{{ $avgKegiatan !== null ? number_format($avgKegiatan, 2) : '-' }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white text-center py-3">
                    <div style="font-size:.75rem; text-transform:uppercase;">Nilai Akhir ({{ $pkReportFormat['score_weight_kinerja'] ?? 70 }}% Kinerja + {{ $pkReportFormat['score_weight_kegiatan'] ?? 30 }}% Kegiatan)</div>
                    <div class="fs-3 fw-bold">{{ $pkNilaiAkhir !== null ? number_format($pkNilaiAkhir, 2) : '-' }}</div>
                    @if($pkNilaiAkhir !== null)
                        <div><span class="badge bg-{{ $pkRatingMeta['color'] }}">{{ $pkRatingMeta['label'] }}</span></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Keterangan Reward & Punishment --}}
        @php
            $rpInfo = \App\Support\LaporanScoreCalculator::getRewardPunishmentInfo($pkNilaiAkhir);
        @endphp
        @if($rpInfo && $rpInfo['items']->isNotEmpty())
        <div class="mt-3">
            <div class="alert {{ $rpInfo['grade'] === 'C' || $rpInfo['grade'] === 'D' ? 'alert-danger' : 'alert-success' }} mb-0">
                <h6 class="fw-bold mb-2">
                    @if($rpInfo['grade'] === 'C' || $rpInfo['grade'] === 'D')
                        <i class="bi bi-exclamation-triangle me-1"></i>KETERANGAN HUKUMAN
                    @else
                        <i class="bi bi-check-circle me-1"></i>KETERANGAN REWARD
                    @endif
                </h6>
                @foreach($rpInfo['items'] as $rpItem)
                <div style="font-size:.875rem;" class="mb-1">
                    @if(($rpItem->tipe ?? '') === 'punishment')
                        <strong>{{ $rpItem->nama ?? 'Hukuman' }}:</strong>
                        Karyawan yang mendapatkan nilai akhir <strong>Grade {{ $rpInfo['grade'] }}</strong>
                        mendapatkan hukuman berupa
                        @if(isset($rpItem->jumlah) && $rpItem->jumlah > 0)
                            <strong>{{ $rpItem->jumlah }} {{ $rpItem->satuan ?? '' }}</strong>.
                        @else
                            {{ $rpItem->deskripsi ?? '' }}
                        @endif
                    @else
                        <strong>{{ $rpItem->nama ?? 'Reward' }}:</strong>
                        {{ $rpItem->deskripsi ?? '' }}
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@else
<div class="text-center py-5 text-muted">
    <i class="bi bi-person-lines-fill" style="font-size:3rem;"></i>
    <p class="mt-2">Pilih tahun ajaran dan karyawan untuk melihat laporan nilai perorangan.</p>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('peroranganFilterForm');
    if (form) {
        form.querySelectorAll('select').forEach(function (sel) {
            sel.addEventListener('change', function () { form.submit(); });
        });
    }
});
</script>
@endpush
@endsection

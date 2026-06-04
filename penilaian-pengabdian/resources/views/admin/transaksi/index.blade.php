@extends('layouts.app')
@section('title','Transaksi Penilaian')
@section('page-title','Transaksi Penilaian')
@section('content')

@php
    $routePrefix = $routePrefix ?? 'admin';
    $isKepalaView = $isKepalaView ?? false;
    $scoreMethod = $scoreMethod ?? 'weighted_kinerja_kegiatan';
    $scoreWeightKinerja = (float) ($scoreWeightKinerja ?? 70);
    $scoreWeightKegiatan = (float) ($scoreWeightKegiatan ?? 30);
    $pendingUnlockCount = $pendingUnlockCount ?? 0;
    $canBatchUnlock = !$isKepalaView && (bool) $selectedTahun;
@endphp

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show py-2" role="alert">
    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filter Tahun --}}
<form method="GET" action="{{ route($routePrefix . '.transaksi.index') }}" class="mb-3 d-flex align-items-end gap-2 flex-wrap">
    <div>
        <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Cari Karyawan:</label>
        <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control form-control-sm" style="min-width:220px;"
               placeholder="Nama atau kode karyawan">
    </div>

    @if(!$isKepalaView)
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
        <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Status Karyawan:</label>
        <select name="status_aktif" class="form-select form-select-sm" style="min-width:160px;">
            <option value="">-- Semua --</option>
            <option value="aktif" {{ ($filterStatusAktif ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ ($filterStatusAktif ?? '') === 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
    </div>

    <div>
        <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;">Status Lock:</label>
        <select name="status_lock" class="form-select form-select-sm" style="min-width:160px;">
            <option value="">-- Semua --</option>
            <option value="locked" {{ ($filterStatusLock ?? '') === 'locked' ? 'selected' : '' }}>Locked</option>
            <option value="unlocked" {{ ($filterStatusLock ?? '') === 'unlocked' ? 'selected' : '' }}>Unlocked</option>
        </select>
    </div>
    @endif

    <div>
    <label class="fw-semibold me-1" style="font-size:.85rem; white-space:nowrap;"><i class="bi bi-funnel me-1"></i>Tahun Ajaran:</label>
    <select name="tahun_penilaian_id" class="form-select form-select-sm" style="min-width:200px;">
        <option value="">-- Semua Tahun --</option>
        @foreach($tahunList as $t)
        <option value="{{ $t->id }}" {{ $selectedTahun == $t->id ? 'selected' : '' }}>
            {{ $t->periode_penilaian }} {{ $t->is_active ? '(Aktif)' : '' }}
        </option>
        @endforeach
    </select>
    </div>

    @include('components.per-page-select')

    @if(request()->hasAny(['q', 'pangkalan_id', 'status_aktif', 'status_lock', 'tahun_penilaian_id', 'per_page']))
        <a href="{{ route($routePrefix . '.transaksi.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-receipt me-2"></i>Input & Rekap Penilaian</span>
        <div class="d-flex gap-2">
            @if(!$isKepalaView)
                <form method="POST" action="{{ route('admin.transaksi.import') }}" enctype="multipart/form-data" class="d-flex gap-1 align-items-center">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm" accept=".xlsx,.xls,.csv" style="max-width:180px;" required>
                    <button type="submit" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-upload me-1"></i>Import
                    </button>
                </form>
                <a href="{{ route('admin.transaksi.unlock-requests') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-unlock me-1"></i>Request Unlock
                    @if($pendingUnlockCount > 0)
                        <span class="badge bg-danger ms-1">{{ $pendingUnlockCount }}</span>
                    @endif
                </a>
                <form id="batchUnlockForm" method="POST" action="{{ route('admin.transaksi.batch-unlock') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun }}">
                    <div id="batchUnlockIdsContainer"></div>
                    <button type="button" id="batchUnlockBtn" class="btn btn-outline-danger btn-sm" {{ $canBatchUnlock ? '' : 'disabled' }}>
                        <i class="bi bi-unlock-fill me-1"></i>Batch Unlock
                    </button>
                </form>
            @endif
            <a href="{{ route($routePrefix . '.transaksi.create', $selectedTahun ? ['tahun_penilaian_id' => $selectedTahun] : []) }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square me-1"></i>Input Nilai
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.84rem;">
                <thead class="table-light">
                    <tr>
                        @if(!$isKepalaView)
                        <th width="36" class="text-center">
                            <input type="checkbox" id="selectAllUnlock" {{ $canBatchUnlock ? '' : 'disabled' }}>
                        </th>
                        @endif
                        <th width="36">No</th>
                        <th>Karyawan</th>
                        <th>Pangkalan Job</th>
                        <th width="100" class="text-center">Terisi</th>
                        <th width="100" class="text-center">Nilai Akhir</th>
                        <th width="140" class="text-center">Rating</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawanList as $i => $k)
                    @php
                        $trx      = $k->transaksi;
                        $kategoriForKaryawan = \App\Support\LaporanScoreCalculator::resolveKategoriUntukKaryawan(($kategoriListForScore ?? collect()), $k);
                        $applicableKompetensiIds = \App\Support\LaporanScoreCalculator::kompetensiIdsFromKategori($kategoriForKaryawan);
                        $scoredTrx = $trx
                            ->filter(fn($t) => $t->nilai !== null)
                            ->filter(fn($t) => $applicableKompetensiIds->contains((int) $t->kompetensi_id));
                        $terisi   = $scoredTrx->count();
                        $totalKompetensiAktif = $applicableKompetensiIds->count();
                        $progress = $totalKompetensiAktif > 0 ? round($terisi / $totalKompetensiAktif * 100) : 0;
                        $lockState = $k->penilaianLocks->first() ?? null;
                        $isLocked  = $lockState ? (bool) $lockState->is_locked : false;

                        $trxByKompetensi = $scoredTrx->keyBy('kompetensi_id');
                        $nilaiAkhir = \App\Support\LaporanScoreCalculator::calculate(
                            $kategoriForKaryawan,
                            $trxByKompetensi,
                            $scoreMethod,
                            [
                                'bobot_kinerja' => $scoreWeightKinerja,
                                'bobot_kegiatan' => $scoreWeightKegiatan,
                            ]
                        );
                        $ratingMeta = \App\Support\LaporanScoreCalculator::ratingMeta($nilaiAkhir);
                        $rating = $ratingMeta['label'];
                        $ratingColor = $ratingMeta['color'];
                    @endphp
                    <tr>
                        @if(!$isKepalaView)
                        <td class="text-center">
                            <input type="checkbox"
                                   class="batch-unlock-check"
                                   value="{{ $k->id }}"
                                   {{ $canBatchUnlock ? '' : 'disabled' }}>
                        </td>
                        @endif
                        <td>{{ $karyawanList->firstItem() + $i }}</td>
                        <td>
                            <div class="fw-semibold">{{ $k->nama_karyawan }}</div>
                            <small class="text-muted">{{ $k->kode_karyawan }}</small>
                        </td>
                        <td>
                            @if($k->pangkalan)
                                <small class="badge bg-light text-dark border">{{ $k->pangkalan->kode_pangkalan }}</small>
                                {{ $k->pangkalan->nama_pangkalan }}
                            @else <span class="text-muted">-</span> @endif
                        </td>
                        <td class="text-center">
                            <div class="progress mb-1" style="height:5px;">
                                <div class="progress-bar {{ $terisi == $totalKompetensiAktif ? 'bg-success' : 'bg-primary' }}"
                                     style="width:{{ $progress }}%"></div>
                            </div>
                            <span class="{{ $terisi == $totalKompetensiAktif ? 'text-success fw-semibold' : '' }}">
                                {{ $terisi }}/{{ $totalKompetensiAktif }}
                            </span>
                        </td>
                        <td class="text-center fw-bold fs-6">
                            {{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}
                        </td>
                        <td class="text-center">
                            @if($nilaiAkhir !== null)
                                <span class="badge bg-{{ $ratingColor }}">{{ $rating }}</span>
                            @else <span class="text-muted">Belum dinilai</span> @endif
                        </td>
                        <td>
                            <a href="{{ route($routePrefix . '.transaksi.create', ['karyawan_id' => $k->id, 'tahun_penilaian_id' => $selectedTahun]) }}"
                               class="btn btn-warning btn-action {{ $isLocked ? 'disabled' : '' }}" title="Input/Edit Nilai">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($isKepalaView && $terisi > 0)
                                @if(!$isLocked)
                                <form method="POST" action="{{ route('kepala.transaksi.submit-final') }}" class="d-inline"
                                      onsubmit="return confirm('Submit final dan kunci nilai untuk {{ addslashes($k->nama_karyawan) }}?')">
                                    @csrf
                                    <input type="hidden" name="karyawan_id" value="{{ $k->id }}">
                                    <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun }}">
                                    <button class="btn btn-success btn-action" title="Submit Final">
                                        <i class="bi bi-check2-circle"></i>
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('kepala.transaksi.request-unlock') }}" class="d-inline"
                                      onsubmit="
                                        const alasan = prompt('Alasan pengajuan unlock nilai:');
                                        if (!alasan) return false;
                                        this.querySelector('input[name=alasan]').value = alasan;
                                        return true;
                                      ">
                                    @csrf
                                    <input type="hidden" name="karyawan_id" value="{{ $k->id }}">
                                    <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun }}">
                                    <input type="hidden" name="alasan" value="">
                                    <button class="btn btn-secondary btn-action" title="Ajukan Unlock">
                                        <i class="bi bi-unlock"></i>
                                    </button>
                                </form>
                                @endif
                            @endif

                                                        @if(!$isKepalaView)
                                                        @if($selectedTahun)
                                                        @if($isLocked)
                            <form method="POST" action="{{ route('admin.transaksi.unlock') }}" class="d-inline"
                                  onsubmit="return confirm('Buka lock nilai untuk {{ addslashes($k->nama_karyawan) }}?')">
                                @csrf
                                <input type="hidden" name="karyawan_id" value="{{ $k->id }}">
                                <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun }}">
                                <button class="btn btn-secondary btn-action" title="Unlock">
                                    <i class="bi bi-unlock"></i>
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.transaksi.lock') }}" class="d-inline"
                                  onsubmit="return confirm('Kunci nilai untuk {{ addslashes($k->nama_karyawan) }}?')">
                                @csrf
                                <input type="hidden" name="karyawan_id" value="{{ $k->id }}">
                                <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun }}">
                                <button class="btn btn-success btn-action" title="Lock">
                                    <i class="bi bi-lock"></i>
                                </button>
                            </form>
                            @endif
                            @endif

                                                        @if($terisi > 0)
                            <form method="POST"
                                  action="{{ route('admin.transaksi.hapus-karyawan', $k) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus semua penilaian {{ addslashes($k->nama_karyawan) }}?')">
                                @csrf @method('DELETE')
                                <input type="hidden" name="tahun_penilaian_id" value="{{ $selectedTahun }}">
                                <button class="btn btn-danger btn-action" title="Hapus Semua">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                            @endif

                            @if($isKepalaView)
                                <small class="d-block mt-1 {{ $isLocked ? 'text-danger' : 'text-success' }}" style="font-size:.68rem;">
                                    {{ $isLocked ? 'Locked' : 'Editable' }}
                                </small>
                            @else
                                <small class="d-block mt-1 {{ $isLocked ? 'text-danger' : 'text-success' }}" style="font-size:.68rem;">
                                    {{ $isLocked ? 'Terkunci (sheet)' : 'Bisa diedit (indikator terisi terkunci otomatis)' }}
                                </small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="{{ $isKepalaView ? 7 : 8 }}" class="text-center text-muted py-4">
                        Belum ada karyawan untuk tahun ini.
                        <a href="{{ route($routePrefix . '.transaksi.create', $selectedTahun ? ['tahun_penilaian_id' => $selectedTahun] : []) }}">Input nilai sekarang</a>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($karyawanList->hasPages())
    <div class="card-footer">{{ $karyawanList->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

@if(!$isKepalaView)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-submit search/filter form
        const filterForm = document.querySelector('form.mb-3');
        if (filterForm) {
            let debounceTimer;
            const textInput = filterForm.querySelector('input[name="q"]');
            const selects = filterForm.querySelectorAll('select');

            if (textInput) {
                textInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () { filterForm.submit(); }, 500);
                });
            }

            selects.forEach(function (sel) {
                sel.addEventListener('change', function () { filterForm.submit(); });
            });
        }

        // Batch unlock
        const selectAll = document.getElementById('selectAllUnlock');
        const checks = Array.from(document.querySelectorAll('.batch-unlock-check'));
        const batchBtn = document.getElementById('batchUnlockBtn');
        const batchForm = document.getElementById('batchUnlockForm');
        const idsContainer = document.getElementById('batchUnlockIdsContainer');

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checks.forEach(function (item) {
                    if (!item.disabled) {
                        item.checked = selectAll.checked;
                    }
                });
            });
        }

        if (batchBtn && batchForm && idsContainer) {
            batchBtn.addEventListener('click', function () {
                const selected = checks.filter(function (item) {
                    return item.checked && !item.disabled;
                });

                if (selected.length === 0) {
                    alert('Pilih minimal satu karyawan untuk batch unlock.');
                    return;
                }

                if (!confirm('Lakukan batch unlock untuk karyawan terpilih?')) {
                    return;
                }

                idsContainer.innerHTML = '';
                selected.forEach(function (item) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'karyawan_ids[]';
                    input.value = item.value;
                    idsContainer.appendChild(input);
                });

                batchForm.submit();
            });
        }
    });
</script>
@endpush
@endif
@endsection

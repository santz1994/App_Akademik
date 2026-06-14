@extends('layouts.app')
@section('title','Transaksi Penilaian')
@section('page-title','Transaksi Penilaian')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show py-2" role="alert">
    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filter Tahun --}}
<form method="GET" action="{{ route('admin.transaksi.index') }}" class="mb-3 d-flex align-items-center gap-2">
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
        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    @endif
</form>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold"><i class="bi bi-receipt me-2"></i>Input & Rekap Penilaian</span>
        <a href="{{ route('admin.transaksi.create', $selectedTahun ? ['tahun_penilaian_id' => $selectedTahun] : []) }}"
           class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-square me-1"></i>Input Nilai
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.84rem;">
                <thead class="table-light">
                    <tr>
                        <th width="36">No</th>
                        <th>Karyawan</th>
                        <th>Pangkalan Job</th>
                        <th width="80" class="text-center">Terisi</th>
                        <th width="90" class="text-center">Nilai Akhir</th>
                        <th width="120" class="text-center">Rating</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawanList as $i => $k)
                    @php
                        $trx      = $k->transaksi;
                        $terisi   = $trx->count();
                        $progress = $totalKompetensi > 0 ? round($terisi / $totalKompetensi * 100) : 0;

                        // Weighted score: for each kategori, avg(nilai) Ã— bobot; sum / totalBobot
                        $nilaiAkhir = null;
                        if ($terisi > 0) {
                            $grouped = $trx->filter(fn($t) => $t->nilai !== null)
                                          ->groupBy(fn($t) => $t->kompetensi?->kategori_kinerja_id);
                            $weighted = 0; $totalBobot = 0;
                            foreach ($grouped as $items) {
                                $bobot = $items->first()->kompetensi?->kategoriKinerja?->bobot ?? 0;
                                $avg   = $items->avg('nilai');
                                $weighted   += ($avg * $bobot);
                                $totalBobot += $bobot;
                            }
                            if ($totalBobot > 0) $nilaiAkhir = $weighted / $totalBobot;
                        }

                        $rating = '-'; $ratingColor = 'secondary';
                        if ($nilaiAkhir !== null) {
                            if      ($nilaiAkhir >= 90) { $rating = 'A” Sangat Baik';   $ratingColor = 'success'; }
                            elseif  ($nilaiAkhir >= 80) { $rating = 'B” Baik';           $ratingColor = 'primary'; }
                            elseif  ($nilaiAkhir >= 70) { $rating = 'C” Cukup';          $ratingColor = 'warning'; }
                            elseif  ($nilaiAkhir >= 60) { $rating = 'D” Kurang';         $ratingColor = 'danger'; }
                            else                        { $rating = 'E” Sangat Kurang';  $ratingColor = 'dark'; }
                        }
                    @endphp
                    <tr>
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
                                <div class="progress-bar {{ $terisi == $totalKompetensi ? 'bg-success' : 'bg-primary' }}"
                                     style="width:{{ $progress }}%"></div>
                            </div>
                            <span class="{{ $terisi == $totalKompetensi ? 'text-success fw-semibold' : '' }}">
                                {{ $terisi }}/{{ $totalKompetensi }}
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
                            <a href="{{ route('admin.transaksi.create', ['karyawan_id' => $k->id, 'tahun_penilaian_id' => $selectedTahun]) }}"
                               class="btn btn-warning btn-action" title="Input/Edit Nilai">
                                <i class="bi bi-pencil"></i>
                            </a>
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
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">
                        Belum ada karyawan untuk tahun ini.
                        <a href="{{ route('admin.transaksi.create', $selectedTahun ? ['tahun_penilaian_id' => $selectedTahun] : []) }}">Input nilai sekarang</a>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($karyawanList->hasPages())
    <div class="card-footer">{{ $karyawanList->links() }}</div>
    @endif
</div>
@endsection

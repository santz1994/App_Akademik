@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page-title','Dashboard')
@section('content')

<div class="row g-3 mb-3">
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body p-3">
                <div class="text-muted" style="font-size:.78rem;">Tahun Penilaian Aktif</div>
                <div class="fs-5 fw-bold">{{ $stats['tahun_aktif'] }}</div>
                <div class="mt-2 text-muted" style="font-size:.78rem;">
                    Total data nilai tahun aktif: <strong>{{ $stats['total_penilaian_tahun_aktif'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body p-3">
                <div class="text-muted" style="font-size:.78rem;">Progress Penilaian Tahun Aktif</div>
                <div class="fs-5 fw-bold">{{ $stats['progres_dinilai_tahun_aktif'] }}%</div>
                <div class="progress mt-2" style="height:8px;">
                    <div class="progress-bar bg-success" style="width: {{ $stats['progres_dinilai_tahun_aktif'] }}%;"></div>
                </div>
                <div class="mt-2 text-muted" style="font-size:.78rem;">
                    {{ $stats['sudah_dinilai_tahun_aktif'] }} dari {{ $stats['total_karyawan_aktif'] }} karyawan aktif sudah dinilai
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body p-3">
                <div class="text-muted" style="font-size:.78rem;">Antrian & Lock Status</div>
                <div class="d-flex justify-content-between mt-1">
                    <span>Pending Unlock</span>
                    <span class="badge bg-warning text-dark">{{ $stats['pending_unlock'] }}</span>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span>Data Terkunci</span>
                    <span class="badge bg-danger">{{ $stats['locked_tahun_aktif'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body p-3">
                <div class="text-muted" style="font-size:.78rem;">Rata-rata Nilai Tahun Aktif</div>
                <div class="fs-5 fw-bold">{{ $stats['rata_nilai_tahun_aktif'] !== null ? number_format($stats['rata_nilai_tahun_aktif'], 2) : '-' }}</div>
                <div class="mt-2 text-muted" style="font-size:.78rem;">
                    Berdasarkan seluruh entri transaksi di tahun aktif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body py-3">
                <h6 class="fw-bold mb-2"><i class="bi bi-lightning-charge me-2 text-primary"></i>Aksi Cepat</h6>
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <a href="{{ route('admin.transaksi.index') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-receipt me-1"></i>Kelola Penilaian
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-bar-chart-line me-1"></i>Lihat Laporan
                    </a>
                    <a href="{{ route('admin.laporan.format.edit') }}" class="btn btn-sm btn-outline-dark">
                        <i class="bi bi-sliders me-1"></i>Atur Format Laporan
                    </a>
                    <a href="{{ route('admin.transaksi.unlock-requests') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-unlock me-1"></i>Review Unlock
                    </a>
                </div>

                <div class="row g-2">
                    <div class="col-sm-4">
                        <div class="border rounded p-2 bg-light h-100">
                            <div class="text-muted" style="font-size:.74rem;">Karyawan</div>
                            <div class="fw-semibold">{{ $stats['total_karyawan'] }}</div>
                            <small class="text-success">Aktif {{ $stats['total_karyawan_aktif'] }}</small>
                            <small class="text-muted d-block">Nonaktif {{ $stats['total_karyawan_nonaktif'] }}</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-2 bg-light h-100">
                            <div class="text-muted" style="font-size:.74rem;">Struktur Data</div>
                            <div class="fw-semibold">{{ $stats['total_kategori'] }} Kategori</div>
                            <small class="text-muted d-block">{{ $stats['total_kompetensi'] }} Kompetensi</small>
                            <small class="text-muted d-block">{{ $stats['total_pangkalan'] }} Pangkalan</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="border rounded p-2 bg-light h-100">
                            <div class="text-muted" style="font-size:.74rem;">Kelengkapan Nilai</div>
                            <div class="fw-semibold">{{ $stats['sudah_dinilai'] }} Sudah Dinilai</div>
                            <small class="text-muted d-block">Total transaksi: {{ $stats['total_penilaian'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body py-3">
                <h6 class="fw-bold mb-2"><i class="bi bi-people me-2 text-primary"></i>Distribusi User</h6>
                <div class="d-flex justify-content-between border-bottom py-1">
                    <span>Administrator</span>
                    <strong>{{ $stats['total_admin'] }}</strong>
                </div>
                <div class="d-flex justify-content-between border-bottom py-1">
                    <span>User Biasa</span>
                    <strong>{{ $stats['total_user_biasa'] }}</strong>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>Kepala Pimpinan Pos</span>
                    <strong>{{ $stats['total_user_kepala'] }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header py-2 fw-semibold">
        <i class="bi bi-building me-2"></i>Ringkasan Pangkalan (Top 6)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Pangkalan</th>
                        <th class="text-center">Karyawan Aktif</th>
                        <th class="text-center">Total Karyawan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['top_pangkalan'] as $pangkalan)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $pangkalan->kode_pangkalan }}</span></td>
                        <td>{{ $pangkalan->nama_pangkalan }}</td>
                        <td class="text-center">{{ $pangkalan->karyawan_aktif_count }}</td>
                        <td class="text-center">{{ $pangkalan->karyawan_count }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Belum ada data pangkalan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
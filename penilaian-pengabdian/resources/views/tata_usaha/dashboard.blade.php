@extends('layouts.app')
@section('title', 'Dashboard Tata Usaha')
@section('page-title', 'Dashboard Tata Usaha')
@section('content')

<div class="card mb-4">
    <div class="card-body py-3">
        <h6 class="fw-bold mb-1"><i class="bi bi-hand-wave text-warning me-2"></i>Selamat datang, {{ auth()->user()->name }}!</h6>
        <p class="mb-0 text-muted" style="font-size:.875rem;">Anda login sebagai <span class="badge bg-info text-white">Tata Usaha</span>. Anda dapat melihat laporan penilaian karyawan dan membuka lock penilaian.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center p-3">
            <i class="bi bi-building fs-1 text-primary mb-2"></i>
            <div class="fw-semibold text-muted" style="font-size:.75rem;">Pangkalan</div>
            <div class="fs-5 fw-bold">{{ $stats['pangkalan'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <i class="bi bi-calendar3 fs-1 text-success mb-2"></i>
            <div class="fw-semibold text-muted" style="font-size:.75rem;">Tahun Aktif</div>
            <div class="fs-5 fw-bold">{{ $stats['tahun_aktif'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <i class="bi bi-people fs-1 text-info mb-2"></i>
            <div class="fw-semibold text-muted" style="font-size:.75rem;">Total Karyawan</div>
            <div class="fs-5 fw-bold">{{ $stats['total_karyawan'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <i class="bi bi-lock fs-1 text-danger mb-2"></i>
            <div class="fw-semibold text-muted" style="font-size:.75rem;">Locked</div>
            <div class="fs-5 fw-bold">{{ $stats['locked_count'] }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2 fw-semibold"><i class="bi bi-bar-chart-line me-2"></i>Laporan Penilaian</div>
            <div class="card-body">
                <p class="text-muted" style="font-size:.875rem;">Lihat laporan penilaian karyawan dalam pangkalan Anda.</p>
                <a href="{{ route('tata-usaha.laporan.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-bar-chart-line me-1"></i>Laporan Keseluruhan
                </a>
                <a href="{{ route('tata-usaha.laporan.perorangan') }}" class="btn btn-outline-primary btn-sm ms-1">
                    <i class="bi bi-person-lines-fill me-1"></i>Laporan Perorangan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header py-2 fw-semibold"><i class="bi bi-unlock me-2"></i>Unlock Penilaian</div>
            <div class="card-body">
                <p class="text-muted" style="font-size:.875rem;">Buka lock penilaian karyawan jika terjadi kesalahan pengisian data.</p>
                <a href="{{ route('tata-usaha.transaksi.index') }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-unlock me-1"></i>Kelola Lock Penilaian
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

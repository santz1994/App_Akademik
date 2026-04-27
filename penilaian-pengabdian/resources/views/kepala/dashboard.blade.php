@extends('layouts.app')
@section('title','Dashboard Kepala')
@section('page-title','Dashboard Kepala Pimpinan Pos')
@section('content')

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

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-bold text-primary">{{ $stats['total_karyawan'] }}</div>
                <div class="text-muted" style="font-size:.8rem;">Karyawan di Pangkalan</div>
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

@endsection

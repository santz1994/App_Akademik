@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page-title','Dashboard')
@section('content')

<div class="row g-3 mb-4">
    @php $cards = [
        ['label'=>'Total User',       'value'=>$stats['total_users'],      'icon'=>'bi-people-fill',      'bg'=>'#dbeafe','ic'=>'#1d4ed8'],
        ['label'=>'Karyawan',         'value'=>$stats['total_karyawan'],   'icon'=>'bi-person-badge',     'bg'=>'#fef9c3','ic'=>'#ca8a04'],
        ['label'=>'Pangkalan Job',    'value'=>$stats['total_pangkalan'],  'icon'=>'bi-building',         'bg'=>'#f3e8ff','ic'=>'#7c3aed'],
        ['label'=>'Kategori Kinerja', 'value'=>$stats['total_kategori'],   'icon'=>'bi-tag-fill',         'bg'=>'#fce7f3','ic'=>'#be185d'],
        ['label'=>'Kompetensi',       'value'=>$stats['total_kompetensi'], 'icon'=>'bi-clipboard-check',  'bg'=>'#d1fae5','ic'=>'#059669'],
        ['label'=>'Sudah Dinilai',    'value'=>$stats['sudah_dinilai'].' / '.$stats['total_karyawan'], 'icon'=>'bi-bar-chart-fill', 'bg'=>'#fee2e2','ic'=>'#dc2626'],
    ]; @endphp
    @foreach($cards as $c)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card h-100">
            <div class="card-body p-3 d-flex align-items-center gap-2">
                <div class="rounded-2 p-2" style="background:{{ $c['bg'] }};">
                    <i class="bi {{ $c['icon'] }}" style="color:{{ $c['ic'] }};font-size:1.3rem;"></i>
                </div>
                <div>
                    <div style="font-size:.7rem;color:#64748b;">{{ $c['label'] }}</div>
                    <div class="fw-bold fs-5 lh-1">{{ $c['value'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-body py-3">
        <h6 class="fw-bold mb-1"><i class="bi bi-hand-wave text-warning me-2"></i>Selamat datang, {{ auth()->user()->name }}!</h6>
        <p class="mb-0 text-muted" style="font-size:.875rem;">
            Anda login sebagai <strong>Administrator</strong>.
            Tahun penilaian aktif: <span class="badge bg-primary">{{ $stats['tahun_aktif'] }}</span>
        </p>
    </div>
</div>

@endsection
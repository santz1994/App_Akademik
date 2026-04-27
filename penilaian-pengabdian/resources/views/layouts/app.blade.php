<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Penilaian Pengabdian')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sb: #1e293b; --sb-active: #1a56db; }
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; }

        /* ---- Sidebar ---- */
        .sidebar { width:240px; min-height:100vh; background:var(--sb); position:fixed; top:0; left:0; z-index:1000; display:flex; flex-direction:column; }
        .sb-brand { padding:1rem 1.2rem; border-bottom:1px solid #334155; color:#fff; }
        .sb-brand-link { color:#fff; text-decoration:none; display:block; }
        .sb-brand-head { display:flex; align-items:center; gap:.6rem; }
        .sb-brand-logo-wrap,
        .sb-brand-icon {
            width:34px;
            height:34px;
            flex:0 0 34px;
            border-radius:6px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
        }
        .sb-brand-logo-wrap {
            background:#fff;
            border:1px solid #cbd5e1;
            padding:3px;
        }
        .sb-brand-logo { width:auto; height:auto; max-height:28px; max-width:94px; object-fit:contain; display:block; }
        .sb-brand-icon { background:#334155; color:#cbd5e1; font-size:.9rem; }
        .sb-brand-text { min-width:0; }
        .sb-brand h6 { margin:0 0 .15rem 0; font-weight:700; font-size:.85rem; }
        .sb-brand small { color:#94a3b8; font-size:.72rem; display:block; line-height:1.25; }
        .sb-section { color:#475569; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:.8rem 1.2rem .2rem; }
        .sidebar a.nav-link { color:#cbd5e1; padding:.45rem 1.2rem; font-size:.82rem; display:flex; align-items:center; gap:.5rem; border-radius:0; }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active { background:var(--sb-active); color:#fff; }
        .sidebar a.nav-link.sub { padding-left:2.5rem; font-size:.78rem; color:#94a3b8; }
        .sidebar a.nav-link.sub:hover, .sidebar a.nav-link.sub.active { background:#334155; color:#fff; }
        .collapse-toggle { cursor:pointer; }
        .sb-user { padding:.75rem 1.2rem; border-top:1px solid #334155; margin-top:auto; }
        .sb-user small { color:#94a3b8; font-size:.73rem; }

        /* ---- Main ---- */
        .main-wrap { margin-left:240px; min-height:100vh; }
        .topbar { background:#fff; border-bottom:1px solid #e2e8f0; padding:.65rem 1.5rem; display:flex; align-items:center; justify-content:space-between; position:fixed; top:0; left:240px; right:0; z-index:900; height:56px; }
        .topbar .page-title { font-weight:600; font-size:.95rem; color:#1e293b; }
        .content { padding:1.25rem; padding-top:calc(1.25rem + 56px); }
        .card { border:0; box-shadow:0 1px 4px rgba(0,0,0,.07); }
        .table thead th { background:#f8fafc; font-size:.8rem; text-transform:uppercase; letter-spacing:.04em; color:#475569; border-bottom:2px solid #e2e8f0; }
        .table tbody td { font-size:.875rem; vertical-align:middle; }
        .badge-admin { background:#fee2e2; color:#dc2626; font-size:.7rem; }
        .badge-user  { background:#dbeafe; color:#1d4ed8; font-size:.7rem; }
        .btn-action  { padding:.25rem .55rem; font-size:.78rem; }

        @media (max-width: 991.98px) {
            .sidebar { width:220px; }
            .main-wrap { margin-left:220px; }
            .topbar { left:220px; padding:.6rem 1rem; }
            .content { padding:1rem; padding-top:calc(1rem + 56px); }
            .sb-brand { padding:.85rem .95rem; }
            .sb-brand-logo-wrap,
            .sb-brand-icon {
                width:26px;
                height:26px;
                flex:0 0 26px;
                border-radius:5px;
            }
            .sb-brand-logo { max-height:22px; max-width:74px; }
            .sb-brand h6 { font-size:.8rem; }
            .sb-brand small { font-size:.67rem; }
            .sidebar a.nav-link { padding:.42rem .95rem; font-size:.78rem; }
            .sb-section { padding:.75rem .95rem .2rem; }
            .sb-user { padding:.65rem .95rem; }
        }

        @media (max-width: 767.98px) {
            .sidebar { width:188px; }
            .main-wrap { margin-left:188px; }
            .topbar { left:188px; padding:.55rem .75rem; }
            .content { padding:.85rem; padding-top:calc(.85rem + 56px); }
            .sb-brand { padding:.72rem .75rem; }
            .sb-brand-head { gap:.45rem; }
            .sb-brand-logo-wrap,
            .sb-brand-icon {
                width:22px;
                height:22px;
                flex:0 0 22px;
                border-radius:4px;
                padding:2px;
            }
            .sb-brand-logo { max-height:18px; max-width:62px; }
            .sb-brand h6 { font-size:.74rem; margin-bottom:.1rem; }
            .sb-brand small { font-size:.62rem; line-height:1.2; }
            .sb-brand small:last-child { display:none; }
            .sidebar a.nav-link { padding:.38rem .75rem; font-size:.74rem; gap:.4rem; }
            .sb-section { padding:.62rem .75rem .2rem; font-size:.58rem; }
            .sb-user { padding:.55rem .75rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
@php
    $sidebarSetting = \App\Models\SettingLembaga::where('is_active', true)->latest()->first()
        ?? \App\Models\SettingLembaga::latest()->first();
    $sidebarTitle = trim((string) ($sidebarSetting?->sidebar_title ?? ''));
    $sidebarSubtitle1 = trim((string) ($sidebarSetting?->sidebar_subtitle_1 ?? ''));
    $sidebarSubtitle2 = trim((string) ($sidebarSetting?->sidebar_subtitle_2 ?? ''));
    $showSidebarTitle = (bool) ($sidebarSetting?->sidebar_show_title ?? true);
    $showSidebarSubtitle1 = (bool) ($sidebarSetting?->sidebar_show_subtitle_1 ?? true);
    $showSidebarSubtitle2 = (bool) ($sidebarSetting?->sidebar_show_subtitle_2 ?? true);

    if (!$sidebarSetting) {
        $sidebarTitle = 'Website Aplikasi';
        $sidebarSubtitle1 = 'Sistem Manajemen Kinerja Pengabdian';
        $sidebarSubtitle2 = 'Yayasan Pondok Pesantren Al-Huda Mugomulyo';
    }

    $sidebarLogoUrl = null;
    $sidebarLogoPath = ltrim((string) ($sidebarSetting?->logo_path ?? ''), '/');
    $homeRoute = auth()->user()->role === 'admin'
        ? route('admin.dashboard')
        : (auth()->user()->is_kepala ? route('kepala.dashboard') : route('user.dashboard'));
    if (
        $sidebarSetting?->show_logo
        && $sidebarLogoPath !== ''
        && \Illuminate\Support\Facades\Storage::disk('public')->exists($sidebarLogoPath)
    ) {
        $sidebarLogoUrl = asset('storage/' . $sidebarLogoPath);
    }

    $pendingUnlockRequests = 0;
    if (auth()->check() && auth()->user()->role === 'admin') {
        $pendingUnlockRequests = \App\Models\PenilaianUnlockRequest::where('status', 'pending')->count();
    }
@endphp

<aside class="sidebar">
    <div class="sb-brand">
        <a href="{{ $homeRoute }}" class="sb-brand-link" aria-label="Kembali ke beranda">
            <div class="sb-brand-head">
                @if($sidebarLogoUrl)
                    <span class="sb-brand-logo-wrap">
                        <img src="{{ $sidebarLogoUrl }}" alt="Logo" class="sb-brand-logo">
                    </span>
                @else
                    <span class="sb-brand-icon"><i class="bi bi-award-fill"></i></span>
                @endif
                <div class="sb-brand-text">
                    @if($showSidebarTitle && $sidebarTitle !== '')
                        <h6>{{ $sidebarTitle }}</h6>
                    @endif
                    @if($showSidebarSubtitle1 && $sidebarSubtitle1 !== '')
                        <small>{{ $sidebarSubtitle1 }}</small>
                    @endif
                    @if($showSidebarSubtitle2 && $sidebarSubtitle2 !== '')
                        <small>{{ $sidebarSubtitle2 }}</small>
                    @endif
                </div>
            </div>
        </a>
    </div>

    <nav class="pt-1 overflow-auto flex-grow-1">
        @if(auth()->user()->role === 'admin')
            {{-- Home --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            {{-- Setting Lembaga --}}
            <a href="{{ route('admin.setting-lembaga.edit') }}" class="nav-link {{ request()->routeIs('admin.setting-lembaga.*') ? 'active' : '' }}">
                <i class="bi bi-building-gear"></i> Setting Lembaga
            </a>

            {{-- MASTER --}}
            <div class="sb-section">Master</div>

            {{-- User Account --}}
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> User Account
            </a>

            {{-- Karyawan --}}
            <a href="{{ route('admin.karyawan.index', ['status_kepala' => 'nonkepala']) }}" class="nav-link {{ request()->routeIs('admin.karyawan.*') && request('status_kepala', 'nonkepala') !== 'kepala' ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Data Karyawan
            </a>
            <a href="{{ route('admin.karyawan.index', ['status_kepala' => 'kepala']) }}" class="nav-link sub {{ request()->routeIs('admin.karyawan.*') && request('status_kepala') === 'kepala' ? 'active' : '' }}">
                <i class="bi bi-person-workspace"></i> Data Kepala
            </a>

            {{-- Tahun Penilaian --}}
            <a href="{{ route('admin.tahun-penilaian.index') }}" class="nav-link {{ request()->routeIs('admin.tahun-penilaian.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Tahun Penilaian
            </a>

            {{-- Kategori Kinerja --}}
            <a href="{{ route('admin.kategori-kinerja.index') }}" class="nav-link {{ request()->routeIs('admin.kategori-kinerja.*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i> Kategori
            </a>

            {{-- Pangkalan Job --}}
            <a href="{{ route('admin.pangkalan.index') }}" class="nav-link {{ request()->routeIs('admin.pangkalan.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Pangkalan Job
            </a>

            {{-- Kompetensi --}}
            <a href="{{ route('admin.kompetensi.index') }}" class="nav-link {{ request()->routeIs('admin.kompetensi.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> Kompetensi
            </a>

            {{-- Performance Rating --}}
            <a href="{{ route('admin.performance-rating.index') }}" class="nav-link {{ request()->routeIs('admin.performance-rating.*') ? 'active' : '' }}">
                <i class="bi bi-star-half"></i> Performance Rating
            </a>

            {{-- Mutasi & Laporan --}}
            <div class="sb-section">Transaksi</div>
            <a href="{{ route('admin.mutasi.index') }}" class="nav-link {{ request()->routeIs('admin.mutasi.*') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Mutasi
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="nav-link {{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Transaksi
                @if($pendingUnlockRequests > 0)
                    <span class="badge bg-danger ms-auto">{{ $pendingUnlockRequests }}</span>
                @endif
            </a>
            <a href="{{ route('admin.transaksi.unlock-requests') }}" class="nav-link sub {{ request()->routeIs('admin.transaksi.unlock-requests') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Request Unlock
                @if($pendingUnlockRequests > 0)
                    <span class="badge bg-danger ms-auto">{{ $pendingUnlockRequests }}</span>
                @endif
            </a>

            <div class="sb-section">Laporan</div>
            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Laporan
            </a>
            <a href="{{ route('admin.laporan.format.edit') }}" class="nav-link {{ request()->routeIs('admin.laporan.format.*') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> Format Cetak Laporan
            </a>
            <a href="{{ route('help.index') }}" class="nav-link {{ request()->routeIs('help.index') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> Help / QnA
            </a>

        @elseif(auth()->user()->is_kepala)
            {{-- KEPALA MENU --}}
            <a href="{{ route('kepala.dashboard') }}" class="nav-link {{ request()->routeIs('kepala.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('kepala.transaksi.index') }}" class="nav-link {{ request()->routeIs('kepala.transaksi.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Transaksi
            </a>
            <a href="{{ route('kepala.laporan.index') }}" class="nav-link {{ request()->routeIs('kepala.laporan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Laporan
            </a>
            <a href="{{ route('help.index') }}" class="nav-link {{ request()->routeIs('help.index') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> Help / QnA
            </a>
        @else
            {{-- USER MENU --}}
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <a href="{{ route('user.laporan.index') }}" class="nav-link {{ request()->routeIs('user.laporan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Laporan
            </a>
            <a href="{{ route('help.index') }}" class="nav-link {{ request()->routeIs('help.index') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> Help / QnA
            </a>
        @endif
    </nav>

    <div class="sb-user">
        <small class="d-block text-truncate"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</small>
        <small>
            @if(auth()->user()->is_kepala)
                <span class="badge bg-warning text-dark">Kepala Pimpinan Pos</span>
            @else
                <span class="badge badge-{{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
            @endif
        </small>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <span class="page-title">@yield('page-title', 'Dashboard')</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted" style="font-size:.8rem;"><i class="bi bi-clock me-1"></i>{{ now()->format('d/m/Y') }}</span>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.transaksi.unlock-requests') }}"
                   class="position-relative text-decoration-none text-dark"
                   title="Permintaan Unlock">
                    <i class="bi bi-bell fs-5"></i>
                    @if($pendingUnlockRequests > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $pendingUnlockRequests }}</span>
                    @endif
                </a>
            @endif
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->is_kepala ? route('kepala.dashboard') : route('user.dashboard')) }}"
               class="d-flex align-items-center gap-2 text-decoration-none text-dark border rounded px-2 py-1"
               style="font-size:.82rem;">
                <i class="bi bi-person-circle fs-5 text-secondary"></i>
                <span>{{ auth()->user()->name }}</span>
                    @if(auth()->user()->is_kepala)
                        <span class="badge bg-warning text-dark ms-1">Kepala Pimpinan Pos</span>
                    @else
                        <span class="badge badge-{{ auth()->user()->role }} ms-1">{{ ucfirst(auth()->user()->role) }}</span>
                    @endif
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button class="btn btn-sm btn-outline-danger" type="submit">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
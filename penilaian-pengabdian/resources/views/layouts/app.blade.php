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
        :root { --sb: #1e293b; --sb-active: #1a56db; --sb-width: 240px; --sb-collapsed-width: 60px; }
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; }

        /* ---- Sidebar ---- */
        .sidebar { width:var(--sb-width); min-height:100vh; max-height:100vh; background:var(--sb); position:fixed; top:0; left:0; z-index:1000; display:flex; flex-direction:column; transition:width 0.3s cubic-bezier(0.4,0,0.2,1); overflow:hidden; }
        .sidebar.collapsed { width:var(--sb-collapsed-width); }
        .sidebar.collapsed .sb-brand-text { display:none !important; }
        .sidebar.collapsed .sb-section { display:none !important; }
        .sidebar.collapsed .sb-user small { display:none !important; }
        .sidebar.collapsed a.nav-link span:not(.badge) { display:none !important; }
        .sidebar.collapsed a.nav-link .ms-auto { display:none !important; }
        .sidebar.collapsed a.nav-link { justify-content:center; padding:.55rem; }
        .sidebar.collapsed a.nav-link.sub { padding-left:.55rem; }
        .sidebar.collapsed .sb-brand { padding:.75rem .5rem; text-align:center; }
        .sidebar.collapsed .sb-brand-head { justify-content:center; }
        .sidebar.collapsed .sb-user { padding:.5rem; text-align:center; }
        .sb-brand { padding:1rem 1.2rem; border-bottom:1px solid #334155; color:#fff; transition:padding 0.3s cubic-bezier(0.4,0,0.2,1); }
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
        .sb-brand-text { min-width:0; transition:opacity 0.2s ease; }
        .sb-brand h6 { margin:0 0 .15rem 0; font-weight:700; font-size:.85rem; }
        .sb-brand small { color:#94a3b8; font-size:.72rem; display:block; line-height:1.25; }
        .sb-section { color:#475569; font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:.8rem 1.2rem .2rem; transition:opacity 0.2s ease; }
        .sidebar a.nav-link { color:#cbd5e1; padding:.45rem 1.2rem; font-size:.82rem; display:flex; align-items:center; gap:.5rem; border-radius:0; transition:all 0.3s cubic-bezier(0.4,0,0.2,1); }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active { background:var(--sb-active); color:#fff; }
        .sidebar a.nav-link.sub { padding-left:2.5rem; font-size:.78rem; color:#94a3b8; }
        .sidebar a.nav-link.sub:hover, .sidebar a.nav-link.sub.active { background:#334155; color:#fff; }
        .sidebar a.nav-link i { flex-shrink:0; width:20px; text-align:center; }
        .sidebar a.nav-link span { white-space:nowrap; overflow:hidden; transition:opacity 0.2s ease; }
        .collapse-toggle { cursor:pointer; }
        .sb-user { padding:.75rem 1.2rem; border-top:1px solid #334155; margin-top:auto; transition:padding 0.3s cubic-bezier(0.4,0,0.2,1); }
        .sb-user small { color:#94a3b8; font-size:.73rem; }

        /* ---- Main ---- */
        .main-wrap { margin-left:var(--sb-width); min-height:100vh; transition:margin-left 0.3s cubic-bezier(0.4,0,0.2,1); }
        .main-wrap.sidebar-collapsed { margin-left:var(--sb-collapsed-width); }
        .topbar { background:#fff; border-bottom:1px solid #e2e8f0; padding:.65rem 1.5rem; display:flex; align-items:center; justify-content:space-between; position:fixed; top:0; left:var(--sb-width); right:0; z-index:900; height:56px; transition:left 0.3s cubic-bezier(0.4,0,0.2,1); }
        .topbar.sidebar-collapsed { left:var(--sb-collapsed-width); }
        .sb-toggle { background:none; border:none; color:#475569; font-size:1.15rem; cursor:pointer; padding:.25rem .4rem; border-radius:4px; transition:color .15s,background .15s; }
        .sb-toggle:hover { background:#f1f5f9; color:#1e293b; }
        .topbar .page-title { font-weight:600; font-size:.95rem; color:#1e293b; }
        .content { padding:1.25rem; padding-top:calc(1.25rem + 56px); }
        .card { border:0; box-shadow:0 1px 4px rgba(0,0,0,.07); }
        .table thead th { background:#f8fafc; font-size:.8rem; text-transform:uppercase; letter-spacing:.04em; color:#475569; border-bottom:2px solid #e2e8f0; }
        .table tbody td { font-size:.875rem; vertical-align:middle; }
        .badge-admin { background:#fee2e2; color:#dc2626; font-size:.7rem; }
        .badge-user  { background:#dbeafe; color:#1d4ed8; font-size:.7rem; }
        .btn-action  { padding:.25rem .55rem; font-size:.78rem; }

        /* Responsive for 1366x768 and similar */
        @media (max-height: 800px) {
            .sidebar { max-height:100vh; }
            .sidebar nav { overflow-y:auto; overflow-x:hidden; }
        }

        @media (max-width: 991.98px) {
            :root { --sb-width:220px; }
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
            :root { --sb-width:188px; }
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
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>

            {{-- Setting Lembaga --}}
            <a href="{{ route('admin.setting-lembaga.edit') }}" class="nav-link {{ request()->routeIs('admin.setting-lembaga.*') ? 'active' : '' }}">
                <i class="bi bi-building-gear"></i> <span>Setting Lembaga</span>
            </a>

            {{-- User Account --}}
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> <span>User Account</span>
            </a>

            {{-- Karyawan --}}
            <div class="sb-section">Karyawan</div>
            <a href="{{ route('admin.karyawan.index', ['status_kepala' => 'nonkepala', 'status_aktif' => 'aktif']) }}" class="nav-link {{ request()->routeIs('admin.karyawan.*') && request('status_kepala', 'nonkepala') !== 'kepala' && request('status_aktif') !== '' ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> <span>Karyawan Aktif</span>
            </a>
            <a href="{{ route('admin.karyawan.index', ['status_kepala' => 'kepala']) }}" class="nav-link sub {{ request()->routeIs('admin.karyawan.*') && request('status_kepala') === 'kepala' ? 'active' : '' }}">
                <i class="bi bi-person-workspace"></i> <span>Data Kepala</span>
            </a>
            <a href="{{ route('admin.karyawan.index') }}" class="nav-link sub {{ request()->routeIs('admin.karyawan.*') && !request()->has('status_kepala') && !request()->has('status_aktif') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>Semua Data Karyawan</span>
            </a>

            {{-- Tahun Penilaian --}}
            <a href="{{ route('admin.tahun-penilaian.index') }}" class="nav-link {{ request()->routeIs('admin.tahun-penilaian.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> <span>Tahun Penilaian</span>
            </a>

            {{-- Data --}}
            <div class="sb-section">Data</div>
            <a href="{{ route('admin.kategori-kinerja.index') }}" class="nav-link {{ request()->routeIs('admin.kategori-kinerja.*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i> <span>Data Kategori</span>
            </a>
            <a href="{{ route('admin.pangkalan.index') }}" class="nav-link {{ request()->routeIs('admin.pangkalan.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> <span>Data Pangkalan Job</span>
            </a>
            <a href="{{ route('admin.kompetensi.index') }}" class="nav-link {{ request()->routeIs('admin.kompetensi.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i> <span>Data List Kompetensi</span>
            </a>
            <a href="{{ route('admin.performance-rating.index') }}" class="nav-link {{ request()->routeIs('admin.performance-rating.*') ? 'active' : '' }}">
                <i class="bi bi-star-half"></i> <span>Data Performance Rating</span>
            </a>

            {{-- Mutasi --}}
            <div class="sb-section">Mutasi</div>
            <a href="{{ route('admin.mutasi.index') }}" class="nav-link {{ request()->routeIs('admin.mutasi.index') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> <span>Mutasi Tahun Ajaran</span>
            </a>
            <a href="{{ route('admin.mutasi.pangkalan') }}" class="nav-link {{ request()->routeIs('admin.mutasi.pangkalan') ? 'active' : '' }}">
                <i class="bi bi-building"></i> <span>Mutasi Antar Pangkalan Job</span>
            </a>

            {{-- Transaksi --}}
            <div class="sb-section">Transaksi</div>
            <a href="{{ route('admin.transaksi.index') }}" class="nav-link {{ request()->routeIs('admin.transaksi.*') && !request()->routeIs('admin.transaksi.unlock-requests') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> <span>Penilaian Karyawan</span>
                @if($pendingUnlockRequests > 0)
                    <span class="badge bg-danger ms-auto">{{ $pendingUnlockRequests }}</span>
                @endif
            </a>
            <a href="{{ route('admin.penilaian-metode.edit') }}" class="nav-link {{ request()->routeIs('admin.penilaian-metode.*') ? 'active' : '' }}">
                <i class="bi bi-calculator"></i> <span>Pengaturan Bobot Penilaian</span>
            </a>
            <a href="{{ route('admin.transaksi.unlock-requests') }}" class="nav-link sub {{ request()->routeIs('admin.transaksi.unlock-requests') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> <span>Request Unlock</span>
                @if($pendingUnlockRequests > 0)
                    <span class="badge bg-danger ms-auto">{{ $pendingUnlockRequests }}</span>
                @endif
            </a>

            {{-- Laporan --}}
            <div class="sb-section">Laporan</div>
            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> <span>Laporan Nilai Keseluruhan</span>
            </a>
            <a href="{{ route('admin.laporan.perorangan') }}" class="nav-link {{ request()->routeIs('admin.laporan.perorangan') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> <span>Laporan Nilai Perorangan</span>
            </a>
            <a href="{{ route('admin.laporan.format.edit') }}" class="nav-link {{ request()->routeIs('admin.laporan.format.*') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> <span>Format Cetak Laporan</span>
            </a>

            {{-- FAQ --}}
            <div class="sb-section">FAQ</div>
            <a href="{{ route('help.index') }}" class="nav-link {{ request()->routeIs('help.index') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> <span>Help</span>
            </a>
            <a href="{{ route('help.index') }}" class="nav-link sub {{ request()->routeIs('help.index') ? 'active' : '' }}">
                <i class="bi bi-chat-dots"></i> <span>FAQ</span>
            </a>

            {{-- Pengaturan --}}
            <div class="sb-section">Pengaturan</div>
            <a href="{{ route('admin.reward-punishment.index') }}" class="nav-link {{ request()->routeIs('admin.reward-punishment.*') ? 'active' : '' }}">
                <i class="bi bi-gift"></i> <span>Reward & Punishment</span>
            </a>
            <a href="{{ route('admin.database.index') }}" class="nav-link {{ request()->routeIs('admin.database.*') ? 'active' : '' }}">
                <i class="bi bi-database-gear"></i> <span>Database Backup</span>
            </a>

        @elseif(auth()->user()->role === 'tata_usaha')
            {{-- TATA USAHA MENU --}}
            <a href="{{ route('tata-usaha.dashboard') }}" class="nav-link {{ request()->routeIs('tata-usaha.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>

            <div class="sb-section">Laporan</div>
            <a href="{{ route('tata-usaha.laporan.index') }}" class="nav-link {{ request()->routeIs('tata-usaha.laporan.index') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> <span>Laporan Keseluruhan</span>
            </a>
            <a href="{{ route('tata-usaha.laporan.perorangan') }}" class="nav-link {{ request()->routeIs('tata-usaha.laporan.perorangan') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> <span>Laporan Perorangan</span>
            </a>

            <div class="sb-section">Transaksi</div>
            <a href="{{ route('tata-usaha.transaksi.index') }}" class="nav-link {{ request()->routeIs('tata-usaha.transaksi.*') ? 'active' : '' }}">
                <i class="bi bi-unlock"></i> <span>Kelola Lock Penilaian</span>
            </a>

        @elseif(auth()->user()->is_kepala)
            {{-- KEPALA MENU --}}
            <a href="{{ route('kepala.dashboard') }}" class="nav-link {{ request()->routeIs('kepala.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('kepala.transaksi.index') }}" class="nav-link {{ request()->routeIs('kepala.transaksi.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> <span>Penilaian Karyawan</span>
            </a>
            <a href="{{ route('kepala.laporan.index') }}" class="nav-link {{ request()->routeIs('kepala.laporan.index') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> <span>Laporan Keseluruhan</span>
            </a>
            <a href="{{ route('kepala.laporan.perorangan') }}" class="nav-link {{ request()->routeIs('kepala.laporan.perorangan') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> <span>Laporan Perorangan</span>
            </a>
            <a href="{{ route('help.index') }}" class="nav-link {{ request()->routeIs('help.index') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> <span>Help / QnA</span>
            </a>
        @else
            {{-- USER MENU --}}
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i> <span>Profil Saya</span>
            </a>
            <a href="{{ route('user.laporan.index') }}" class="nav-link {{ request()->routeIs('user.laporan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> <span>Laporan</span>
            </a>
        @endif
    </nav>

    <div class="sb-user">
        <small class="d-block text-truncate"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</small>
        <small>
            @if(auth()->user()->is_kepala)
                <span class="badge bg-warning text-dark">Kepala Pimpinan Pos</span>
            @elseif(auth()->user()->role === 'tata_usaha')
                <span class="badge bg-info text-white">Tata Usaha</span>
            @else
                <span class="badge badge-{{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
            @endif
        </small>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="sb-toggle" id="sidebarToggle" title="Toggle Sidebar">
                <i class="bi bi-list"></i>
            </button>
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
        </div>
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
                @php
                    $topbarFotoUrl = null;
                    if (auth()->user()->karyawan && !empty(auth()->user()->karyawan->foto_path)) {
                        $topbarFotoPath = ltrim((string) auth()->user()->karyawan->foto_path, '/');
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($topbarFotoPath)) {
                            $topbarFotoUrl = asset('storage/' . $topbarFotoPath);
                        }
                    }
                @endphp
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : (auth()->user()->is_kepala ? route('kepala.dashboard') : route('user.dashboard')) }}"
               class="d-flex align-items-center gap-2 text-decoration-none text-dark border rounded px-2 py-1"
               style="font-size:.82rem;">
                @if($topbarFotoUrl)
                    <img src="{{ $topbarFotoUrl }}" alt="Foto" style="width:28px;height:28px;object-fit:cover;border-radius:50%;border:1px solid #e2e8f0;">
                @else
                    <i class="bi bi-person-circle fs-5 text-secondary"></i>
                @endif
                <span>{{ auth()->user()->name }}</span>
                    @if(auth()->user()->is_kepala)
                        <span class="badge bg-warning text-dark ms-1">Kepala Pimpinan Pos</span>
                    @elseif(auth()->user()->role === 'tata_usaha')
                        <span class="badge bg-info text-white ms-1">Tata Usaha</span>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const mainWrap = document.querySelector('.main-wrap');
    const topbar = document.querySelector('.topbar');
    const toggleBtn = document.getElementById('sidebarToggle');

    if (toggleBtn && sidebar) {
        // Restore state from localStorage
        if (localStorage.getItem('sb-collapsed') === '1') {
            sidebar.classList.add('collapsed');
            if (mainWrap) mainWrap.classList.add('sidebar-collapsed');
            if (topbar) topbar.classList.add('sidebar-collapsed');
        }

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            if (mainWrap) mainWrap.classList.toggle('sidebar-collapsed');
            if (topbar) topbar.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sb-collapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
        });
    }
});
</script>
@stack('scripts')
</body>
</html>
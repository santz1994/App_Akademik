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
        .sb-brand h6 { margin:0; font-weight:700; font-size:.85rem; }
        .sb-brand small { color:#94a3b8; font-size:.72rem; }
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
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sb-brand">
        <h6><i class="bi bi-award-fill me-2"></i>Penilaian Pengabdian</h6>
        <small>Sistem Manajemen Kinerja</small>
    </div>

    <nav class="pt-1 overflow-auto flex-grow-1">
        @if(auth()->user()->role === 'admin')
            {{-- Home --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            {{-- MASTER --}}
            <div class="sb-section">Master</div>

            {{-- User Account --}}
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> User Account
            </a>

            {{-- Karyawan --}}
            <a href="{{ route('admin.karyawan.index') }}" class="nav-link {{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Data Karyawan
            </a>

            {{-- Tahun Penilaian --}}
            <a href="{{ route('admin.tahun-penilaian.index') }}" class="nav-link {{ request()->routeIs('admin.tahun-penilaian.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Tahun Penilaian
            </a>

            {{-- Kategori Kinerja --}}
            <a href="{{ route('admin.kategori-kinerja.index') }}" class="nav-link {{ request()->routeIs('admin.kategori-kinerja.*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i> Kategori Kinerja
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
            </a>

            <div class="sb-section">Laporan</div>
            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Laporan
            </a>

        @else
            {{-- USER MENU --}}
            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
            <div class="sb-section">Informasi</div>
            <a href="#" class="nav-link"><i class="bi bi-person-badge"></i> Data Saya</a>
            <a href="#" class="nav-link"><i class="bi bi-calendar3"></i> Tahun Penilaian</a>
            <a href="#" class="nav-link"><i class="bi bi-bar-chart-line"></i> Laporan</a>
        @endif
    </nav>

    <div class="sb-user">
        <small class="d-block text-truncate"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</small>
        <small>
            <span class="badge badge-{{ auth()->user()->role }}">{{ ucfirst(auth()->user()->role) }}</span>
        </small>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <span class="page-title">@yield('page-title', 'Dashboard')</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted" style="font-size:.8rem;"><i class="bi bi-clock me-1"></i>{{ now()->format('d/m/Y') }}</span>
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
               class="d-flex align-items-center gap-2 text-decoration-none text-dark border rounded px-2 py-1"
               style="font-size:.82rem;">
                <i class="bi bi-person-circle fs-5 text-secondary"></i>
                <span>{{ auth()->user()->name }}</span>
                <span class="badge badge-{{ auth()->user()->role }} ms-1">{{ ucfirst(auth()->user()->role) }}</span>
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
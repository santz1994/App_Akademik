@extends('layouts.app')
@section('title','Setting Lembaga')
@section('page-title','Setting Lembaga')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-building-gear me-2"></i>Pengaturan Identitas Lembaga & Report</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.setting-lembaga.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lembaga</label>
                    <input type="text" name="nama_lembaga" class="form-control" value="{{ old('nama_lembaga', $setting->nama_lembaga) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Yayasan</label>
                    <input type="text" name="nama_yayasan" class="form-control" value="{{ old('nama_yayasan', $setting->nama_yayasan) }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Alamat Lembaga</label>
                    <input type="text" name="alamat_lembaga" class="form-control" value="{{ old('alamat_lembaga', $setting->alamat_lembaga) }}"
                           placeholder="Contoh: Jl. Raya Mugomulyo No. 1, Situbondo">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="telepon_lembaga" class="form-control" value="{{ old('telepon_lembaga', $setting->telepon_lembaga) }}"
                           placeholder="Contoh: (0338) 123456">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email_lembaga" class="form-control" value="{{ old('email_lembaga', $setting->email_lembaga) }}"
                           placeholder="Contoh: info@lembaga.sch.id">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Website</label>
                    <input type="text" name="website_lembaga" class="form-control" value="{{ old('website_lembaga', $setting->website_lembaga) }}"
                           placeholder="Contoh: https://lembaga.sch.id">
                </div>
            </div>

            <h6 class="fw-semibold mt-2 mb-2"><i class="bi bi-layout-text-sidebar-reverse me-2"></i>Pengaturan Title Sidebar</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Judul Sidebar</label>
                    <input type="text" name="sidebar_title" class="form-control" value="{{ old('sidebar_title', $setting->sidebar_title ?? 'Website Aplikasi') }}"
                           placeholder="Contoh: Website Aplikasi">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Subjudul Sidebar 1</label>
                    <input type="text" name="sidebar_subtitle_1" class="form-control" value="{{ old('sidebar_subtitle_1', $setting->sidebar_subtitle_1 ?? 'Sistem Manajemen Kinerja Pengabdian') }}"
                           placeholder="Contoh: Sistem Manajemen Kinerja Pengabdian">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Subjudul Sidebar 2</label>
                    <input type="text" name="sidebar_subtitle_2" class="form-control" value="{{ old('sidebar_subtitle_2', $setting->sidebar_subtitle_2 ?? 'Yayasan Pondok Pesantren Al-Huda Mugomulyo') }}"
                           placeholder="Contoh: Yayasan Pondok Pesantren Al-Huda Mugomulyo">
                </div>
                <div class="col-md-12">
                    <div class="row g-2">
                        <div class="col-md-4 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="sidebar_show_title" value="1" id="showSidebarTitle" {{ old('sidebar_show_title', $setting->sidebar_show_title ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showSidebarTitle">Tampilkan Judul Sidebar</label>
                        </div>
                        <div class="col-md-4 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="sidebar_show_subtitle_1" value="1" id="showSidebarSubtitle1" {{ old('sidebar_show_subtitle_1', $setting->sidebar_show_subtitle_1 ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showSidebarSubtitle1">Tampilkan Subjudul Sidebar 1</label>
                        </div>
                        <div class="col-md-4 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="sidebar_show_subtitle_2" value="1" id="showSidebarSubtitle2" {{ old('sidebar_show_subtitle_2', $setting->sidebar_show_subtitle_2 ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showSidebarSubtitle2">Tampilkan Subjudul Sidebar 2</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <small class="text-muted">Judul/subjudul ini dipakai pada brand sidebar aplikasi. Kosongkan teks jika tidak diperlukan.</small>
                </div>
            </div>

            <hr class="my-4">
            <h6 class="fw-semibold mb-3"><i class="bi bi-envelope-paper me-2"></i>Pengaturan Surat, Logo, dan Lainnya</h6>

            <div class="mb-3">
                <label class="form-label fw-semibold">Lokasi Surat</label>
                <input type="text" name="lokasi_surat" class="form-control" value="{{ old('lokasi_surat', $setting->lokasi_surat) }}"
                       placeholder="Contoh: Situbondo">
                <small class="text-muted">Digunakan untuk format lokasi dan tanggal pada bagian tanda tangan laporan.</small>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ketua Yayasan</label>
                    <input type="text" name="nama_ketua_yayasan" class="form-control" value="{{ old('nama_ketua_yayasan', $setting->nama_ketua_yayasan) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ketua Babinlumni</label>
                    <input type="text" name="nama_ketua_babinlumni" class="form-control" value="{{ old('nama_ketua_babinlumni', $setting->nama_ketua_babinlumni) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tahun Ajaran Aktif untuk Report</label>
                <select name="tahun_penilaian_id" class="form-select">
                    <option value="">-- Gunakan Tahun Aktif Sistem --</option>
                    @foreach($tahunList as $t)
                    <option value="{{ $t->id }}" {{ (string)old('tahun_penilaian_id', $setting->tahun_penilaian_id) === (string)$t->id ? 'selected' : '' }}>
                        {{ $t->periode_penilaian }} {{ $t->is_active ? '(Aktif)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Logo (PNG)</label>
                    <input type="file" name="logo" accept="image/png" class="form-control">
                    @if($setting->logo_path)
                        <small class="text-muted d-block mt-1">Tersimpan: {{ $setting->logo_path }}</small>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">TTD Ketua Yayasan (PNG)</label>
                    <input type="file" name="ttd_ketua_yayasan" accept="image/png" class="form-control">
                    @if($setting->ttd_ketua_yayasan_path)
                        <small class="text-muted d-block mt-1">Tersimpan: {{ $setting->ttd_ketua_yayasan_path }}</small>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">TTD Ketua Babinlumni (PNG)</label>
                    <input type="file" name="ttd_ketua_babinlumni" accept="image/png" class="form-control">
                    @if($setting->ttd_ketua_babinlumni_path)
                        <small class="text-muted d-block mt-1">Tersimpan: {{ $setting->ttd_ketua_babinlumni_path }}</small>
                    @endif
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3 form-check ms-1">
                    <input class="form-check-input" type="checkbox" name="show_logo" value="1" id="showLogo" {{ old('show_logo', $setting->show_logo) ? 'checked' : '' }}>
                    <label class="form-check-label" for="showLogo">Tampilkan Logo</label>
                </div>
                <div class="col-md-3 form-check ms-1">
                    <input class="form-check-input" type="checkbox" name="show_tahun_ajaran" value="1" id="showTahun" {{ old('show_tahun_ajaran', $setting->show_tahun_ajaran) ? 'checked' : '' }}>
                    <label class="form-check-label" for="showTahun">Tampilkan Tahun Ajaran</label>
                </div>
                <div class="col-md-3 form-check ms-1">
                    <input class="form-check-input" type="checkbox" name="show_nama_pimpinan" value="1" id="showNama" {{ old('show_nama_pimpinan', $setting->show_nama_pimpinan) ? 'checked' : '' }}>
                    <label class="form-check-label" for="showNama">Tampilkan Nama Ketua</label>
                </div>
                <div class="col-md-3 form-check ms-1">
                    <input class="form-check-input" type="checkbox" name="show_tanda_tangan" value="1" id="showTtd" {{ old('show_tanda_tangan', $setting->show_tanda_tangan) ? 'checked' : '' }}>
                    <label class="form-check-label" for="showTtd">Tampilkan Tanda Tangan</label>
                </div>
            </div>

            <div class="mb-4 form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', $setting->is_active) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="isActive">Aktifkan setting lembaga ini</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

@endsection

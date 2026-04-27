@extends('layouts.app')
@section('title','Format Cetak Laporan')
@section('page-title','Format Cetak Laporan')
@section('content')

@php
    $defaultColumnOrder = ['no', 'kode_karyawan', 'nama_karyawan', 'pangkalan', 'detail_kompetensi', 'nilai_akhir', 'rating'];

    $orderFromOld = old('laporan_column_order');
    $decodedOldOrder = is_string($orderFromOld) ? json_decode($orderFromOld, true) : null;
    $decodedSavedOrder = json_decode((string)($setting->laporan_column_order ?? ''), true);

    $rawColumnOrder = is_array($decodedOldOrder)
        ? $decodedOldOrder
        : (is_array($decodedSavedOrder) ? $decodedSavedOrder : $defaultColumnOrder);

    $initialColumnOrder = [];
    foreach ($rawColumnOrder as $columnKey) {
        $columnKey = (string) $columnKey;
        if (in_array($columnKey, $defaultColumnOrder, true) && !in_array($columnKey, $initialColumnOrder, true)) {
            $initialColumnOrder[] = $columnKey;
        }
    }
    foreach ($defaultColumnOrder as $columnKey) {
        if (!in_array($columnKey, $initialColumnOrder, true)) {
            $initialColumnOrder[] = $columnKey;
        }
    }

    $columnNameMap = [
        'no' => 'No',
        'kode_karyawan' => 'Kode Karyawan',
        'nama_karyawan' => 'Nama Karyawan',
        'pangkalan' => 'Pangkalan',
        'detail_kompetensi' => 'Detail Kompetensi',
        'nilai_akhir' => 'Nilai Akhir',
        'rating' => 'Rating',
    ];

    $previewNamaLembaga = trim((string) ($setting->nama_lembaga ?? ''));
    $previewNamaYayasan = trim((string) ($setting->nama_yayasan ?? ''));

    if ($previewNamaLembaga === '' && $previewNamaYayasan === '') {
        $previewInstitution = 'Lembaga';
    } elseif ($previewNamaLembaga !== '' && $previewNamaYayasan !== '' && strcasecmp($previewNamaLembaga, $previewNamaYayasan) === 0) {
        $previewInstitution = $previewNamaLembaga;
    } elseif ($previewNamaLembaga !== '' && $previewNamaYayasan !== '') {
        $previewInstitution = $previewNamaLembaga . ' - ' . $previewNamaYayasan;
    } else {
        $previewInstitution = $previewNamaLembaga !== '' ? $previewNamaLembaga : $previewNamaYayasan;
    }

    $previewContactParts = [];
    if (!empty($setting->telepon_lembaga)) {
        $previewContactParts[] = 'Telp: ' . trim((string) $setting->telepon_lembaga);
    }
    if (!empty($setting->email_lembaga)) {
        $previewContactParts[] = 'Email: ' . trim((string) $setting->email_lembaga);
    }
    if (!empty($setting->website_lembaga)) {
        $previewContactParts[] = 'Web: ' . trim((string) $setting->website_lembaga);
    }
    $previewContactLine = implode(' | ', $previewContactParts);
@endphp

@push('styles')
<style>
    .setting-divider { border-top:1px solid #e2e8f0; margin:1rem 0; }
    .drag-handle { cursor: grab; color:#64748b; }
    .sortable-item.dragging { opacity: .5; }
    .preview-wrap { background:#eef2f7; border:1px dashed #cbd5e1; border-radius:.5rem; padding:1rem; overflow:auto; }
    .preview-paper {
        --pv-font-size: 11px;
        --pv-title-size: 16px;
        --pv-cell-padding: 6px;
        --pv-border-width: 1px;
        --pv-text-align: left;
        --pv-header-align: center;
        width: 380px;
        min-width: 260px;
        height: 520px;
        margin: 0 auto;
        background:#fff;
        border:1px solid #cbd5e1;
        box-shadow:0 2px 8px rgba(15,23,42,.08);
    }
    .preview-content { height:100%; box-sizing:border-box; overflow:hidden; }
    .preview-kop { display:flex; align-items:flex-start; gap:.5rem; margin-bottom:.45rem; }
    .preview-kop-logo {
        width:34px;
        height:34px;
        flex:0 0 34px;
        border:1px solid #cbd5e1;
        border-radius:6px;
        background:#ffffff;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        color:#334155;
        font-size:.9rem;
    }
    .preview-kop-logo img { width:auto; height:auto; max-width:100%; max-height:100%; object-fit:contain; display:block; }
    .preview-kop-center { min-width:0; flex:1; text-align:center; }
    .preview-title { text-align:center; font-weight:700; font-size:var(--pv-title-size); margin-bottom:.5rem; }
    .preview-subtitle { text-align:center; font-size:calc(var(--pv-font-size) - 1px); margin-bottom:.15rem; color:#334155; line-height:1.25; }
    .preview-contact { text-align:center; font-size:calc(var(--pv-font-size) - 2px); margin-bottom:.15rem; color:#475569; line-height:1.25; }
    .preview-kop-line { border-top:2px solid #334155; border-bottom:1px solid #334155; height:2px; margin:0 0 .5rem 0; }
    .preview-table { width:100%; border-collapse:collapse; font-size:var(--pv-font-size); table-layout:fixed; }
    .preview-table th,
    .preview-table td {
        border:var(--pv-border-width) solid #94a3b8;
        padding:var(--pv-cell-padding);
        white-space:normal;
        word-break:normal;
        overflow-wrap:break-word;
        hyphens:none;
    }
    .preview-table th { text-align:var(--pv-header-align); background:#f8fafc; }
    .preview-table td { text-align:var(--pv-text-align); }
    .preview-meta { font-size:.78rem; color:#475569; }
    .preview-empty {
        font-size:.8rem;
        color:#64748b;
        text-align:center;
        padding:.75rem;
        border:1px dashed #cbd5e1;
        border-radius:.35rem;
        background:#f8fafc;
    }
</style>
@endpush

<div class="row g-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header py-2 fw-semibold"><i class="bi bi-sliders me-2"></i>Pengaturan Format Laporan</div>
            <div class="card-body">
                <form id="laporanFormatForm" method="POST" action="{{ route('admin.laporan.format.update') }}">
                    @csrf
                    @method('PUT')

                    <h6 class="fw-bold mb-2">A. Struktur Kolom</h6>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="laporan_show_no" value="1" id="showNo"
                                {{ old('laporan_show_no', $setting->laporan_show_no ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showNo">Kolom nomor urut</label>
                        </div>
                        <div class="col-md-6 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="laporan_show_kode_karyawan" value="1" id="showKode"
                                {{ old('laporan_show_kode_karyawan', $setting->laporan_show_kode_karyawan ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showKode">Kolom kode karyawan</label>
                        </div>
                        <div class="col-md-6 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="laporan_show_pangkalan" value="1" id="showPangkalan"
                                {{ old('laporan_show_pangkalan', $setting->laporan_show_pangkalan ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showPangkalan">Kolom pangkalan/job</label>
                        </div>
                        <div class="col-md-6 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="laporan_show_nilai_akhir" value="1" id="showNilaiAkhir"
                                {{ old('laporan_show_nilai_akhir', $setting->laporan_show_nilai_akhir ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showNilaiAkhir">Kolom nilai akhir</label>
                        </div>
                        <div class="col-md-6 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="laporan_show_rating" value="1" id="showRating"
                                {{ old('laporan_show_rating', $setting->laporan_show_rating ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showRating">Kolom rating</label>
                        </div>
                        <div class="col-md-6 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="laporan_show_detail_kompetensi" value="1" id="showDetail"
                                {{ old('laporan_show_detail_kompetensi', $setting->laporan_show_detail_kompetensi ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showDetail">Kolom detail kompetensi</label>
                        </div>
                        <div class="col-md-6 form-check ms-1">
                            <input class="form-check-input" type="checkbox" name="laporan_show_bobot_kategori" value="1" id="showBobot"
                                {{ old('laporan_show_bobot_kategori', $setting->laporan_show_bobot_kategori ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="showBobot">Tampilkan bobot kategori</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Urutan Kolom (Drag & Drop)</label>
                            <input type="hidden" name="laporan_column_order" id="laporanColumnOrder" value='@json($initialColumnOrder)'>
                            <ul class="list-group" id="columnOrderList">
                                @foreach($initialColumnOrder as $columnKey)
                                <li class="list-group-item d-flex justify-content-between align-items-center sortable-item" draggable="true" data-column-key="{{ $columnKey }}">
                                    <span><i class="bi bi-grip-vertical me-2 drag-handle"></i>{{ $columnNameMap[$columnKey] ?? $columnKey }}</span>
                                    <small class="text-muted">{{ $columnKey }}</small>
                                </li>
                                @endforeach
                            </ul>
                            <small class="text-muted">Seret item ke atas/bawah untuk mengatur urutan output laporan.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Custom Label Header</label>
                            <div class="row g-2">
                                <div class="col-6"><input class="form-control form-control-sm" name="laporan_label_no" id="labelNo" value="{{ old('laporan_label_no', $setting->laporan_label_no ?? 'No') }}" placeholder="Label No"></div>
                                <div class="col-6"><input class="form-control form-control-sm" name="laporan_label_kode_karyawan" id="labelKode" value="{{ old('laporan_label_kode_karyawan', $setting->laporan_label_kode_karyawan ?? 'Kode Karyawan') }}" placeholder="Label Kode"></div>
                                <div class="col-6"><input class="form-control form-control-sm" name="laporan_label_nama_karyawan" id="labelNama" value="{{ old('laporan_label_nama_karyawan', $setting->laporan_label_nama_karyawan ?? 'Nama Karyawan') }}" placeholder="Label Nama"></div>
                                <div class="col-6"><input class="form-control form-control-sm" name="laporan_label_pangkalan" id="labelPangkalan" value="{{ old('laporan_label_pangkalan', $setting->laporan_label_pangkalan ?? 'Pangkalan') }}" placeholder="Label Pangkalan"></div>
                                <div class="col-6"><input class="form-control form-control-sm" name="laporan_label_detail_kompetensi" id="labelDetail" value="{{ old('laporan_label_detail_kompetensi', $setting->laporan_label_detail_kompetensi ?? 'Detail Kompetensi') }}" placeholder="Label Detail"></div>
                                <div class="col-6"><input class="form-control form-control-sm" name="laporan_label_nilai_akhir" id="labelNilai" value="{{ old('laporan_label_nilai_akhir', $setting->laporan_label_nilai_akhir ?? 'Nilai Akhir') }}" placeholder="Label Nilai"></div>
                                <div class="col-6"><input class="form-control form-control-sm" name="laporan_label_rating" id="labelRating" value="{{ old('laporan_label_rating', $setting->laporan_label_rating ?? 'Rating') }}" placeholder="Label Rating"></div>
                            </div>
                        </div>
                    </div>

                    <hr class="setting-divider">

                    <h6 class="fw-bold mb-2">B. Metode Bobot Nilai</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cara Hitung Nilai Akhir</label>
                            <select name="laporan_scoring_method" id="scoringMethod" class="form-select">
                                <option value="weighted_kategori" {{ old('laporan_scoring_method', $setting->laporan_scoring_method ?? 'weighted_kinerja_kegiatan') === 'weighted_kategori' ? 'selected' : '' }}>Rata-rata per Kategori (skip kosong/0)</option>
                                <option value="weighted_kinerja_kegiatan" {{ in_array(old('laporan_scoring_method', $setting->laporan_scoring_method ?? 'weighted_kinerja_kegiatan'), ['weighted_kinerja_kegiatan', 'average_kinerja_kegiatan'], true) ? 'selected' : '' }}>Bobot Kinerja + Kegiatan (atur persen di menu Cara Penilaian)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info py-2 mb-0" style="font-size:.82rem;">
                                Formula mode Rata-rata per Kategori (N):<br>
                                <strong>Total Nilai N = (n + n + ... ) / jumlah indikator bernilai</strong><br>
                                <strong>Nilai Akhir = (Total N1 + Total N2 + ... ) / jumlah kategori bernilai</strong><br>
                                Formula mode Bobot Kinerja + Kegiatan diatur pada menu <strong>Cara Penilaian</strong>.
                            </div>
                        </div>
                    </div>

                    <hr class="setting-divider">

                    <h6 class="fw-bold mb-2">C. Kertas & Margin</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Ukuran Kertas</label>
                            <select name="laporan_paper_size" id="paperSize" class="form-select @error('laporan_paper_size') is-invalid @enderror">
                                <option value="a4" {{ old('laporan_paper_size', $setting->laporan_paper_size ?? 'a4') === 'a4' ? 'selected' : '' }}>A4</option>
                                <option value="letter" {{ old('laporan_paper_size', $setting->laporan_paper_size ?? 'a4') === 'letter' ? 'selected' : '' }}>Letter</option>
                                <option value="legal" {{ old('laporan_paper_size', $setting->laporan_paper_size ?? 'a4') === 'legal' ? 'selected' : '' }}>Legal</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Orientasi</label>
                            <select name="laporan_orientation" id="orientation" class="form-select @error('laporan_orientation') is-invalid @enderror">
                                <option value="portrait" {{ old('laporan_orientation', $setting->laporan_orientation ?? 'portrait') === 'portrait' ? 'selected' : '' }}>Portrait</option>
                                <option value="landscape" {{ old('laporan_orientation', $setting->laporan_orientation ?? 'portrait') === 'landscape' ? 'selected' : '' }}>Landscape</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Default Jenis</label>
                            <select name="laporan_default_jenis" id="defaultJenis" class="form-select @error('laporan_default_jenis') is-invalid @enderror">
                                <option value="ringkas" {{ old('laporan_default_jenis', $setting->laporan_default_jenis ?? 'ringkas') === 'ringkas' ? 'selected' : '' }}>Ringkas</option>
                                <option value="rinci" {{ old('laporan_default_jenis', $setting->laporan_default_jenis ?? 'ringkas') === 'rinci' ? 'selected' : '' }}>Rinci</option>
                            </select>
                        </div>
                        <div class="col-md-3"><label class="form-label">Margin Atas (cm)</label><input type="number" step="0.01" min="0.5" max="5" name="laporan_margin_top" id="marginTop" class="form-control" value="{{ old('laporan_margin_top', $setting->laporan_margin_top ?? 2.54) }}"></div>
                        <div class="col-md-3"><label class="form-label">Margin Kanan (cm)</label><input type="number" step="0.01" min="0.5" max="5" name="laporan_margin_right" id="marginRight" class="form-control" value="{{ old('laporan_margin_right', $setting->laporan_margin_right ?? 2.54) }}"></div>
                        <div class="col-md-3"><label class="form-label">Margin Bawah (cm)</label><input type="number" step="0.01" min="0.5" max="5" name="laporan_margin_bottom" id="marginBottom" class="form-control" value="{{ old('laporan_margin_bottom', $setting->laporan_margin_bottom ?? 2.54) }}"></div>
                        <div class="col-md-3"><label class="form-label">Margin Kiri (cm)</label><input type="number" step="0.01" min="0.5" max="5" name="laporan_margin_left" id="marginLeft" class="form-control" value="{{ old('laporan_margin_left', $setting->laporan_margin_left ?? 2.54) }}"></div>
                    </div>

                    <hr class="setting-divider">

                    <h6 class="fw-bold mb-2">D. Teks, Border, Cell</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">Rata Isi Sel</label><select name="laporan_text_align" id="textAlign" class="form-select"><option value="left" {{ old('laporan_text_align', $setting->laporan_text_align ?? 'left') === 'left' ? 'selected' : '' }}>Kiri</option><option value="center" {{ old('laporan_text_align', $setting->laporan_text_align ?? 'left') === 'center' ? 'selected' : '' }}>Tengah</option><option value="right" {{ old('laporan_text_align', $setting->laporan_text_align ?? 'left') === 'right' ? 'selected' : '' }}>Kanan</option><option value="justify" {{ old('laporan_text_align', $setting->laporan_text_align ?? 'left') === 'justify' ? 'selected' : '' }}>Kanan-Kiri</option></select></div>
                        <div class="col-md-4"><label class="form-label">Rata Header</label><select name="laporan_header_align" id="headerAlign" class="form-select"><option value="left" {{ old('laporan_header_align', $setting->laporan_header_align ?? 'center') === 'left' ? 'selected' : '' }}>Kiri</option><option value="center" {{ old('laporan_header_align', $setting->laporan_header_align ?? 'center') === 'center' ? 'selected' : '' }}>Tengah</option><option value="right" {{ old('laporan_header_align', $setting->laporan_header_align ?? 'center') === 'right' ? 'selected' : '' }}>Kanan</option></select></div>
                        <div class="col-md-4"><label class="form-label">Padding Cell (px)</label><input type="number" min="2" max="16" name="laporan_cell_padding" id="cellPadding" class="form-control" value="{{ old('laporan_cell_padding', $setting->laporan_cell_padding ?? 6) }}"></div>
                        <div class="col-md-4"><label class="form-label">Ketebalan Border</label><input type="number" step="0.1" min="0.2" max="3" name="laporan_border_width" id="borderWidth" class="form-control" value="{{ old('laporan_border_width', $setting->laporan_border_width ?? 1) }}"></div>
                        <div class="col-md-4"><label class="form-label">Ukuran Font Isi (px)</label><input type="number" min="9" max="14" name="laporan_font_size" id="fontSize" class="form-control" value="{{ old('laporan_font_size', $setting->laporan_font_size ?? 11) }}"></div>
                        <div class="col-md-4"><label class="form-label">Ukuran Font Judul (px)</label><input type="number" min="12" max="24" name="laporan_title_font_size" id="titleFontSize" class="form-control" value="{{ old('laporan_title_font_size', $setting->laporan_title_font_size ?? 16) }}"></div>
                    </div>

                    <hr class="setting-divider">

                    <h6 class="fw-bold mb-2">E. Lebar Kolom Utama (px)</h6>
                    <div class="row g-2 mb-3">
                        <div class="col-md-4"><label class="form-label">No</label><input type="number" min="24" max="80" name="laporan_col_width_no" id="colNo" class="form-control" value="{{ old('laporan_col_width_no', $setting->laporan_col_width_no ?? 32) }}"></div>
                        <div class="col-md-4"><label class="form-label">Kode</label><input type="number" min="50" max="160" name="laporan_col_width_kode" id="colKode" class="form-control" value="{{ old('laporan_col_width_kode', $setting->laporan_col_width_kode ?? 72) }}"></div>
                        <div class="col-md-4"><label class="form-label">Nama Karyawan</label><input type="number" min="120" max="320" name="laporan_col_width_nama" id="colNama" class="form-control" value="{{ old('laporan_col_width_nama', $setting->laporan_col_width_nama ?? 190) }}"></div>
                        <div class="col-md-4"><label class="form-label">Pangkalan</label><input type="number" min="90" max="280" name="laporan_col_width_pangkalan" id="colPangkalan" class="form-control" value="{{ old('laporan_col_width_pangkalan', $setting->laporan_col_width_pangkalan ?? 140) }}"></div>
                        <div class="col-md-4"><label class="form-label">Nilai Akhir</label><input type="number" min="70" max="160" name="laporan_col_width_nilai" id="colNilai" class="form-control" value="{{ old('laporan_col_width_nilai', $setting->laporan_col_width_nilai ?? 88) }}"></div>
                        <div class="col-md-4"><label class="form-label">Rating</label><input type="number" min="90" max="220" name="laporan_col_width_rating" id="colRating" class="form-control" value="{{ old('laporan_col_width_rating', $setting->laporan_col_width_rating ?? 108) }}"></div>
                    </div>

                    <div class="alert alert-info py-2" style="font-size:.86rem;">
                        Pengaturan ini dipakai untuk output Cetak/PDF. Untuk Excel/CSV, struktur kolom, label header, urutan kolom, dan metode bobot juga ikut diterapkan.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Format</button>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card sticky-top" style="top:72px;">
            <div class="card-header py-2 fw-semibold"><i class="bi bi-eye me-2"></i>Live Preview (Sebelum Simpan)</div>
            <div class="card-body">
                <div class="preview-meta mb-2" id="previewMeta">A4 Portrait, margin 2.54cm</div>
                <div class="preview-wrap">
                    <div id="previewPaper" class="preview-paper">
                        <div id="previewContent" class="preview-content">
                            <div class="preview-kop">
                                <div class="preview-kop-logo">
                                    @php
                                        $previewLogoPath = ltrim((string) ($setting->logo_path ?? ''), '/');
                                        $previewLogoUrl = null;
                                        if (
                                            ($setting->show_logo ?? true)
                                            && $previewLogoPath !== ''
                                            && \Illuminate\Support\Facades\Storage::disk('public')->exists($previewLogoPath)
                                        ) {
                                            $previewLogoUrl = asset('storage/' . $previewLogoPath);
                                        }
                                    @endphp
                                    @if($previewLogoUrl)
                                        <img src="{{ $previewLogoUrl }}" alt="Logo">
                                    @else
                                        <i class="bi bi-award-fill"></i>
                                    @endif
                                </div>
                                <div class="preview-kop-center">
                                    <div class="preview-title">Laporan Penilaian Pengabdian</div>
                                    <div class="preview-subtitle">{{ $previewInstitution !== '' ? $previewInstitution : 'Lembaga' }}</div>
                                    @if(!empty($setting->alamat_lembaga))
                                        <div class="preview-contact">{{ $setting->alamat_lembaga }}</div>
                                    @endif
                                    @if($previewContactLine !== '')
                                        <div class="preview-contact">{{ $previewContactLine }}</div>
                                    @endif
                                    <div class="preview-subtitle" id="previewSubtitle">Mode Ringkas - Simulasi Tahun Ajaran 2025/2026</div>
                                </div>
                            </div>
                            <div class="preview-kop-line"></div>
                            <div id="previewTableHost"></div>
                        </div>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Preview ini simulasi tampilan cetak/PDF, bukan data asli.</small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const form = document.getElementById('laporanFormatForm');
    if (!form) return;

    const previewPaper = document.getElementById('previewPaper');
    const previewContent = document.getElementById('previewContent');
    const previewSubtitle = document.getElementById('previewSubtitle');
    const previewMeta = document.getElementById('previewMeta');
    const previewTableHost = document.getElementById('previewTableHost');
    const columnOrderList = document.getElementById('columnOrderList');
    const columnOrderInput = document.getElementById('laporanColumnOrder');
    const previewWrap = previewPaper?.closest('.preview-wrap');

    const sizeMap = {
        a4: { w: 21.0, h: 29.7, label: 'A4' },
        letter: { w: 21.59, h: 27.94, label: 'Letter' },
        legal: { w: 21.59, h: 35.56, label: 'Legal' }
    };

    const sampleRowsWeighted = [
        { no: '1', kode_karyawan: 'KRY-001', nama_karyawan: 'Ahmad Fauzi', pangkalan: 'PRAMUKA', detail_kompetensi: ['85', '90'], nilai_akhir: '87.50', rating: 'B - Baik' },
        { no: '2', kode_karyawan: 'KRY-002', nama_karyawan: 'Siti Aminah', pangkalan: 'PONDOK', detail_kompetensi: ['92', '94'], nilai_akhir: '93.00', rating: 'A - Sangat Baik' }
    ];

    const sampleRowsAverage = [
        { no: '1', kode_karyawan: 'KRY-001', nama_karyawan: 'Ahmad Fauzi', pangkalan: 'PRAMUKA', detail_kompetensi: ['85', '90'], nilai_akhir: '86.25', rating: 'B - Baik' },
        { no: '2', kode_karyawan: 'KRY-002', nama_karyawan: 'Siti Aminah', pangkalan: 'PONDOK', detail_kompetensi: ['92', '94'], nilai_akhir: '91.50', rating: 'A - Sangat Baik' }
    ];

    const detailCodes = ['K001', 'K002'];

    function input(name) {
        return form.querySelector(`[name="${name}"]`);
    }

    function isChecked(name) {
        const el = input(name);
        return !!el && el.checked;
    }

    function toNumber(name, fallback) {
        const el = input(name);
        const num = parseFloat(el ? el.value : '');
        return Number.isFinite(num) ? num : fallback;
    }

    function clamp(num, min, max) {
        return Math.min(Math.max(num, min), max);
    }

    function esc(text) {
        return String(text ?? '').replace(/[&<>'"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[c]));
    }

    function getColumnOrder() {
        return Array.from(columnOrderList.querySelectorAll('.sortable-item')).map((el) => el.dataset.columnKey);
    }

    function saveColumnOrder() {
        columnOrderInput.value = JSON.stringify(getColumnOrder());
    }

    function getLabelMap() {
        return {
            no: input('laporan_label_no')?.value || 'No',
            kode_karyawan: input('laporan_label_kode_karyawan')?.value || 'Kode Karyawan',
            nama_karyawan: input('laporan_label_nama_karyawan')?.value || 'Nama Karyawan',
            pangkalan: input('laporan_label_pangkalan')?.value || 'Pangkalan',
            detail_kompetensi: input('laporan_label_detail_kompetensi')?.value || 'Detail Kompetensi',
            nilai_akhir: input('laporan_label_nilai_akhir')?.value || 'Nilai Akhir',
            rating: input('laporan_label_rating')?.value || 'Rating',
        };
    }

    function getVisibleColumns() {
        const showNo = isChecked('laporan_show_no');
        const showKode = isChecked('laporan_show_kode_karyawan');
        const showPangkalan = isChecked('laporan_show_pangkalan');
        const showNilai = isChecked('laporan_show_nilai_akhir');
        const showRating = showNilai && isChecked('laporan_show_rating');
        const showDetail = isChecked('laporan_show_detail_kompetensi') && (input('laporan_default_jenis')?.value === 'rinci');

        return getColumnOrder().filter((column) => {
            if (column === 'no') return showNo;
            if (column === 'kode_karyawan') return showKode;
            if (column === 'pangkalan') return showPangkalan;
            if (column === 'nilai_akhir') return showNilai;
            if (column === 'rating') return showRating;
            if (column === 'detail_kompetensi') return showDetail;
            return true;
        });
    }

    function colWidth(columnKey) {
        if (columnKey === 'no') return clamp(toNumber('laporan_col_width_no', 32), 24, 80);
        if (columnKey === 'kode_karyawan') return clamp(toNumber('laporan_col_width_kode', 72), 50, 160);
        if (columnKey === 'nama_karyawan') return clamp(toNumber('laporan_col_width_nama', 190), 120, 320);
        if (columnKey === 'pangkalan') return clamp(toNumber('laporan_col_width_pangkalan', 140), 90, 280);
        if (columnKey === 'nilai_akhir') return clamp(toNumber('laporan_col_width_nilai', 88), 70, 160);
        if (columnKey === 'rating') return clamp(toNumber('laporan_col_width_rating', 108), 90, 220);
        return 60;
    }

    function buildPreviewColumns(visibleColumns, printableWidthPx, previewScale) {
        const parts = [];
        let totalDesiredWidth = 0;

        visibleColumns.forEach((column) => {
            if (column === 'detail_kompetensi') {
                detailCodes.forEach((code, detailIndex) => {
                    parts.push({ type: 'detail', code, detailIndex, desired: 60 });
                    totalDesiredWidth += 60;
                });
            } else {
                const desired = colWidth(column);
                parts.push({ type: 'main', column, desired });
                totalDesiredWidth += desired;
            }
        });

        const fitRatio = totalDesiredWidth > 0
            ? Math.min(1, printableWidthPx / totalDesiredWidth)
            : 1;

        return parts.map((part) => ({
            ...part,
            width: Math.max(3, Math.round(part.desired * fitRatio * previewScale)),
        }));
    }

    function updatePreview() {
        const paperSize = (input('laporan_paper_size')?.value || 'a4').toLowerCase();
        const orientation = (input('laporan_orientation')?.value || 'portrait').toLowerCase();
        const defaultJenis = input('laporan_default_jenis')?.value || 'ringkas';
        const textAlign = (input('laporan_text_align')?.value || 'left').toLowerCase();
        const headerAlign = (input('laporan_header_align')?.value || 'center').toLowerCase();
        const scoringMethod = input('laporan_scoring_method')?.value || 'weighted_kategori';

        const marginTop = clamp(toNumber('laporan_margin_top', 2.54), 0.5, 5);
        const marginRight = clamp(toNumber('laporan_margin_right', 2.54), 0.5, 5);
        const marginBottom = clamp(toNumber('laporan_margin_bottom', 2.54), 0.5, 5);
        const marginLeft = clamp(toNumber('laporan_margin_left', 2.54), 0.5, 5);

        const cellPadding = clamp(toNumber('laporan_cell_padding', 6), 2, 16);
        const borderWidth = clamp(toNumber('laporan_border_width', 1), 0.2, 3);
        const fontSize = clamp(toNumber('laporan_font_size', 11), 9, 14);
        const titleFontSize = clamp(toNumber('laporan_title_font_size', 16), 12, 24);

        const defaultJenisInput = input('laporan_default_jenis');
        const detailInput = input('laporan_show_detail_kompetensi');
        if (defaultJenisInput && detailInput) {
            const rinciOption = defaultJenisInput.querySelector('option[value="rinci"]');
            if (rinciOption) {
                rinciOption.disabled = !detailInput.checked;
            }
            if (!detailInput.checked && defaultJenisInput.value === 'rinci') {
                defaultJenisInput.value = 'ringkas';
            }
        }

        const ratingInput = input('laporan_show_rating');
        const nilaiInput = input('laporan_show_nilai_akhir');
        if (ratingInput && nilaiInput) {
            ratingInput.disabled = !nilaiInput.checked;
            if (!nilaiInput.checked) ratingInput.checked = false;
        }

        const labels = getLabelMap();
        const visibleColumns = getVisibleColumns();
        const sampleRows = scoringMethod === 'weighted_kinerja_kegiatan' ? sampleRowsAverage : sampleRowsWeighted;

        const base = sizeMap[paperSize] || sizeMap.a4;
        const rawW = orientation === 'landscape' ? base.h : base.w;
        const rawH = orientation === 'landscape' ? base.w : base.h;
        const cmToPx = 37.7952755906;
        const paperWidthPx = rawW * cmToPx;
        const paperHeightPx = rawH * cmToPx;
        const availableWidth = Math.max((previewWrap?.clientWidth || 420) - 24, 220);
        const previewScale = clamp(availableWidth / paperWidthPx, 0.25, 0.8);
        const pxPerCm = cmToPx * previewScale;

        previewPaper.style.width = `${Math.round(paperWidthPx * previewScale)}px`;
        previewPaper.style.height = `${Math.round(paperHeightPx * previewScale)}px`;

        previewContent.style.paddingTop = `${Math.round(marginTop * pxPerCm)}px`;
        previewContent.style.paddingRight = `${Math.round(marginRight * pxPerCm)}px`;
        previewContent.style.paddingBottom = `${Math.round(marginBottom * pxPerCm)}px`;
        previewContent.style.paddingLeft = `${Math.round(marginLeft * pxPerCm)}px`;

        const printableWidthPx = Math.max(120, paperWidthPx - ((marginLeft + marginRight) * cmToPx));

        previewPaper.style.setProperty('--pv-font-size', `${Math.max(4, fontSize * previewScale).toFixed(2)}px`);
        previewPaper.style.setProperty('--pv-title-size', `${Math.max(6, titleFontSize * previewScale).toFixed(2)}px`);
        previewPaper.style.setProperty('--pv-cell-padding', `${Math.max(1, cellPadding * previewScale).toFixed(2)}px`);
        previewPaper.style.setProperty('--pv-border-width', `${Math.max(0.3, borderWidth * previewScale).toFixed(2)}px`);
        previewPaper.style.setProperty('--pv-text-align', textAlign);
        previewPaper.style.setProperty('--pv-header-align', headerAlign);

        const scoringText = scoringMethod === 'weighted_kinerja_kegiatan'
            ? 'Formula Bobot Kinerja/Kegiatan'
            : 'Formula Rata-rata per Kategori';
        const jenisText = defaultJenis === 'rinci' ? 'Rinci' : 'Ringkas';
        const scalePercent = Math.round(previewScale * 100);
        const scaleRatio = previewScale > 0 ? (1 / previewScale) : 1;

        previewMeta.textContent = `${base.label} ${orientation === 'landscape' ? 'Landscape' : 'Portrait'}, margin ${marginTop}/${marginRight}/${marginBottom}/${marginLeft} cm, ${jenisText}, ${scoringText}, skala ${scalePercent}% (~1:${scaleRatio.toFixed(1)})`;

        if (previewSubtitle) {
            previewSubtitle.textContent = defaultJenis === 'rinci'
                ? 'Mode Rinci - Simulasi Tahun Ajaran 2025/2026'
                : 'Mode Ringkas - Simulasi Tahun Ajaran 2025/2026';
        }

        if (!visibleColumns.length) {
            previewTableHost.innerHTML = '<div class="preview-empty">Tidak ada kolom aktif untuk ditampilkan.</div>';
            return;
        }

        const scaledColumns = buildPreviewColumns(visibleColumns, printableWidthPx, previewScale);

        const headerHtml = scaledColumns.map((part) => {
            if (part.type === 'detail') {
                return `<th style="width:${part.width}px">${esc(labels.detail_kompetensi)} ${part.detailIndex + 1}<br><small>${esc(part.code)}</small></th>`;
            }
            return `<th style="width:${part.width}px">${esc(labels[part.column] || part.column)}</th>`;
        }).join('');

        const bodyHtml = sampleRows.map((row) => {
            const cells = visibleColumns.map((column) => {
                if (column === 'detail_kompetensi') {
                    return row.detail_kompetensi.map((v) => `<td>${esc(v)}</td>`).join('');
                }
                return `<td>${esc(row[column] ?? '-')}</td>`;
            }).join('');
            return `<tr>${cells}</tr>`;
        }).join('');

        previewTableHost.innerHTML = `
            <table class="preview-table">
                <thead><tr>${headerHtml}</tr></thead>
                <tbody>${bodyHtml}</tbody>
            </table>
        `;
    }

    let draggedItem = null;
    columnOrderList.querySelectorAll('.sortable-item').forEach((item) => {
        item.addEventListener('dragstart', () => {
            draggedItem = item;
            item.classList.add('dragging');
        });

        item.addEventListener('dragend', () => {
            item.classList.remove('dragging');
            draggedItem = null;
            saveColumnOrder();
            updatePreview();
        });

        item.addEventListener('dragover', (e) => e.preventDefault());
        item.addEventListener('drop', (e) => {
            e.preventDefault();
            if (!draggedItem || draggedItem === item) return;
            const rect = item.getBoundingClientRect();
            const isAfter = (e.clientY - rect.top) > (rect.height / 2);
            item.parentNode.insertBefore(draggedItem, isAfter ? item.nextSibling : item);
            saveColumnOrder();
            updatePreview();
        });
    });

    form.querySelectorAll('input, select').forEach((el) => {
        el.addEventListener('input', updatePreview);
        el.addEventListener('change', updatePreview);
    });

    window.addEventListener('resize', updatePreview);

    saveColumnOrder();
    updatePreview();
})();
</script>
@endpush

@endsection

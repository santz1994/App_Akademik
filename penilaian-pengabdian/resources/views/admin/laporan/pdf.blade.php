<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian</title>
    @php
        $jenisLaporan = $jenisLaporan ?? 'ringkas';
        $reportFormat = array_merge([
            'show_no' => true,
            'show_kode_karyawan' => true,
            'show_pangkalan' => true,
            'show_nilai_akhir' => true,
            'show_rating' => true,
            'show_detail_kompetensi' => true,
            'show_bobot_kategori' => true,
            'paper_size' => 'a4',
            'orientation' => 'portrait',
            'margin_top' => 2.54,
            'margin_right' => 2.54,
            'margin_bottom' => 2.54,
            'margin_left' => 2.54,
            'text_align' => 'left',
            'header_align' => 'center',
            'cell_padding' => 6,
            'border_width' => 1,
            'font_size' => 11,
            'title_font_size' => 16,
            'col_width_no' => 32,
            'col_width_kode' => 72,
            'col_width_nama' => 190,
            'col_width_pangkalan' => 140,
            'col_width_nilai' => 88,
            'col_width_rating' => 108,
            'column_order' => ['no', 'kode_karyawan', 'nama_karyawan', 'pangkalan', 'detail_kompetensi', 'nilai_akhir', 'rating'],
            'labels' => [],
            'scoring_method' => 'weighted_kinerja_kegiatan',
            'score_weight_kinerja' => 70,
            'score_weight_kegiatan' => 30,
        ], $reportFormat ?? []);

        $namaLembaga = trim((string) ($setting?->nama_lembaga ?? ''));
        $namaYayasan = trim((string) ($setting?->nama_yayasan ?? ''));

        if ($namaLembaga === '' && $namaYayasan === '') {
            $kopInstitution = 'Lembaga';
        } elseif ($namaLembaga !== '' && $namaYayasan !== '' && strcasecmp($namaLembaga, $namaYayasan) === 0) {
            $kopInstitution = $namaLembaga;
        } elseif ($namaLembaga !== '' && $namaYayasan !== '') {
            $kopInstitution = $namaLembaga . ' - ' . $namaYayasan;
        } else {
            $kopInstitution = $namaLembaga !== '' ? $namaLembaga : $namaYayasan;
        }

        $kopAddress = trim((string) ($setting?->alamat_lembaga ?? ''));
        $kopContactParts = [];
        if (!empty($setting?->telepon_lembaga)) {
            $kopContactParts[] = 'Telp: ' . trim((string) $setting->telepon_lembaga);
        }
        if (!empty($setting?->email_lembaga)) {
            $kopContactParts[] = 'Email: ' . trim((string) $setting->email_lembaga);
        }
        if (!empty($setting?->website_lembaga)) {
            $kopContactParts[] = 'Web: ' . trim((string) $setting->website_lembaga);
        }
        $kopContact = implode(' | ', $kopContactParts);

        $defaultOrder = ['no', 'kode_karyawan', 'nama_karyawan', 'pangkalan', 'detail_kompetensi', 'nilai_akhir', 'rating'];
        $columnOrder = is_array($reportFormat['column_order']) ? $reportFormat['column_order'] : $defaultOrder;
        $normalizedOrder = [];
        foreach ($columnOrder as $columnKey) {
            $columnKey = (string) $columnKey;
            if (in_array($columnKey, $defaultOrder, true) && !in_array($columnKey, $normalizedOrder, true)) {
                $normalizedOrder[] = $columnKey;
            }
        }
        foreach ($defaultOrder as $columnKey) {
            if (!in_array($columnKey, $normalizedOrder, true)) {
                $normalizedOrder[] = $columnKey;
            }
        }

        $labels = array_merge([
            'no' => 'No',
            'kode_karyawan' => 'Kode Karyawan',
            'nama_karyawan' => 'Nama Karyawan',
            'pangkalan' => 'Pangkalan',
            'detail_kompetensi' => 'Detail Kompetensi',
            'nilai_akhir' => 'Nilai Akhir',
            'rating' => 'Rating',
        ], is_array($reportFormat['labels'] ?? null) ? $reportFormat['labels'] : []);

        $showDetailColumns = $jenisLaporan === 'rinci' && (bool) $reportFormat['show_detail_kompetensi'];
        $ringkasKategoriList = $jenisLaporan === 'ringkas'
            ? $kategoriList->values()
            : collect();
        $detailKompetensiList = $showDetailColumns
            ? $kategoriList->flatMap(fn($kat) => $kat->kompetensi)->values()
            : collect();

        $visibleColumns = [];
        foreach ($normalizedOrder as $columnKey) {
            if ($columnKey === 'no' && $reportFormat['show_no']) {
                $visibleColumns[] = $columnKey;
            } elseif ($columnKey === 'kode_karyawan' && $reportFormat['show_kode_karyawan']) {
                $visibleColumns[] = $columnKey;
            } elseif ($columnKey === 'nama_karyawan') {
                $visibleColumns[] = $columnKey;
            } elseif ($columnKey === 'pangkalan' && $reportFormat['show_pangkalan']) {
                $visibleColumns[] = $columnKey;
            } elseif (
                $columnKey === 'detail_kompetensi'
                && (
                    ($jenisLaporan === 'ringkas' && $ringkasKategoriList->isNotEmpty())
                    || ($showDetailColumns && $detailKompetensiList->isNotEmpty())
                )
            ) {
                $visibleColumns[] = $columnKey;
            } elseif ($columnKey === 'nilai_akhir' && $reportFormat['show_nilai_akhir']) {
                $visibleColumns[] = $columnKey;
            } elseif ($columnKey === 'rating' && $reportFormat['show_rating'] && $reportFormat['show_nilai_akhir']) {
                $visibleColumns[] = $columnKey;
            }
        }

        $detailHasGroupedHeader = in_array('detail_kompetensi', $visibleColumns, true)
            && $jenisLaporan === 'rinci'
            && $detailKompetensiList->isNotEmpty();

        $paperSize = strtoupper((string) $reportFormat['paper_size']);
        $orientation = strtolower((string) $reportFormat['orientation']) === 'landscape' ? 'landscape' : 'portrait';

        $kopLogoSlotWidth = $orientation === 'landscape' ? 76 : 90;
        $kopLogoBoxSize = $orientation === 'landscape' ? 56 : 68;
        $routeName = request()->route()?->getName() ?? '';
        $isPdfOutput = is_string($routeName) && str_ends_with($routeName, '.laporan.pdf');
        $logoRelativePath = ($setting && $setting->show_logo) ? trim((string) ($setting->logo_path ?? '')) : '';
        $kopLogoSrc = null;
        if ($logoRelativePath !== '') {
            $normalizedLogoPath = ltrim($logoRelativePath, '/');
            $publicLogoPath = public_path('storage/' . $normalizedLogoPath);
            if (is_file($publicLogoPath)) {
                $kopLogoSrc = $isPdfOutput
                    ? $publicLogoPath
                    : asset('storage/' . $normalizedLogoPath);
            }
        }

        $karyawanFotoSrc = null;
        $karyawanFotoNama = null;
        $karyawanItems = $karyawanList instanceof \Illuminate\Support\Collection
            ? $karyawanList
            : collect($karyawanList ?? []);
        $singleKaryawan = $karyawanItems->count() === 1 ? $karyawanItems->first() : null;
        if ($singleKaryawan && !empty($singleKaryawan->foto_path)) {
            $normalizedFotoPath = ltrim((string) $singleKaryawan->foto_path, '/');
            $publicFotoPath = public_path('storage/' . $normalizedFotoPath);
            if (is_file($publicFotoPath)) {
                $karyawanFotoSrc = $isPdfOutput
                    ? $publicFotoPath
                    : asset('storage/' . $normalizedFotoPath);
                $karyawanFotoNama = trim((string) ($singleKaryawan->nama_karyawan ?? ''));
            }
        }

        $marginTop = number_format((float) $reportFormat['margin_top'], 2, '.', '');
        $marginRight = number_format((float) $reportFormat['margin_right'], 2, '.', '');
        $marginBottom = number_format((float) $reportFormat['margin_bottom'], 2, '.', '');
        $marginLeft = number_format((float) $reportFormat['margin_left'], 2, '.', '');

        $textAlign = (string) $reportFormat['text_align'];
        $headerAlign = (string) $reportFormat['header_align'];
        $cellPadding = (int) $reportFormat['cell_padding'];
        $borderWidth = number_format((float) $reportFormat['border_width'], 1, '.', '');
        $fontSize = (int) $reportFormat['font_size'];
        $titleFontSize = (int) $reportFormat['title_font_size'];

        $colWidthMap = [
            'no' => (int) $reportFormat['col_width_no'],
            'kode_karyawan' => (int) $reportFormat['col_width_kode'],
            'nama_karyawan' => (int) $reportFormat['col_width_nama'],
            'pangkalan' => (int) $reportFormat['col_width_pangkalan'],
            'nilai_akhir' => (int) $reportFormat['col_width_nilai'],
            'rating' => (int) $reportFormat['col_width_rating'],
        ];

        // Calculate stats for summary section
        $totalKaryawan = $karyawanItems->count();
        $totalKategori = $kategoriList->count();
        $totalKompetensiCount = $kategoriList->sum(fn($k) => $k->kompetensi->count());
        $periodeLabel = $selectedTahunData?->periode_penilaian ?? '-';

        // Calculate average score and distribution for summary
        $allScores = collect();
        $ratingDistribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0];
        foreach($karyawanList as $k) {
            $kategoriUntukKaryawan = \App\Support\LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $k);
            $applicableKompetensiIds = \App\Support\LaporanScoreCalculator::kompetensiIdsFromKategori($kategoriUntukKaryawan);
            $trxByKompetensi = $k->transaksi
                ->filter(fn($t) => $t->nilai !== null)
                ->filter(fn($t) => $applicableKompetensiIds->contains((int) $t->kompetensi_id))
                ->keyBy('kompetensi_id');
            $nilai = \App\Support\LaporanScoreCalculator::calculate(
                $kategoriUntukKaryawan,
                $trxByKompetensi,
                $reportFormat['scoring_method'],
                [
                    'bobot_kinerja' => $reportFormat['score_weight_kinerja'],
                    'bobot_kegiatan' => $reportFormat['score_weight_kegiatan'],
                ]
            );
            if ($nilai !== null) {
                $allScores->push($nilai);
                $ratingMeta = \App\Support\LaporanScoreCalculator::ratingMeta($nilai);
                $ratingLetter = substr($ratingMeta['label'], 0, 1);
                if (isset($ratingDistribution[$ratingLetter])) {
                    $ratingDistribution[$ratingLetter]++;
                }
            }
        }
        $avgScore = $allScores->isNotEmpty() ? $allScores->avg() : null;
        $maxScore = $allScores->isNotEmpty() ? $allScores->max() : null;
        $minScore = $allScores->isNotEmpty() ? $allScores->min() : null;
    @endphp
    <style>
        @page {
            size: {{ $paperSize }} {{ $orientation }};
            margin: {{ $marginTop }}cm {{ $marginRight }}cm {{ $marginBottom }}cm {{ $marginLeft }}cm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: {{ $fontSize }}px;
            color: #1a1a2e;
            text-align: {{ $textAlign }};
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* ===== HEADER / KOP SURAT ===== */
        .kop-header {
            width: 100%;
            display: table;
            margin-bottom: 0;
            padding-bottom: 8px;
        }
        .kop-logo {
            width: {{ $kopLogoSlotWidth }}px;
            display: table-cell;
            vertical-align: middle;
            text-align: left;
        }
        .kop-logo-box {
            width: {{ $kopLogoBoxSize }}px;
            height: {{ $kopLogoBoxSize }}px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #1a56db;
            border-radius: 10px;
            background: #ffffff;
            overflow: hidden;
        }
        .logo {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }
        .kop-center {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-right: {{ $kopLogoSlotWidth }}px;
        }
        .kop-title {
            font-size: {{ $titleFontSize + 2 }}px;
            font-weight: 800;
            text-transform: uppercase;
            color: #1a1a2e;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }
        .kop-institution {
            font-size: {{ $titleFontSize - 2 }}px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1a56db;
            margin-bottom: 2px;
        }
        .kop-contact {
            font-size: {{ max($fontSize - 2, 8) }}px;
            color: #4a5568;
            line-height: 1.4;
        }

        /* Double line separator */
        .kop-line {
            border: 0;
            border-top: 3px double #1a56db;
            margin: 6px 0 14px;
        }

        /* ===== REPORT TITLE SECTION ===== */
        .report-title-section {
            text-align: center;
            margin-bottom: 14px;
        }
        .report-main-title {
            font-size: {{ $titleFontSize }}px;
            font-weight: 800;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .report-subtitle {
            font-size: {{ $fontSize + 1 }}px;
            font-weight: 600;
            color: #1a56db;
            margin-bottom: 2px;
        }
        .report-period {
            font-size: {{ $fontSize - 1 }}px;
            color: #4a5568;
            font-weight: 500;
        }

        /* ===== INFO BOX ===== */
        .info-box {
            width: 100%;
            margin-bottom: 14px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            overflow: hidden;
        }
        .info-box-header {
            background: linear-gradient(135deg, #1a56db, #2563eb);
            color: #fff;
            font-size: {{ $fontSize - 1 }}px;
            font-weight: 700;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .info-box-body {
            padding: 6px 10px;
            font-size: {{ $fontSize - 1 }}px;
            color: #374151;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }
        .info-label {
            display: table-cell;
            width: 130px;
            font-weight: 600;
            color: #4a5568;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            color: #1a1a2e;
            vertical-align: top;
        }

        /* ===== SUMMARY STATS ===== */
        .summary-grid {
            width: 100%;
            margin-bottom: 14px;
            display: table;
        }
        .summary-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 8px 6px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            vertical-align: middle;
        }
        .summary-item:first-child {
            border-radius: 4px 0 0 4px;
        }
        .summary-item:last-child {
            border-radius: 0 4px 4px 0;
        }
        .summary-value {
            font-size: {{ $fontSize + 4 }}px;
            font-weight: 800;
            color: #1a56db;
            line-height: 1.2;
        }
        .summary-value.green { color: #059669; }
        .summary-value.orange { color: #d97706; }
        .summary-value.purple { color: #7c3aed; }
        .summary-label {
            font-size: {{ max($fontSize - 2, 8) }}px;
            color: #6b7280;
            font-weight: 500;
            margin-top: 2px;
        }

        /* ===== RATING DISTRIBUTION ===== */
        .rating-dist {
            width: 100%;
            margin-bottom: 14px;
            display: table;
            border-collapse: collapse;
        }
        .rating-dist-cell {
            display: table-cell;
            text-align: center;
            padding: 5px 4px;
            border: 1px solid #e5e7eb;
        }
        .rating-dist-letter {
            font-weight: 800;
            font-size: {{ $fontSize }}px;
            margin-bottom: 1px;
        }
        .rating-dist-count {
            font-size: {{ max($fontSize - 2, 8) }}px;
            color: #6b7280;
            font-weight: 600;
        }
        .rating-a { color: #059669; background: #ecfdf5; }
        .rating-b { color: #2563eb; background: #eff6ff; }
        .rating-c { color: #d97706; background: #fffbeb; }
        .rating-d { color: #dc2626; background: #fef2f2; }
        .rating-e { color: #6b7280; background: #f9fafb; }

        /* ===== PHOTO (single karyawan) ===== */
        .karyawan-photo-wrap {
            width: 100%;
            text-align: right;
            margin: 0 0 10px;
        }
        .karyawan-photo-card {
            display: inline-block;
            text-align: center;
        }
        .karyawan-photo-frame {
            width: 92px;
            height: 122px;
            border: 2px solid #1a56db;
            border-radius: 6px;
            background: #ffffff;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .karyawan-photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .karyawan-photo-caption {
            margin-top: 3px;
            font-size: {{ max($fontSize - 2, 8) }}px;
            color: #334155;
            font-weight: 600;
            max-width: 140px;
        }

        /* ===== MAIN TABLE ===== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
            font-size: {{ max($fontSize - 1, 9) }}px;
        }
        .data-table th,
        .data-table td {
            border: {{ $borderWidth }}px solid #9ca3af;
            padding: {{ $cellPadding }}px {{ max($cellPadding - 1, 3) }}px;
            vertical-align: middle;
        }

        /* Table header */
        .data-table thead th {
            background: linear-gradient(180deg, #1a56db 0%, #1e40af 100%);
            color: #ffffff;
            text-align: {{ $headerAlign }};
            font-weight: 700;
            font-size: {{ max($fontSize - 1, 9) }}px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: {{ $cellPadding + 2 }}px {{ max($cellPadding - 1, 3) }}px;
            border-color: #1e40af;
        }

        /* Sub-header for grouped columns */
        .data-table thead tr.sub-header th {
            background: #e0e7ff;
            color: #1e3a8a;
            font-weight: 600;
            font-size: {{ max($fontSize - 2, 8) }}px;
            border-color: #9ca3af;
        }

        /* Table body */
        .data-table tbody td {
            text-align: {{ $textAlign }};
            color: #1f2937;
        }
        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .data-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        /* Alignment helpers */
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .fw-bold { font-weight: 700; }
        .fw-semibold { font-weight: 600; }

        /* Detail kompetensi headers */
        .detail-head {
            font-size: {{ max($fontSize - 2, 8) }}px;
            line-height: 1.3;
        }
        .detail-subhead {
            display: block;
            color: #6b7280;
            font-size: {{ max($fontSize - 3, 7) }}px;
            font-weight: 400;
        }

        /* Rating badge style */
        .rating-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: {{ max($fontSize - 2, 8) }}px;
            font-weight: 700;
            text-align: center;
        }

        /* Alternating row number */
        .row-number {
            color: #6b7280;
            font-weight: 600;
        }

        /* ===== FOOTER SUMMARY ===== */
        .report-footer-info {
            margin-top: 12px;
            font-size: {{ max($fontSize - 2, 8) }}px;
            color: #6b7280;
        }

        /* ===== SIGNATURES ===== */
        .signatures {
            margin-top: 28px;
            width: 100%;
            display: table;
        }
        .sign-col {
            width: 50%;
            display: table-cell;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        .sign-space { height: 64px; }
        .sign-note { margin-bottom: 2px; font-weight: 600; color: #374151; }
        .sign-role { margin-bottom: 2px; color: #4a5568; font-size: {{ max($fontSize - 1, 9) }}px; }
        .sign-name {
            font-weight: 700;
            color: #1a1a2e;
            border-top: 1px solid #1a1a2e;
            padding-top: 3px;
            margin-top: 0;
            display: inline-block;
            min-width: 150px;
        }
        .sign-nip {
            font-size: {{ max($fontSize - 2, 8) }}px;
            color: #6b7280;
            margin-top: 1px;
        }

        /* ===== PAGE NUMBER ===== */
        .page-number {
            text-align: center;
            font-size: {{ max($fontSize - 2, 8) }}px;
            color: #9ca3af;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    {{-- ===== HEADER / KOP SURAT ===== --}}
    <div class="kop-header">
        <div class="kop-logo">
        @if($kopLogoSrc)
            <span class="kop-logo-box">
                <img class="logo" src="{{ $kopLogoSrc }}" alt="Logo">
            </span>
        @endif
        </div>
        <div class="kop-center">
            <div class="kop-title">Laporan Penilaian Pengabdian</div>
            <div class="kop-institution">{{ $kopInstitution }}</div>
            @if($kopAddress !== '')
                <div class="kop-contact">{{ $kopAddress }}</div>
            @endif
            @if($kopContact !== '')
                <div class="kop-contact">{{ $kopContact }}</div>
            @endif
        </div>
    </div>
    <hr class="kop-line">

    {{-- ===== REPORT TITLE ===== --}}
    <div class="report-title-section">
        <div class="report-main-title">Rekapitulasi Nilai Pengabdian</div>
        @if($setting?->show_tahun_ajaran)
            <div class="report-period">Periode: {{ $periodeLabel }}</div>
        @endif
    </div>

    {{-- ===== INFO BOX ===== --}}
    <div class="info-box">
        <div class="info-box-header">Informasi Laporan</div>
        <div class="info-box-body">
            <div class="info-row">
                <span class="info-label">Jenis Laporan</span>
                <span class="info-value">: {{ $jenisLaporan === 'rinci' ? 'Rinci (Detail Kompetensi)' : 'Ringkas (Per Kategori)' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Periode</span>
                <span class="info-value">: {{ $periodeLabel }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jumlah Karyawan</span>
                <span class="info-value">: {{ $totalKaryawan }} orang</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kategori Kinerja</span>
                <span class="info-value">: {{ $totalKategori }} kategori ({{ $totalKompetensiCount }} indikator)</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Penilaian</span>
                <span class="info-value">: {{ $reportFormat['scoring_method'] === 'weighted_kinerja_kegiatan' ? 'Rata-rata Berbobot (Kinerja '. $reportFormat['score_weight_kinerja'] .'% / Kegiatan '. $reportFormat['score_weight_kegiatan'] .'%)' : ($reportFormat['scoring_method'] === 'weighted_kategori' ? 'Rata-rata Berbobot per Kategori' : 'Rata-rata') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Cetak</span>
                <span class="info-value">: {{ now()->format('d F Y H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- ===== SUMMARY STATS ===== --}}
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-value">{{ $totalKaryawan }}</div>
            <div class="summary-label">Total Karyawan</div>
        </div>
        <div class="summary-item">
            <div class="summary-value green">{{ $avgScore !== null ? number_format($avgScore, 1) : '-' }}</div>
            <div class="summary-label">Rata-rata Nilai</div>
        </div>
        <div class="summary-item">
            <div class="summary-value orange">{{ $maxScore !== null ? number_format($maxScore, 1) : '-' }}</div>
            <div class="summary-label">Nilai Tertinggi</div>
        </div>
        <div class="summary-item">
            <div class="summary-value purple">{{ $minScore !== null ? number_format($minScore, 1) : '-' }}</div>
            <div class="summary-label">Nilai Terendah</div>
        </div>
    </div>

    {{-- ===== RATING DISTRIBUTION ===== --}}
    <div class="rating-dist">
        <div class="rating-dist-cell rating-a">
            <div class="rating-dist-letter">A</div>
            <div class="rating-dist-count">{{ $ratingDistribution['A'] }} orang</div>
        </div>
        <div class="rating-dist-cell rating-b">
            <div class="rating-dist-letter">B</div>
            <div class="rating-dist-count">{{ $ratingDistribution['B'] }} orang</div>
        </div>
        <div class="rating-dist-cell rating-c">
            <div class="rating-dist-letter">C</div>
            <div class="rating-dist-count">{{ $ratingDistribution['C'] }} orang</div>
        </div>
        <div class="rating-dist-cell rating-d">
            <div class="rating-dist-letter">D</div>
            <div class="rating-dist-count">{{ $ratingDistribution['D'] }} orang</div>
        </div>
        <div class="rating-dist-cell rating-e">
            <div class="rating-dist-letter">E</div>
            <div class="rating-dist-count">{{ $ratingDistribution['E'] }} orang</div>
        </div>
    </div>

    {{-- ===== SINGLE KARYAWAN PHOTO ===== --}}
    @if($karyawanFotoSrc)
    <div class="karyawan-photo-wrap">
        <div class="karyawan-photo-card">
            <div class="karyawan-photo-frame">
                <img src="{{ $karyawanFotoSrc }}" alt="Foto {{ $karyawanFotoNama !== '' ? $karyawanFotoNama : 'Karyawan' }}">
            </div>
            @if($karyawanFotoNama !== '')
                <div class="karyawan-photo-caption">{{ $karyawanFotoNama }}</div>
            @endif
        </div>
    </div>
    @endif

    {{-- ===== DATA TABLE ===== --}}
    <table class="data-table">
        <thead>
            <tr>
                @foreach($visibleColumns as $columnKey)
                    @if($columnKey === 'detail_kompetensi')
                        @if($jenisLaporan === 'ringkas')
                            @foreach($ringkasKategoriList as $kategori)
                                <th class="detail-head text-center">
                                    {{ $kategori->kategori }}
                                    @if(($reportFormat['show_bobot_kategori'] ?? true) && $kategori->bobot)
                                        <br><span style="font-size:{{ max($fontSize - 3, 7) }}px; font-weight:400; opacity:0.85;">Bobot {{ $kategori->bobot }}%</span>
                                    @endif
                                </th>
                            @endforeach
                        @else
                            @foreach($kategoriList as $kategori)
                                @if($kategori->kompetensi->isNotEmpty())
                                    <th class="detail-head text-center" colspan="{{ $kategori->kompetensi->count() }}">
                                        {{ $kategori->kategori }}
                                        @if(($reportFormat['show_bobot_kategori'] ?? true) && $kategori->bobot)
                                            <br><span style="font-size:{{ max($fontSize - 3, 7) }}px; font-weight:400; opacity:0.85;">Bobot {{ $kategori->bobot }}%</span>
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                        @endif
                    @else
                        <th width="{{ $colWidthMap[$columnKey] ?? 80 }}" {{ $detailHasGroupedHeader ? 'rowspan=2' : '' }} class="text-center">
                            {{ $labels[$columnKey] ?? ucfirst(str_replace('_', ' ', $columnKey)) }}
                        </th>
                    @endif
                @endforeach
            </tr>
            @if($detailHasGroupedHeader)
            <tr class="sub-header">
                @foreach($visibleColumns as $columnKey)
                    @if($columnKey === 'detail_kompetensi')
                        @foreach($kategoriList as $kategori)
                            @foreach($kategori->kompetensi as $kompetensi)
                                <th class="detail-head" width="60">
                                    {{ $kompetensi->kode_kompetensi }}
                                    <span class="detail-subhead">{{ $kompetensi->kompetensi }}</span>
                                </th>
                            @endforeach
                        @endforeach
                    @endif
                @endforeach
            </tr>
            @endif
        </thead>
        <tbody>
            @php
                $rowIndex = 0;
            @endphp
            @foreach($karyawanList as $i => $k)
                @php
                    $rowIndex++;
                    $kategoriUntukKaryawan = \App\Support\LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $k);
                    $applicableKompetensiIds = \App\Support\LaporanScoreCalculator::kompetensiIdsFromKategori($kategoriUntukKaryawan);
                    $trxByKompetensi = $k->transaksi
                        ->filter(fn($t) => $t->nilai !== null)
                        ->filter(fn($t) => $applicableKompetensiIds->contains((int) $t->kompetensi_id))
                        ->keyBy('kompetensi_id');
                    $nilaiAkhir = \App\Support\LaporanScoreCalculator::calculate(
                        $kategoriUntukKaryawan,
                        $trxByKompetensi,
                        $reportFormat['scoring_method'],
                        [
                            'bobot_kinerja' => $reportFormat['score_weight_kinerja'],
                            'bobot_kegiatan' => $reportFormat['score_weight_kegiatan'],
                        ]
                    );
                    $ratingMeta = \App\Support\LaporanScoreCalculator::ratingMeta($nilaiAkhir);
                @endphp
                <tr>
                    @foreach($visibleColumns as $columnKey)
                        @if($columnKey === 'no')
                            <td class="text-center row-number">{{ $rowIndex }}</td>
                        @elseif($columnKey === 'kode_karyawan')
                            <td class="text-center">{{ $k->kode_karyawan }}</td>
                        @elseif($columnKey === 'nama_karyawan')
                            <td class="fw-semibold">{{ $k->nama_karyawan }}</td>
                        @elseif($columnKey === 'pangkalan')
                            <td>{{ $k->pangkalan?->nama_pangkalan ?? '-' }}</td>
                        @elseif($columnKey === 'detail_kompetensi')
                            @if($jenisLaporan === 'ringkas')
                                @foreach($ringkasKategoriList as $kategori)
                                    @php
                                        $isApplicableKategori = $kategoriUntukKaryawan->contains(fn($item) => (int) $item->id === (int) $kategori->id);
                                        $kategoriValues = $kategori->kompetensi
                                            ->map(function ($komp) use ($trxByKompetensi) {
                                                $trx = $trxByKompetensi->get($komp->id);
                                                return ($trx && $trx->nilai !== null) ? (float) $trx->nilai : null;
                                            })
                                            ->filter(fn($nilai) => $nilai !== null)
                                            ->values();
                                        $kategoriAvg = $kategoriValues->isNotEmpty()
                                            ? ($kategoriValues->sum() / $kategoriValues->count())
                                            : null;
                                    @endphp
                                    <td class="text-center">
                                        @if(!$isApplicableKategori)
                                            <span style="color:#9ca3af;">-</span>
                                        @elseif($kategoriAvg !== null)
                                            <span class="fw-bold">{{ number_format($kategoriAvg, 2) }}</span>
                                        @else
                                            <span style="color:#9ca3af;">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                @foreach($detailKompetensiList as $kompetensi)
                                    @php $trx = $trxByKompetensi->get($kompetensi->id); @endphp
                                    <td class="text-center">
                                        @if(!$applicableKompetensiIds->contains((int) $kompetensi->id))
                                            <span style="color:#9ca3af;">-</span>
                                        @else
                                            {{ $trx && $trx->nilai !== null ? number_format($trx->nilai, 0) : '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            @endif
                        @elseif($columnKey === 'nilai_akhir')
                            <td class="text-center fw-bold" style="font-size:{{ $fontSize }}px;">
                                @if($nilaiAkhir !== null)
                                    {{ number_format($nilaiAkhir, 2) }}
                                @else
                                    <span style="color:#9ca3af;">-</span>
                                @endif
                            </td>
                        @elseif($columnKey === 'rating')
                            <td class="text-center">
                                @if($nilaiAkhir !== null)
                                    <span class="rating-badge" style="background-color:{{ $ratingMeta['bg'] ?? '#e5e7eb' }}; color:{{ $ratingMeta['text'] ?? '#374151' }};">
                                        {{ $ratingMeta['label'] }}
                                    </span>
                                @else
                                    <span style="color:#9ca3af;">-</span>
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            @if($karyawanList->isEmpty())
                <tr>
                    <td colspan="{{ count($visibleColumns) > 0 ? count($visibleColumns) : 7 }}" class="text-center" style="padding:20px; color:#9ca3af;">
                        Tidak ada data untuk ditampilkan.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- ===== FOOTER INFO ===== --}}
    <div class="report-footer-info">
        <em>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | Total data: {{ $totalKaryawan }} karyawan</em>
    </div>

    {{-- ===== SIGNATURES ===== --}}
    @if($setting && $setting->show_nama_pimpinan)
    @php
        $lokasiSurat = $setting->lokasi_surat ?: '................';
        $tanggalCetak = now()->format('d F Y');
    @endphp
    <div class="signatures">
        <div class="sign-col">
            <div class="sign-note">Mengetahui,</div>
            <div class="sign-role">Ketua Yayasan</div>
            <div class="sign-space">
                @if($setting->show_tanda_tangan && $setting->ttd_ketua_yayasan_path)
                    @php $ttdPath = public_path('storage/' . $setting->ttd_ketua_yayasan_path); @endphp
                    @if(is_file($ttdPath))
                        <img src="{{ $isPdfOutput ? $ttdPath : asset('storage/' . $setting->ttd_ketua_yayasan_path) }}" alt="TTD" style="max-height:60px;">
                    @endif
                @endif
            </div>
            <div><span class="sign-name">{{ $setting->nama_ketua_yayasan ?? '................' }}</span></div>
        </div>
        <div class="sign-col">
            <div class="sign-note">{{ $lokasiSurat }}, {{ $tanggalCetak }}</div>
            <div class="sign-role">Ketua Babinlumni</div>
            <div class="sign-space">
                @if($setting->show_tanda_tangan && $setting->ttd_ketua_babinlumni_path)
                    @php $ttdPath = public_path('storage/' . $setting->ttd_ketua_babinlumni_path); @endphp
                    @if(is_file($ttdPath))
                        <img src="{{ $isPdfOutput ? $ttdPath : asset('storage/' . $setting->ttd_ketua_babinlumni_path) }}" alt="TTD" style="max-height:60px;">
                    @endif
                @endif
            </div>
            <div><span class="sign-name">{{ $setting->nama_ketua_babinlumni ?? '................' }}</span></div>
        </div>
    </div>
    @endif
</body>
</html>
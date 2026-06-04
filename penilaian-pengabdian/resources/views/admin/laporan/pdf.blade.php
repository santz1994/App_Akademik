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
    @endphp
    <style>
        @page {
            size: {{ $paperSize }} {{ $orientation }};
            margin: {{ $marginTop }}cm {{ $marginRight }}cm {{ $marginBottom }}cm {{ $marginLeft }}cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: {{ $fontSize }}px;
            color: #111827;
            text-align: {{ $textAlign }};
        }

        .kop-wrap { width: 100%; display: table; margin-bottom: 2px; }
        .kop-logo { width: {{ $kopLogoSlotWidth }}px; display: table-cell; vertical-align: middle; text-align: left; }
        .kop-center { display: table-cell; vertical-align: middle; text-align: center; padding-right: {{ $kopLogoSlotWidth }}px; }
        .kop-title { font-size: {{ $titleFontSize }}px; font-weight: bold; text-transform: uppercase; margin-bottom: 3px; }
        .kop-subtitle { font-size: {{ max($fontSize - 1, 9) }}px; color: #111827; line-height: 1.35; }
        .kop-subtitle-main { text-transform: uppercase; font-weight: 600; }
        .kop-contact { font-size: {{ max($fontSize - 2, 8) }}px; color: #334155; line-height: 1.3; }
        .kop-line { border-top: 2px solid #111827; border-bottom: 1px solid #111827; height: 2px; margin: 8px 0 12px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: {{ $borderWidth }}px solid #d1d5db; padding: {{ $cellPadding }}px; vertical-align: top; }
        th { background: #f3f4f6; text-align: {{ $headerAlign }}; }
        td { text-align: {{ $textAlign }}; }

        .text-center { text-align: center; }
        .detail-head { font-size: {{ max($fontSize - 2, 8) }}px; }
        .detail-subhead { display:block; color:#475569; font-size: {{ max($fontSize - 3, 7) }}px; }

        .signatures { margin-top: 28px; width: 100%; }
        .sign-col { width: 50%; text-align: center; float: left; }
        .sign-space { height: 64px; }
        .sign-note { margin-bottom: 2px; }
        .sign-role { margin-bottom: 2px; }
        .clearfix::after { content: ""; display: table; clear: both; }
        .kop-logo-box {
            width: {{ $kopLogoBoxSize }}px;
            height: {{ $kopLogoBoxSize }}px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
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
            border: 1px solid #cbd5e1;
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
            max-width: 140px;
        }
    </style>
</head>
<body>
    <div class="kop-wrap">
        <div class="kop-logo">
        @if($kopLogoSrc)
            <span class="kop-logo-box">
                <img class="logo" src="{{ $kopLogoSrc }}" alt="Logo">
            </span>
        @endif
        </div>
        <div class="kop-center">
            <div class="kop-title">Laporan Penilaian Pengabdian</div>
            <div class="kop-subtitle kop-subtitle-main">{{ $kopInstitution }}</div>
            @if($kopAddress !== '')
                <div class="kop-contact">{{ $kopAddress }}</div>
            @endif
            @if($kopContact !== '')
                <div class="kop-contact">{{ $kopContact }}</div>
            @endif
            @if($setting?->show_tahun_ajaran)
                <div class="kop-subtitle">Tahun Ajaran: {{ $selectedTahunData?->periode_penilaian ?? '-' }}</div>
            @endif
        </div>
    </div>
    <div class="kop-line"></div>

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

    <table>
        <thead>
            <tr>
                @foreach($visibleColumns as $columnKey)
                    @if($columnKey === 'detail_kompetensi')
                        @if($jenisLaporan === 'ringkas')
                            @foreach($ringkasKategoriList as $kategori)
                                <th class="detail-head">{{ $kategori->kategori }}</th>
                            @endforeach
                        @else
                            @foreach($kategoriList as $kategori)
                                @if($kategori->kompetensi->isNotEmpty())
                                    <th class="detail-head" colspan="{{ $kategori->kompetensi->count() }}">
                                        {{ $kategori->kategori }}
                                    </th>
                                @endif
                            @endforeach
                        @endif
                    @else
                        <th width="{{ $colWidthMap[$columnKey] ?? 80 }}" {{ $detailHasGroupedHeader ? 'rowspan=2' : '' }}>
                            {{ $labels[$columnKey] ?? ucfirst(str_replace('_', ' ', $columnKey)) }}
                        </th>
                    @endif
                @endforeach
            </tr>
            @if($detailHasGroupedHeader)
            <tr>
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
            @foreach($karyawanList as $i => $k)
                @php
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
                            <td class="text-center">{{ $i + 1 }}</td>
                        @elseif($columnKey === 'kode_karyawan')
                            <td>{{ $k->kode_karyawan }}</td>
                        @elseif($columnKey === 'nama_karyawan')
                            <td>{{ $k->nama_karyawan }}</td>
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
                                            -
                                        @elseif($kategoriAvg !== null)
                                            {{ number_format($kategoriAvg, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                @foreach($detailKompetensiList as $kompetensi)
                                    @php $trx = $trxByKompetensi->get($kompetensi->id); @endphp
                                    <td class="text-center">
                                        @if(!$applicableKompetensiIds->contains((int) $kompetensi->id))
                                            -
                                        @else
                                            {{ $trx && $trx->nilai !== null ? number_format($trx->nilai, 0) : '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            @endif
                        @elseif($columnKey === 'nilai_akhir')
                            <td class="text-center">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}</td>
                        @elseif($columnKey === 'rating')
                            <td class="text-center">{{ $ratingMeta['label'] }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($setting && $setting->show_nama_pimpinan)
    @php
        $lokasiSurat = $setting->lokasi_surat ?: '................';
        $tanggalCetak = now()->format('d-m-Y');
    @endphp
    <div class="signatures clearfix">
        <div class="sign-col">
            <div class="sign-note">Mengetahui,</div>
            <div class="sign-role">Ketua Yayasan</div>
            <div class="sign-space">
                @if($setting->show_tanda_tangan && $setting->ttd_ketua_yayasan_path)
                    <img src="{{ public_path('storage/' . $setting->ttd_ketua_yayasan_path) }}" alt="TTD Ketua Yayasan" style="max-height:60px;">
                @endif
            </div>
            <div><strong>{{ $setting->nama_ketua_yayasan ?? '-' }}</strong></div>
        </div>
        <div class="sign-col">
            <div class="sign-note">{{ $lokasiSurat }}, {{ $tanggalCetak }}</div>
            <div class="sign-role">Ketua Babinlumni</div>
            <div class="sign-space">
                @if($setting->show_tanda_tangan && $setting->ttd_ketua_babinlumni_path)
                    <img src="{{ public_path('storage/' . $setting->ttd_ketua_babinlumni_path) }}" alt="TTD Ketua Babinlumni" style="max-height:60px;">
                @endif
            </div>
            <div><strong>{{ $setting->nama_ketua_babinlumni ?? '-' }}</strong></div>
        </div>
    </div>
    @endif
</body>
</html>

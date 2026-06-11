@php
    $jenisLaporan = $jenisLaporan ?? 'ringkas';
    $reportFormat = array_merge([
        'show_no' => true,
        'show_kode_karyawan' => true,
        'show_pangkalan' => true,
        'show_nilai_akhir' => true,
        'show_rating' => true,
        'show_detail_kompetensi' => true,
        'text_align' => 'left',
        'header_align' => 'center',
        'cell_padding' => 6,
        'border_width' => 1,
        'font_size' => 11,
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

    $textAlign = $reportFormat['text_align'];
    $headerAlign = $reportFormat['header_align'];
    $cellPadding = (int) $reportFormat['cell_padding'];
    $borderWidth = number_format((float) $reportFormat['border_width'], 1, '.', '');
    $fontSize = (int) $reportFormat['font_size'];

    $colWidthMap = [
        'no' => (int) $reportFormat['col_width_no'],
        'kode_karyawan' => (int) $reportFormat['col_width_kode'],
        'nama_karyawan' => (int) $reportFormat['col_width_nama'],
        'pangkalan' => (int) $reportFormat['col_width_pangkalan'],
        'nilai_akhir' => (int) $reportFormat['col_width_nilai'],
        'rating' => (int) $reportFormat['col_width_rating'],
    ];

    $colspan = 0;
    foreach ($visibleColumns as $columnKey) {
        if ($columnKey === 'detail_kompetensi') {
            $colspan += $jenisLaporan === 'ringkas'
                ? $ringkasKategoriList->count()
                : $detailKompetensiList->count();
        } else {
            $colspan++;
        }
    }
@endphp

<table style="border-collapse:collapse; width:100%; font-size:{{ $fontSize }}px;">
    <thead>
        <tr>
            <th colspan="{{ max($colspan, 1) }}" style="text-align:center; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">Laporan Penilaian Pengabdian</th>
        </tr>
        <tr>
            <th colspan="{{ max($colspan, 1) }}" style="text-align:center; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">Tahun: {{ $selectedTahunData?->periode_penilaian ?? '-' }}</th>
        </tr>
        <tr>
            @foreach($visibleColumns as $columnKey)
                @if($columnKey === 'detail_kompetensi')
                    @if($jenisLaporan === 'ringkas')
                        @foreach($ringkasKategoriList as $kategori)
                            <th style="text-align:{{ $headerAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $kategori->kategori }}</th>
                        @endforeach
                    @else
                        @foreach($kategoriList as $kategori)
                            @if($kategori->kompetensi->isNotEmpty())
                                <th colspan="{{ $kategori->kompetensi->count() }}" style="text-align:{{ $headerAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $kategori->kategori }}</th>
                            @endif
                        @endforeach
                    @endif
                @else
                    <th style="width:{{ $colWidthMap[$columnKey] ?? 80 }}px; text-align:{{ $headerAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;" {{ $detailHasGroupedHeader ? 'rowspan=2' : '' }}>{{ $labels[$columnKey] ?? ucfirst(str_replace('_', ' ', $columnKey)) }}</th>
                @endif
            @endforeach
        </tr>
        @if($detailHasGroupedHeader)
        <tr>
            @foreach($visibleColumns as $columnKey)
                @if($columnKey === 'detail_kompetensi')
                    @foreach($kategoriList as $kategori)
                        @foreach($kategori->kompetensi as $kompetensi)
                            <th style="text-align:{{ $headerAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">
                                {{ $kompetensi->kode_kompetensi }}
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
                // Only include transaksi with valid pangkalan_id (matching karyawan's pangkalans)
                $kPangkalanIds = $k->getAllPangkalanIds();
                $allTrx = $k->transaksi->filter(fn($t) => $t->nilai !== null && in_array((int) ($t->pangkalan_id ?? 0), $kPangkalanIds, true));
                // Key by kompetensi_id:kategori_kinerja_id to handle shared kompetensi
                $trxByKompetensi = $allTrx
                    ->filter(fn($t) => $applicableKompetensiIds->contains((int) $t->kompetensi_id))
                    ->mapWithKeys(fn($t) => [(int) $t->kompetensi_id . ':' . (int) ($t->kategori_kinerja_id ?? 0) => $t]);
                // Enrich: ensure shared kompetensi (belonging to multiple kategoris) has entries under all kategoris
                $trxByKompetensi = \App\Support\LaporanScoreCalculator::enrichTrxForSharedKompetensi($trxByKompetensi, $kategoriUntukKaryawan);
                // Use unified per-pangkalan calculation for consistent scoring
                $scoreResult = \App\Support\LaporanScoreCalculator::calculateNilaiAkhirForKaryawan(
                    $kategoriUntukKaryawan,
                    $k,
                    [
                        'bobot_kinerja' => $reportFormat['score_weight_kinerja'],
                        'bobot_kegiatan' => $reportFormat['score_weight_kegiatan'],
                    ]
                );
                $nilaiAkhir = $scoreResult['nilaiAkhir'];
                $ratingMeta = \App\Support\LaporanScoreCalculator::ratingMeta($nilaiAkhir);
            @endphp
            <tr>
                @foreach($visibleColumns as $columnKey)
                    @if($columnKey === 'no')
                        <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $i + 1 }}</td>
                    @elseif($columnKey === 'kode_karyawan')
                        <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $k->kode_karyawan }}</td>
                    @elseif($columnKey === 'nama_karyawan')
                        <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $k->nama_karyawan }}</td>
                    @elseif($columnKey === 'pangkalan')
                        <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $k->pangkalan?->nama_pangkalan ?? '-' }}</td>
                    @elseif($columnKey === 'detail_kompetensi')
                        @if($jenisLaporan === 'ringkas')
                            @foreach($ringkasKategoriList as $kategori)
                                @php
                                    $isApplicableKategori = $kategoriUntukKaryawan->contains(fn($item) => (int) $item->id === (int) $kategori->id);
                                    $kategoriValues = $kategori->kompetensi
                                        ->map(function ($komp) use ($trxByKompetensi, $kategori) {
                                            $trx = $trxByKompetensi->get((int) $komp->id . ':' . (int) $kategori->id);
                                            if ($trx && $trx->nilai !== null) {
                                                return (float) $trx->nilai;
                                            }
                                            return null;
                                        })
                                        ->filter(fn($nilai) => $nilai !== null)
                                        ->values();
                                    $kategoriAvg = $kategoriValues->isNotEmpty()
                                        ? ($kategoriValues->sum() / $kategoriValues->count())
                                        : null;
                                @endphp
                                <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">
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
                                @php
                                    $katId = $kompetensi->kategoriKinerja->first()?->id ?? 0;
                                    $trx = $trxByKompetensi->get((int) $kompetensi->id . ':' . (int) $katId);
                                @endphp
                                <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">
                                    @if(!$applicableKompetensiIds->contains((int) $kompetensi->id))
                                        -
                                    @else
                                        {{ $trx && $trx->nilai !== null ? number_format($trx->nilai, 0) : '-' }}
                                    @endif
                                </td>
                            @endforeach
                        @endif
                    @elseif($columnKey === 'nilai_akhir')
                        <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}</td>
                    @elseif($columnKey === 'rating')
                        <td style="text-align:{{ $textAlign }}; border:{{ $borderWidth }}px solid #999; padding:{{ $cellPadding }}px;">{{ $ratingMeta['label'] }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

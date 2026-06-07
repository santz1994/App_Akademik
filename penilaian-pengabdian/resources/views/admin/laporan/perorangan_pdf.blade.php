<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Pegawai</title>
    @php
        $logoRelativePath = ($setting && $setting->show_logo) ? trim((string) ($setting->logo_path ?? '')) : '';
        $logoSrc = null;
        if ($logoRelativePath !== '') {
            $normalizedLogoPath = ltrim($logoRelativePath, '/');
            $publicLogoPath = public_path('storage/' . $normalizedLogoPath);
            if (is_file($publicLogoPath)) {
                $logoSrc = $publicLogoPath;
            }
        }

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

        $kinerjaKategori = $kategoriList->filter(fn($k) => strtolower((string) $k->jenis) === 'kinerja')->values();
        $kegiatanKategori = $kategoriList->filter(fn($k) => strtolower((string) $k->jenis) === 'kegiatan')->values();
    @endphp
    <style>
        @page {
            size: A4 portrait;
            margin: 2cm 2cm 2cm 2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #0f172a;
        }

        .header-wrap { width: 100%; display: table; margin-bottom: 8px; }
        .header-logo { width: 84px; display: table-cell; vertical-align: middle; }
        .header-logo-box {
            width: 66px;
            height: 66px;
            border: 1px solid #dbe2ea;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            overflow: hidden;
        }
        .header-logo-box img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }
        .header-center { display: table-cell; vertical-align: middle; text-align: center; padding-right: 84px; }
        .header-title { margin: 0; font-size: 16px; font-weight: 700; text-transform: uppercase; }
        .header-subtitle { margin-top: 3px; font-size: 11px; color: #1e293b; line-height: 1.35; }
        .header-subtitle-main { text-transform: uppercase; font-weight: 600; }
        .header-contact { margin-top: 2px; font-size: 10px; color: #334155; line-height: 1.3; }
        .header-line { border-top: 2px solid #0f172a; border-bottom: 1px solid #0f172a; height: 2px; margin: 10px 0 14px; }

        .info-section { margin-bottom: 14px; }
        .info-section table { width: 100%; border-collapse: collapse; }
        .info-section td { padding: 3px 8px; vertical-align: top; font-size: 11px; }
        .info-label { width: 140px; font-weight: 600; color: #475569; }
        .info-value { color: #0f172a; }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
            margin: 14px 0 6px;
            padding-bottom: 4px;
            border-bottom: 2px solid #3b82f6;
        }

        table.score-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.score-table th,
        table.score-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 7px;
            vertical-align: middle;
            line-height: 1.35;
        }
        table.score-table th {
            background: #f1f5f9;
            font-weight: 700;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.score-table td.nama { }
        table.score-table td.nilai { width: 80px; text-align: center; font-weight: 600; }
        table.score-table td.kategori-avg { text-align: center; font-weight: 700; font-size: 12px; }
        table.score-table tr.kategori-header td {
            background: #e2e8f0;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
        }

        .summary-box {
            margin-top: 16px;
            border: 2px solid #1e293b;
            border-radius: 8px;
            padding: 12px 16px;
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            width: 33%;
        }
        .summary-item + .summary-item {
            border-left: 1px solid #cbd5e1;
        }
        .summary-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }
        .summary-sub {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="header-wrap">
        <div class="header-logo">
            @if($logoSrc)
                <span class="header-logo-box">
                    <img src="{{ $logoSrc }}" alt="Logo">
                </span>
            @endif
        </div>
        <div class="header-center">
            <h1 class="header-title">Laporan Nilai Pegawai</h1>
            <div class="header-subtitle header-subtitle-main">{{ $kopInstitution }}</div>
            @if($kopAddress !== '')
                <div class="header-contact">{{ $kopAddress }}</div>
            @endif
            @if($kopContact !== '')
                <div class="header-contact">{{ $kopContact }}</div>
            @endif
            <div class="header-subtitle">Tahun Penilaian: {{ $selectedTahunData?->periode_penilaian ?? '-' }}</div>
        </div>
    </div>
    <div class="header-line"></div>

    {{-- Informasi Karyawan --}}
    <div class="info-section">
        <table>
            <tr>
                <td class="info-label">Nama Karyawan</td>
                <td class="info-value">: <strong>{{ $karyawan->nama_karyawan }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Pangkalan Job</td>
                <td class="info-value">:
                    @if(isset($allPangkalan) && $allPangkalan->isNotEmpty())
                        {{ $allPangkalan->map(fn($p) => $p->nama_pangkalan)->implode(', ') }}
                    @else
                        {{ $karyawan->pangkalan ? $karyawan->pangkalan->nama_pangkalan : '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="info-label">Nomor Induk</td>
                <td class="info-value">: {{ $karyawan->nomor_induk ?: '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Status</td>
                <td class="info-value">: {{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
        </table>
    </div>

    {{-- Nilai Kinerja --}}
    @if($kinerjaKategori->isNotEmpty())
    @if(isset($perPangkalanData) && count($perPangkalanData['perPangkalan']) > 1)
    {{-- Multi-pangkalan: show per-pangkalan breakdown with pangkalan name --}}
    @foreach($perPangkalanData['perPangkalan'] as $ppData)
    @php
        // Use per-pangkalan transaksi if available
        $pangkalanTrx = isset($trxByPangkalan[$ppData['pangkalan_id']]) ? $trxByPangkalan[$ppData['pangkalan_id']] : $trxByKompetensi;
    @endphp
    <div style="margin-bottom: 10px;">
        <div style="font-size: 12px; font-weight: 700; color: #1e293b; margin-bottom: 6px; background: #e2e8f0; padding: 6px 10px; border-left: 4px solid #3b82f6;">
            @if($ppData['pangkalan'])
                {{ $ppData['pangkalan']->nama_pangkalan }}
            @else
                Pangkalan #{{ $ppData['pangkalan_id'] }}
            @endif
        </div>
        <div class="section-title" style="margin-top: 4px;">Nilai Kinerja</div>
    <table class="score-table">
        <thead>
            <tr>
                <th>Indikator Kompetensi</th>
                <th class="nilai">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ppData['kategoriDetails'] as $kd)
                @foreach($kd['kategori']->kompetensi as $komp)
                    @php
                        $t = $pangkalanTrx->get($komp->id);
                        $nilai = ($t && $t->nilai !== null) ? (float) $t->nilai : null;
                    @endphp
                    <tr>
                        <td>{{ $komp->kompetensi }}</td>
                        <td class="nilai">{{ $nilai !== null ? number_format($nilai, 0) : '-' }}</td>
                    </tr>
                @endforeach
                <tr class="kategori-header">
                    <td>Rata-rata {{ $kd['kategori']->kategori }}</td>
                    <td class="kategori-avg">{{ $kd['average'] !== null ? number_format($kd['average'], 2) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="score-table" style="margin-top: 2px;">
        <tbody>
            <tr class="kategori-header">
                <td style="font-size: 11px;">Rata-Rata {{ $ppData['pangkalan'] ? $ppData['pangkalan']->nama_pangkalan : 'Pangkalan #' . $ppData['pangkalan_id'] }}</td>
                <td class="kategori-avg" style="font-size: 12px;">{{ $ppData['kinerjaAvg'] !== null ? number_format($ppData['kinerjaAvg'], 2) : '-' }}</td>
            </tr>
        </tbody>
    </table>
    </div>
    @endforeach
    @else
    {{-- Single pangkalan: standard layout --}}
    <div class="section-title">Nilai Kinerja</div>
    <table class="score-table">
        <thead>
            <tr>
                <th>Indikator Kompetensi</th>
                <th class="nilai">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kinerjaKategori as $kat)
                @php
                    $kategoriNilai = [];
                @endphp
                @foreach($kat->kompetensi as $komp)
                    @php
                        $t = $trxByKompetensi->get($komp->id);
                        $nilai = ($t && $t->nilai !== null) ? (float) $t->nilai : null;
                        if ($nilai !== null) { $kategoriNilai[] = $nilai; }
                    @endphp
                    <tr>
                        <td>{{ $komp->kompetensi }}</td>
                        <td class="nilai">{{ $nilai !== null ? number_format($nilai, 0) : '-' }}</td>
                    </tr>
                @endforeach
                @if(count($kategoriNilai) > 0)
                <tr class="kategori-header">
                    <td>Rata-rata {{ $kat->kategori }}</td>
                    <td class="kategori-avg">{{ number_format(array_sum($kategoriNilai) / count($kategoriNilai), 2) }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    @endif
    @endif

    {{-- Nilai Kegiatan --}}
    @if($kegiatanKategori->isNotEmpty())
    <div class="section-title">Nilai Kegiatan</div>
    @foreach($kegiatanKategori as $kat)
    <div style="margin-bottom: 8px;">
        <div style="font-size: 10px; font-weight: 700; color: #1e293b; margin-bottom: 4px; background: #dcfce7; padding: 4px 8px; border-left: 4px solid #22c55e;">
            {{ $kat->kategori }}
            @if($kat->is_wajib)
                <span style="color: #dc2626; font-size: 9px;"> (Wajib)</span>
            @endif
        </div>
    <table class="score-table">
        <thead>
            <tr>
                <th>Indikator Kompetensi</th>
                <th class="nilai">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php $kategoriNilai = []; @endphp
            @foreach($kat->kompetensi as $komp)
                @php
                    $t = $trxByKompetensi->get($komp->id);
                    $nilai = ($t && $t->nilai !== null) ? (float) $t->nilai : null;
                    if ($nilai !== null) { $kategoriNilai[] = $nilai; }
                @endphp
                <tr>
                    <td>{{ $komp->kompetensi }}</td>
                    <td class="nilai">{{ $nilai !== null ? number_format($nilai, 0) : '-' }}</td>
                </tr>
            @endforeach
            <tr class="kategori-header">
                <td>Rata-rata {{ $kat->kategori }}</td>
                <td class="kategori-avg">{{ count($kategoriNilai) > 0 ? number_format(array_sum($kategoriNilai) / count($kategoriNilai), 2) : '-' }}</td>
            </tr>
        </tbody>
    </table>
    </div>
    @endforeach
    @endif

    {{-- Nilai Akhir --}}
    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">Nilai Akhir Kinerja</div>
            <div class="summary-value">
                @php
                    $avgKinerja = (isset($perPangkalanData) && $perPangkalanData['kinerjaFinal'] !== null)
                        ? $perPangkalanData['kinerjaFinal']
                        : null;
                    if ($avgKinerja === null) {
                        $kinerjaNilai = [];
                        foreach ($kinerjaKategori as $kat) {
                            foreach ($kat->kompetensi as $komp) {
                                $t = $trxByKompetensi->get($komp->id);
                                if ($t && $t->nilai !== null) { $kinerjaNilai[] = (float) $t->nilai; }
                            }
                        }
                        $avgKinerja = count($kinerjaNilai) > 0 ? array_sum($kinerjaNilai) / count($kinerjaNilai) : null;
                    }
                @endphp
                {{ $avgKinerja !== null ? number_format($avgKinerja, 2) : '-' }}
            </div>
            @if(isset($perPangkalanData) && count($perPangkalanData['perPangkalan']) > 1)
            <div class="summary-sub">Rata-rata {{ count($perPangkalanData['perPangkalan']) }} pangkalan</div>
            @endif
        </div>
        <div class="summary-item">
            <div class="summary-label">Nilai Akhir Kegiatan</div>
            <div class="summary-value">
                @php
                    $avgKegiatan = (isset($perPangkalanData) && $perPangkalanData['kegiatanAvg'] !== null)
                        ? $perPangkalanData['kegiatanAvg']
                        : null;
                    if ($avgKegiatan === null) {
                        $kegiatanNilai = [];
                        foreach ($kegiatanKategori as $kat) {
                            foreach ($kat->kompetensi as $komp) {
                                $t = $trxByKompetensi->get($komp->id);
                                if ($t && $t->nilai !== null) { $kegiatanNilai[] = (float) $t->nilai; }
                            }
                        }
                        $avgKegiatan = count($kegiatanNilai) > 0 ? array_sum($kegiatanNilai) / count($kegiatanNilai) : null;
                    }
                @endphp
                {{ $avgKegiatan !== null ? number_format($avgKegiatan, 2) : '-' }}
            </div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Nilai Akhir ({{ $reportFormat['score_weight_kinerja'] ?? 70 }}% Kinerja + {{ $reportFormat['score_weight_kegiatan'] ?? 30 }}% Kegiatan)</div>
            <div class="summary-value">{{ $nilaiAkhir !== null ? number_format($nilaiAkhir, 2) : '-' }}</div>
            <div class="summary-sub">
                @if($nilaiAkhir !== null)
                    {{ $ratingMeta['label'] }}
                @endif
            </div>
        </div>
    </div>

    {{-- Keterangan Reward & Punishment --}}
    @php
        $rpInfo = \App\Support\LaporanScoreCalculator::getRewardPunishmentInfo($nilaiAkhir);
    @endphp
    @if($rpInfo && $rpInfo['items']->isNotEmpty())
    <div style="margin-top: 14px; border: 2px solid {{ $rpInfo['grade'] === 'C' || $rpInfo['grade'] === 'D' ? '#dc2626' : '#22c55e' }}; border-radius: 8px; padding: 10px 14px; background: {{ $rpInfo['grade'] === 'C' || $rpInfo['grade'] === 'D' ? '#fef2f2' : '#f0fdf4' }};">
        <div style="font-size: 11px; font-weight: 700; margin-bottom: 6px; color: {{ $rpInfo['grade'] === 'C' || $rpInfo['grade'] === 'D' ? '#dc2626' : '#16a34a' }};">
            @if($rpInfo['grade'] === 'C' || $rpInfo['grade'] === 'D')
                <i>⚠ KETERANGAN HUKUMAN</i>
            @else
                <i>✓ KETERANGAN REWARD</i>
            @endif
        </div>
        @foreach($rpInfo['items'] as $rpItem)
        <div style="font-size: 10px; margin-bottom: 4px;">
            @if($rpItem['tipe'] === 'punishment' || (isset($rpItem['tipe']) && $rpItem['tipe'] === 'punishment'))
                <strong>{{ $rpItem['nama'] ?? 'Hukuman' }}:</strong>
                Karyawan yang mendapatkan nilai akhir <strong>Grade {{ $rpInfo['grade'] }}</strong>
                mendapatkan hukuman berupa
                @if(isset($rpItem['jumlah']) && $rpItem['jumlah'] > 0)
                    <strong>{{ $rpItem['jumlah'] }} {{ $rpItem['satuan'] ?? '' }}</strong>.
                @else
                    {{ $rpItem['deskripsi'] ?? '' }}
                @endif
            @else
                <strong>{{ $rpItem['nama'] ?? 'Reward' }}:</strong>
                {{ $rpItem['deskripsi'] ?? '' }}
            @endif
        </div>
        @endforeach
    </div>
    @endif
</body>
</html>

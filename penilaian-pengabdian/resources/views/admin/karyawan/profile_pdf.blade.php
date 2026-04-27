<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Karyawan</title>
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

        $jenisKelaminLabel = '-';
        if ($karyawan->jenis_kelamin === 'L') {
            $jenisKelaminLabel = 'Laki-laki';
        } elseif ($karyawan->jenis_kelamin === 'P') {
            $jenisKelaminLabel = 'Perempuan';
        }
    @endphp
    <style>
        @page {
            size: A4 portrait;
            margin: 2.2cm 2.2cm 2cm 2.2cm;
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
        .header-title { margin: 0; font-size: 17px; font-weight: 700; text-transform: uppercase; }
        .header-subtitle { margin-top: 3px; font-size: 11px; color: #1e293b; line-height: 1.35; }
        .header-subtitle-main { text-transform: uppercase; font-weight: 600; }
        .header-contact { margin-top: 2px; font-size: 10px; color: #334155; line-height: 1.3; }
        .header-line { border-top: 2px solid #0f172a; border-bottom: 1px solid #0f172a; height: 2px; margin: 10px 0 14px; }

        .profile-meta { margin-bottom: 10px; }
        .profile-meta strong { font-size: 12px; }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 7px;
            vertical-align: top;
            line-height: 1.35;
        }
        th {
            background: #f1f5f9;
            font-weight: 700;
            text-align: center;
        }

        .col-attr { width: 28%; }
        .col-value { width: 34%; }
        .col-note { width: 38%; }

        .text-muted { color: #64748b; }
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
            <h1 class="header-title">Profil Karyawan</h1>
            <div class="header-subtitle header-subtitle-main">{{ $kopInstitution }}</div>
            @if($kopAddress !== '')
                <div class="header-contact">{{ $kopAddress }}</div>
            @endif
            @if($kopContact !== '')
                <div class="header-contact">{{ $kopContact }}</div>
            @endif
            <div class="header-subtitle">Tahun Penilaian: {{ $karyawan->tahunPenilaian?->periode_penilaian ?? '-' }}</div>
        </div>
    </div>
    <div class="header-line"></div>

    <div class="profile-meta">
        <strong>{{ $karyawan->nama_karyawan }}</strong>
        <span class="text-muted">(Kode: {{ $karyawan->kode_karyawan }})</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-attr">Atribut</th>
                <th class="col-value">Isian</th>
                <th class="col-note">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Kode Karyawan</td>
                <td>{{ $karyawan->kode_karyawan }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Nama Karyawan</td>
                <td>{{ $karyawan->nama_karyawan }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Nomor Induk</td>
                <td>{{ $karyawan->nomor_induk ?: '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>{{ $jenisKelaminLabel }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Nomor Surat Tugas</td>
                <td>{{ $karyawan->nomor_surat_tugas ?: '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Tanggal Surat Tugas</td>
                <td>{{ $karyawan->tanggal_surat_tugas ? $karyawan->tanggal_surat_tugas->format('d-m-Y') : '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Status Karyawan</td>
                <td>{{ $karyawan->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Pangkalan Job</td>
                <td>
                    @if($karyawan->pangkalan)
                        {{ $karyawan->pangkalan->kode_pangkalan }} - {{ $karyawan->pangkalan->nama_pangkalan }}
                    @else
                        -
                    @endif
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Tugas Khusus</td>
                <td>{{ $karyawan->tugas_khusus ?: '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>{{ $karyawan->alamat ?: '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>User Account</td>
                <td>
                    @if($karyawan->user)
                        {{ $karyawan->user->name }} ({{ $karyawan->user->username }})
                    @else
                        -
                    @endif
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Tahun Penilaian</td>
                <td>{{ $karyawan->tahunPenilaian?->periode_penilaian ?? '-' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Foto Profil 3x4</td>
                <td>{{ $karyawan->foto_path ? 'Tersedia' : 'Tidak tersedia' }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>

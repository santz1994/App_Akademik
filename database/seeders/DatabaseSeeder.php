<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\KategoriKinerja;
use App\Models\Kompetensi;
use App\Models\Pangkalan;
use App\Models\PerformanceRating;
use App\Models\TahunPenilaian;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@penilaian.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'User Penilai',
            'username' => 'user',
            'email' => 'user@penilaian.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@penilaian.com',
            'password' => Hash::make('budi123'),
            'role' => 'user',
        ]);

        // ── Tahun Penilaian ────────────────────────────────
        $tahun = TahunPenilaian::create([
            'periode_penilaian' => '2023/2024',
            'keterangan' => 'Tahun Pengabdian 2023/2024',
            'is_active' => true,
        ]);

        // ── Kategori Kinerja ───────────────────────────────
        $kat1 = KategoriKinerja::create(['kode_kategori' => 'KTG-001', 'kategori' => 'Kedisiplinan', 'jenis' => 'kinerja',  'bobot' => 25]);
        $kat2 = KategoriKinerja::create(['kode_kategori' => 'KTG-002', 'kategori' => 'Karakter',     'jenis' => 'kinerja',  'bobot' => 30]);
        $kat3 = KategoriKinerja::create(['kode_kategori' => 'KTG-003', 'kategori' => 'Kompetensi',   'jenis' => 'kinerja',  'bobot' => 25]);
        $kat4 = KategoriKinerja::create(['kode_kategori' => 'KTG-004', 'kategori' => 'Kegiatan Wajib', 'jenis' => 'kegiatan', 'bobot' => 20]);

        // ── Kompetensi ─────────────────────────────────────
        $kompetensiData = [
            [$kat1->id, 'KMP-001', 'Kehadiran'],
            [$kat1->id, 'KMP-002', 'Tepat Waktu'],
            [$kat1->id, 'KMP-003', 'Penggunaan Waktu'],
            [$kat2->id, 'KMP-004', 'Kepatuhan'],
            [$kat2->id, 'KMP-005', 'Loyalitas'],
            [$kat2->id, 'KMP-006', 'Leadership'],
            [$kat2->id, 'KMP-007', 'Tanggung-jawab'],
            [$kat3->id, 'KMP-008', 'Inisiatif'],
            [$kat3->id, 'KMP-009', 'Adaptasi'],
            [$kat3->id, 'KMP-010', 'Pemecahan Masalah'],
            [$kat3->id, 'KMP-011', 'Pengambilan Keputusan'],

            // Kegiatan Wajib
            [$kat4->id, 'KMP-012', 'Keaktifan Mengaji'],
            [$kat4->id, 'KMP-013', 'Keaktifan Jamaah'],
            [$kat4->id, 'KMP-014', 'Keaktifan Pramuka'],
            [$kat4->id, 'KMP-015', 'Penilaian Kerja Khusus'],
            [$kat4->id, 'KMP-016', 'Kegiatan Lainnya'],
        ];
        foreach ($kompetensiData as [$katId, $kode, $nama]) {
            $kompetensi = Kompetensi::create([
                'kode_kompetensi' => $kode,
                'kategori_kinerja_id' => $katId,
                'kompetensi' => $nama,
            ]);

            $kompetensi->kategoriKinerja()->syncWithoutDetaching([$katId]);
        }

        // ── Performance Rating ─────────────────────────────
        $ratingData = [
            ['RTG-001', 'A (Sangat Baik)',   'Nilai 90 ke atas'],
            ['RTG-002', 'B (Baik)',          'Nilai 80 - 89'],
            ['RTG-003', 'C (Cukup)',         'Nilai 70 - 79'],
            ['RTG-004', 'D (Kurang)',        'Nilai 60 - 69'],
            ['RTG-005', 'E (Sangat Kurang)', 'Nilai di bawah 60'],
        ];
        foreach ($ratingData as [$kode, $rating, $ket]) {
            PerformanceRating::create(['kode_rating' => $kode, 'rating' => $rating, 'keterangan' => $ket]);
        }

        // ── Pangkalan Job ──────────────────────────────────
        $pangkalanData = [
            ['PNG-001', 'MA AL-HUDA AL-ILAHIYAH',     "FATHUL MU'IN, S.Pd.",         null],
            ['PNG-002', 'MTs AL-HUDA AL-ILAHIYAH',    'Drs. ANAS',                   null],
            ['PNG-003', 'MI AL-HUDA AL-ILAHIYAH',     'SUJITHO, S.Pd.SD',            null],
            ['PNG-004', 'RA AL-HUDA AL-ILAHIYAH',     'BANDIAH, S.Pd.',              null],
            ['PNG-005', 'PAUD DASARI BUDI',            'SITI JUARIAH, S.Pd.I',       null],
            ['PNG-006', 'MDTA AL-HUDA AL-ILAHIYAH',   'NUR MAKMUROH',               null],
            ['PNG-007', 'PENGURUS PONPES PA',          'MISRUN, S.Ag.',              null],
            ['PNG-008', 'PENGURUS PONPES PI',          'NINA MARLINA, S.Pd.',        null],
            ['PNG-009', 'KOPERASI PONTREN',            'NINA MARLINA, S.Pd.',        null],
            ['PNG-010', 'KEMASJIDAN',                  'Drs. ANAS',                  null],
            ['PNG-011', 'DEPOT AIR, TAMAN, LOGISTIK',  'RAHMAT BUDI PERMANA, S.Pd.', null],
            ['PNG-012', 'PERPUSTAKAAN',                null,                         null],
            ['PNG-013', 'PEMBINA PRAMUKA',             "FATHUL MU'IN, S.Pd.",        null],
        ];
        $pangkalan = [];
        foreach ($pangkalanData as [$kode, $nama, $pimpinan, $ket]) {
            $pangkalan[$kode] = Pangkalan::create([
                'kode_pangkalan' => $kode,
                'nama_pangkalan' => $nama,
                'pimpinan_pos' => $pimpinan,
                'keterangan' => $ket,
            ]);
        }

        // Sample Kepala Pimpinan Pos (UAC)
        User::updateOrCreate(
            ['username' => 'kepala_png001'],
            [
                'name' => 'Kepala Pos MA',
                'email' => 'kepala.pos@penilaian.com',
                'password' => Hash::make('kepala123'),
                'role' => 'user',
                'pangkalan_id' => $pangkalan['PNG-001']->id,
                'is_kepala' => true,
            ]
        );

        // ── Karyawan (28 orang dari blanko) ───────────────
        $karyawanData = [
            // [no_urut, nama, tugas_khusus, kode_pangkalan]
            [1,  "AMIRUL MU'MININ",              'Koperasi',                'PNG-009'],
            [2,  'ANDISKA ARIA WIJAYA',           'TU MI, Kemasjidan',       'PNG-003'],
            [3,  'APRIANTI',                      'TU MI, MDTA',             'PNG-003'],
            [4,  'ARABIAH',                       'Koperasi',                'PNG-009'],
            [5,  'DARMA LUTFIA',                  'TU RA, MDTA',             'PNG-004'],
            [6,  'DAVID GUSTIA PUTRA',            'Depot, Taman',            'PNG-011'],
            [7,  'DIANA LESTARI',                 'MDTA',                    'PNG-006'],
            [8,  'ERIN AZKA FUAD SAPUTRA',        'TU MI',                   'PNG-003'],
            [9,  'FIDIANA HADIATUL HIKMAH',       'Koperasi',                'PNG-009'],
            [10, 'HAPPY FARIDAH',                 'TU RA',                   'PNG-004'],
            [11, 'HARI UTAMI',                    'Pustaka',                 'PNG-012'],
            [12, 'INTAN MAULIDDIAH',              'BEND. MTs',               'PNG-002'],
            [13, 'KHIKMATUL MARIA',               'TU PAUD, MDTA',           'PNG-005'],
            [14, 'LAILI BINTI HABIBAH',           'Pustaka',                 'PNG-012'],
            [15, 'LAILIN NASOIHAH',               'Bend. MI',                'PNG-003'],
            [16, 'LILIK ALISTIN',                 'Koperasi',                'PNG-009'],
            [17, 'LILY HERAWATI',                 'PAUD',                    'PNG-005'],
            [18, 'M. FITROH AHSANI',              'Depot, Taman',            'PNG-011'],
            [19, 'M. MUSTAQIM MAHMUDIN',          'Depot, Taman',            'PNG-011'],
            [20, 'MOH. AKMAL SUKMA WARDANI',      'TU MA, Kemasjidan',       'PNG-001'],
            [21, 'MUHAMAD IBNU ATHO\'ILAH',       'TU MA',                   'PNG-001'],
            [22, 'NOFI ALFIANI',                  'TU MA',                   'PNG-001'],
            [23, 'NUR LINDA',                     'Pustaka',                 'PNG-012'],
            [24, 'NURIL HUDA FERDIANSYAH',        'Koperasi',                'PNG-009'],
            [25, 'PUTRI AGUSTINA',                'PAUD',                    'PNG-005'],
            [26, 'RIFKA RIFIA FITRIANI',          'TU MTs',                  'PNG-002'],
            [27, 'SITI MUNIROTUS SHOLIHAH',       'Koperasi',                'PNG-009'],
            [28, 'ULVA INAYATUL IFTAKHIYAH',      'TU MA',                   'PNG-001'],
        ];

        foreach ($karyawanData as [$no, $nama, $tugas, $kodePangkalan]) {
            Karyawan::create([
                'kode_karyawan' => 'KRY-'.str_pad($no, 4, '0', STR_PAD_LEFT),
                'nama_karyawan' => $nama,
                'is_active' => true,
                'alamat' => null,
                'tugas_khusus' => $tugas,
                'tahun_penilaian_id' => $tahun->id,
                'pangkalan_id' => $pangkalan[$kodePangkalan]->id,
                'user_id' => null,
            ]);
        }
    }
}

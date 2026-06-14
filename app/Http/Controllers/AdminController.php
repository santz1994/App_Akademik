<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\KategoriKinerja;
use App\Models\Kompetensi;
use App\Models\Pangkalan;
use App\Models\PenilaianLock;
use App\Models\PenilaianUnlockRequest;
use App\Models\TahunPenilaian;
use App\Models\Transaksi;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tahunAktifModel = TahunPenilaian::where('is_active', true)->first();
        $tahunAktifId = $tahunAktifModel?->id;

        $totalKaryawan = Karyawan::bukanKepala()->count();
        $totalKaryawanAktif = Karyawan::bukanKepala()->where('is_active', true)->count();
        $totalKaryawanNonaktif = max($totalKaryawan - $totalKaryawanAktif, 0);

        $sudahDinilaiAktifTahun = $tahunAktifId
            ? Transaksi::where('tahun_penilaian_id', $tahunAktifId)
                ->whereHas('karyawan', fn ($q) => $q->bukanKepala()->where('is_active', true))
                ->distinct('karyawan_id')
                ->count('karyawan_id')
            : 0;

        $progresDinilaiAktifTahun = $totalKaryawanAktif > 0
            ? round(($sudahDinilaiAktifTahun / $totalKaryawanAktif) * 100)
            : 0;

        $rataNilaiAktifTahun = $tahunAktifId
            ? Transaksi::where('tahun_penilaian_id', $tahunAktifId)
                ->whereHas('karyawan', fn ($q) => $q->bukanKepala())
                ->avg('nilai')
            : null;

        $topPangkalan = Pangkalan::withCount([
            'karyawan' => fn ($q) => $q->bukanKepala(),
            'karyawan as karyawan_aktif_count' => fn ($q) => $q->bukanKepala()->where('is_active', true),
        ])
            ->orderByDesc('karyawan_count')
            ->orderBy('nama_pangkalan')
            ->take(6)
            ->get();

        $usersByRole = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $pendingUnlockCount = PenilaianUnlockRequest::where('status', 'pending')->count();

        // Build list of karyawan with incomplete scores for the active tahun
        $karyawanIncompleteList = collect();
        if ($tahunAktifId) {
            $karyawanAktif = Karyawan::with([
                'pangkalans.kategoriKinerja.kompetensi',
                'transaksi' => fn ($q) => $q->where('tahun_penilaian_id', $tahunAktifId),
            ])
                ->bukanKepala()
                ->where('is_active', true)
                ->where('tahun_penilaian_id', $tahunAktifId)
                ->get();

            foreach ($karyawanAktif as $k) {
                $totalKomp = 0;
                $terisi = 0;
                foreach ($k->pangkalans as $pk) {
                    $pkKatIds = $pk->kategoriKinerja->pluck('id')->toArray();
                    $pkKats = KategoriKinerja::with('kompetensi')
                        ->whereIn('id', $pkKatIds)
                        ->get();
                    // Per-kategori sum (shared kompetensi counted per kategori)
                    $totalKomp += $pkKats->sum(fn ($kat) => $kat->kompetensi->count());
                    // Enrichment-aware: check kompetensi_id only (not kategori_kinerja_id)
                    foreach ($pkKats as $kat) {
                        foreach ($kat->kompetensi as $komp) {
                            if ($k->transaksi->contains(fn ($t) => $t->nilai !== null
                                && (int) ($t->pangkalan_id ?? 0) === (int) $pk->id
                                && (int) $t->kompetensi_id === (int) $komp->id
                            )) {
                                $terisi++;
                            }
                        }
                    }
                }
                if ($totalKomp > 0 && $terisi < $totalKomp) {
                    $karyawanIncompleteList->push([
                        'karyawan' => $k,
                        'terisi' => $terisi,
                        'total' => $totalKomp,
                        'persen' => round(($terisi / $totalKomp) * 100),
                    ]);
                }
            }
        }

        $stats = [
            'total_users' => User::count(),
            'total_karyawan' => $totalKaryawan,
            'total_karyawan_aktif' => $totalKaryawanAktif,
            'total_karyawan_nonaktif' => $totalKaryawanNonaktif,
            'total_pangkalan' => Pangkalan::count(),
            'tahun_aktif' => $tahunAktifModel?->periode_penilaian ?? '-',
            'total_kategori' => KategoriKinerja::count(),
            'total_kompetensi' => Kompetensi::count(),
            'total_penilaian' => Transaksi::whereHas('karyawan', fn ($q) => $q->bukanKepala())->count(),
            'sudah_dinilai' => Transaksi::whereHas('karyawan', fn ($q) => $q->bukanKepala())->distinct('karyawan_id')->count('karyawan_id'),
            'sudah_dinilai_tahun_aktif' => $sudahDinilaiAktifTahun,
            'progres_dinilai_tahun_aktif' => $progresDinilaiAktifTahun,
            'belum_lengkap_count' => $karyawanIncompleteList->count(),
            'belum_lengkap_list' => $karyawanIncompleteList,
            'rata_nilai_tahun_aktif' => $rataNilaiAktifTahun !== null ? round((float) $rataNilaiAktifTahun, 2) : null,
            'total_penilaian_tahun_aktif' => $tahunAktifId
                ? Transaksi::where('tahun_penilaian_id', $tahunAktifId)
                    ->whereHas('karyawan', fn ($q) => $q->bukanKepala())
                    ->count()
                : 0,
            'pending_unlock' => PenilaianUnlockRequest::where('status', 'pending')->count(),
            'locked_tahun_aktif' => $tahunAktifId
                ? PenilaianLock::where('tahun_penilaian_id', $tahunAktifId)->where('is_locked', true)->count()
                : 0,
            'total_admin' => (int) ($usersByRole['admin'] ?? 0),
            'total_user_biasa' => (int) ($usersByRole['user'] ?? 0),
            'total_user_kepala' => User::where('is_kepala', true)->count(),
            'top_pangkalan' => $topPangkalan,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

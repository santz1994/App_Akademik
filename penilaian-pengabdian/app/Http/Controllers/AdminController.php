<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\TahunPenilaian;
use App\Models\KategoriKinerja;
use App\Models\Kompetensi;
use App\Models\PerformanceRating;
use App\Models\Pangkalan;
use App\Models\PenilaianLock;
use App\Models\PenilaianUnlockRequest;
use App\Models\Transaksi;

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
                ->whereHas('karyawan', fn($q) => $q->bukanKepala())
                ->distinct('karyawan_id')
                ->count('karyawan_id')
            : 0;

        $progresDinilaiAktifTahun = $totalKaryawan > 0
            ? round(($sudahDinilaiAktifTahun / $totalKaryawan) * 100)
            : 0;

        $rataNilaiAktifTahun = $tahunAktifId
            ? Transaksi::where('tahun_penilaian_id', $tahunAktifId)
                ->whereHas('karyawan', fn($q) => $q->bukanKepala())
                ->avg('nilai')
            : null;

        $topPangkalan = Pangkalan::withCount([
            'karyawan' => fn($q) => $q->bukanKepala(),
            'karyawan as karyawan_aktif_count' => fn($q) => $q->bukanKepala()->where('is_active', true),
            ])
            ->orderByDesc('karyawan_count')
            ->orderBy('nama_pangkalan')
            ->take(6)
            ->get();

        $usersByRole = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        $stats = [
            'total_users'      => User::count(),
            'total_karyawan'   => $totalKaryawan,
            'total_karyawan_aktif' => $totalKaryawanAktif,
            'total_karyawan_nonaktif' => $totalKaryawanNonaktif,
            'total_pangkalan'  => Pangkalan::count(),
            'tahun_aktif'      => $tahunAktifModel?->periode_penilaian ?? '-',
            'total_kategori'   => KategoriKinerja::count(),
            'total_kompetensi' => Kompetensi::count(),
            'total_penilaian'  => Transaksi::whereHas('karyawan', fn($q) => $q->bukanKepala())->count(),
            'sudah_dinilai'    => Transaksi::whereHas('karyawan', fn($q) => $q->bukanKepala())->distinct('karyawan_id')->count('karyawan_id'),
            'sudah_dinilai_tahun_aktif' => $sudahDinilaiAktifTahun,
            'progres_dinilai_tahun_aktif' => $progresDinilaiAktifTahun,
            'rata_nilai_tahun_aktif' => $rataNilaiAktifTahun !== null ? round((float) $rataNilaiAktifTahun, 2) : null,
            'total_penilaian_tahun_aktif' => $tahunAktifId
                ? Transaksi::where('tahun_penilaian_id', $tahunAktifId)
                    ->whereHas('karyawan', fn($q) => $q->bukanKepala())
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


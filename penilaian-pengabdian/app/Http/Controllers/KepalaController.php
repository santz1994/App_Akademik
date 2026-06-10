<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\TahunPenilaian;
use App\Models\Transaksi;
use App\Models\Pangkalan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KepalaController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $pangkalanIds = $user->getAllPangkalanIds();
        $pangkalanList = Pangkalan::whereIn('id', $pangkalanIds)->where('is_active', true)->orderBy('nama_pangkalan')->get();

        // Pangkalan yang dipilih (default: semua)
        $selectedPangkalanId = $request->input('pangkalan_id');
        $filterPangkalanIds = $selectedPangkalanId
            ? [(int) $selectedPangkalanId]
            : $pangkalanList->pluck('id')->map(fn($id) => (int) $id)->toArray();

        $selectedPangkalanName = $selectedPangkalanId
            ? ($pangkalanList->firstWhere('id', (int) $selectedPangkalanId)?->nama_pangkalan ?? '-')
            : $pangkalanList->pluck('nama_pangkalan')->implode(', ');

        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $tahunId = $tahunAktif?->id;

        // Hanya karyawan aktif di pangkalan yang dipilih
        $karyawanQuery = Karyawan::whereHas('pangkalans', fn($q) => $q->whereIn('pangkalan.id', $filterPangkalanIds))
            ->bukanKepala()
            ->where('is_active', true);

        $karyawanIds = $karyawanQuery->pluck('id');

        $totalKaryawan = $karyawanIds->count();
        $totalPenilaian = Transaksi::whereIn('karyawan_id', $karyawanIds)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->count();

        // FIX: Count karyawan where ALL kompetensi per pangkalan are scored
        $sudahDinilai = 0;
        foreach ($karyawanIds as $kId) {
            $kPangkalanIds = DB::table('karyawan_pangkalan')->where('karyawan_id', $kId)->pluck('pangkalan_id')->toArray();
            $allScored = true;
            foreach ($kPangkalanIds as $pkId) {
                $pkKatIds = DB::table('pangkalan_kategori_kinerja')->where('pangkalan_id', $pkId)->pluck('kategori_kinerja_id')->toArray();
                $pkKompCount = DB::table('kategori_kinerja_kompetensi')->whereIn('kategori_kinerja_id', $pkKatIds)->count();
                if ($pkKompCount === 0) continue;
                $pkScoredCount = Transaksi::where('karyawan_id', $kId)
                    ->where('pangkalan_id', $pkId)
                    ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
                    ->whereNotNull('nilai')
                    ->count();
                if ($pkScoredCount < $pkKompCount) {
                    $allScored = false;
                    break;
                }
            }
            if ($allScored && count($kPangkalanIds) > 0) $sudahDinilai++;
        }

        // List karyawan untuk dashboard
        $karyawanList = Karyawan::with(['pangkalans', 'transaksi' => fn($q) => $q->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))])
            ->whereHas('pangkalans', fn($q) => $q->whereIn('pangkalan.id', $filterPangkalanIds))
            ->bukanKepala()
            ->where('is_active', true)
            ->orderBy('nama_karyawan')
            ->get();

        $stats = [
            'pangkalan' => $selectedPangkalanName,
            'tahun_aktif' => $tahunAktif?->periode_penilaian ?? '-',
            'total_karyawan' => $totalKaryawan,
            'total_penilaian' => $totalPenilaian,
            'sudah_dinilai' => $sudahDinilai,
        ];

        return view('kepala.dashboard', compact('stats', 'pangkalanList', 'selectedPangkalanId', 'karyawanList', 'tahunId'));
    }
}

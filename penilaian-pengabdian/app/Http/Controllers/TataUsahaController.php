<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pangkalan;
use App\Models\PenilaianLock;
use App\Models\TahunPenilaian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TataUsahaController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Tata usaha sees karyawan within their pangkalan
        $pangkalanId = $user->pangkalan_id;
        $pangkalanList = $pangkalanId
            ? Pangkalan::whereIn('id', [$pangkalanId])->where('is_active', true)->get()
            : Pangkalan::where('is_active', true)->orderBy('nama_pangkalan')->get();

        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $tahunId = $tahunAktif?->id;

        // If tata usaha has pangkalan, filter by it
        $karyawanQuery = Karyawan::bukanKepala()->where('is_active', true);
        if ($pangkalanId) {
            $karyawanQuery->whereHas('pangkalans', fn ($q) => $q->where('pangkalan.id', $pangkalanId));
        }

        $karyawanIds = $karyawanQuery->pluck('id');
        $totalKaryawan = $karyawanIds->count();

        $sudahDinilai = Transaksi::whereIn('karyawan_id', $karyawanIds)
            ->when($tahunId, fn ($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->distinct('karyawan_id')
            ->count('karyawan_id');

        $lockedCount = PenilaianLock::whereIn('karyawan_id', $karyawanIds)
            ->when($tahunId, fn ($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->where('is_locked', true)
            ->count();

        $stats = [
            'pangkalan' => $pangkalanId
                ? (Pangkalan::find($pangkalanId)?->nama_pangkalan ?? '-')
                : 'Semua Pangkalan',
            'tahun_aktif' => $tahunAktif?->periode_penilaian ?? '-',
            'total_karyawan' => $totalKaryawan,
            'sudah_dinilai' => $sudahDinilai,
            'locked_count' => $lockedCount,
        ];

        return view('tata_usaha.dashboard', compact('stats', 'pangkalanList'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\TahunPenilaian;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class KepalaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $pangkalanId = $user->pangkalan_id;
        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $tahunId = $tahunAktif?->id;

        $karyawanIds = Karyawan::where('pangkalan_id', $pangkalanId)
            ->bukanKepala()
            ->pluck('id');

        $totalKaryawan = $karyawanIds->count();
        $totalPenilaian = Transaksi::whereIn('karyawan_id', $karyawanIds)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->count();

        $sudahDinilai = Transaksi::whereIn('karyawan_id', $karyawanIds)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->distinct('karyawan_id')
            ->count('karyawan_id');

        $stats = [
            'pangkalan' => $user->pangkalan?->nama_pangkalan ?? '-',
            'tahun_aktif' => $tahunAktif?->periode_penilaian ?? '-',
            'total_karyawan' => $totalKaryawan,
            'total_penilaian' => $totalPenilaian,
            'sudah_dinilai' => $sudahDinilai,
        ];

        return view('kepala.dashboard', compact('stats'));
    }
}

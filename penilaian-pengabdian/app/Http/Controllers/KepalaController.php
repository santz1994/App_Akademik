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

        $pangkalanIds = $user->getAllPangkalanIds();
        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $tahunId = $tahunAktif?->id;

        $karyawanIds = Karyawan::whereIn('pangkalan_id', $pangkalanIds)
            ->bukanKepala()
            ->pluck('id');

        $pangkalanNames = \App\Models\Pangkalan::whereIn('id', $pangkalanIds)->pluck('nama_pangkalan')->implode(', ');

        $totalKaryawan = $karyawanIds->count();
        $totalPenilaian = Transaksi::whereIn('karyawan_id', $karyawanIds)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->count();

        $sudahDinilai = Transaksi::whereIn('karyawan_id', $karyawanIds)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->distinct('karyawan_id')
            ->count('karyawan_id');

        $stats = [
            'pangkalan' => $pangkalanNames ?: '-',
            'tahun_aktif' => $tahunAktif?->periode_penilaian ?? '-',
            'total_karyawan' => $totalKaryawan,
            'total_penilaian' => $totalPenilaian,
            'sudah_dinilai' => $sudahDinilai,
        ];

        return view('kepala.dashboard', compact('stats'));
    }
}

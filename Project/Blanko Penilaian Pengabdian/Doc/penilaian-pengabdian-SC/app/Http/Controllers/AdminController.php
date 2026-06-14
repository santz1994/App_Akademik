<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\TahunPenilaian;
use App\Models\KategoriKinerja;
use App\Models\Kompetensi;
use App\Models\PerformanceRating;
use App\Models\Pangkalan;
use App\Models\Transaksi;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_karyawan'   => Karyawan::count(),
            'total_pangkalan'  => Pangkalan::count(),
            'tahun_aktif'      => TahunPenilaian::where('is_active', true)->value('periode_penilaian') ?? '-',
            'total_kategori'   => KategoriKinerja::count(),
            'total_kompetensi' => Kompetensi::count(),
            'total_penilaian'  => Transaksi::count(),
            'sudah_dinilai'    => Transaksi::distinct('karyawan_id')->count('karyawan_id'),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}


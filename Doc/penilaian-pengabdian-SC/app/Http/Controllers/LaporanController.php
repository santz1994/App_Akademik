<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\TahunPenilaian;
use App\Models\KategoriKinerja;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $tahunList         = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif        = TahunPenilaian::where('is_active', true)->first();
        $selectedTahun     = $request->input('tahun_penilaian_id', $tahunAktif?->id);
        $selectedTahunData = $selectedTahun ? TahunPenilaian::find($selectedTahun) : $tahunAktif;

        $kategoriList = KategoriKinerja::with('kompetensi')->orderBy('kode_kategori')->get();

        $karyawanList = Karyawan::with([
            'pangkalan',
            'transaksi' => fn($q) => $q
                ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
                ->with('kompetensi.kategoriKinerja'),
        ])
        ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
        ->orderBy('nama_karyawan')
        ->get();

        $totalKompetensi = $kategoriList->sum(fn($k) => $k->kompetensi->count());
        $totalTransaksi  = Transaksi::when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))->count();

        return view('admin.laporan.index', compact(
            'tahunList', 'selectedTahun', 'selectedTahunData',
            'karyawanList', 'kategoriList', 'totalKompetensi', 'totalTransaksi'
        ));
    }
}

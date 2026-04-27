<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Karyawan;
use App\Models\Kompetensi;
use App\Models\KategoriKinerja;
use App\Models\TahunPenilaian;
use App\Models\PerformanceRating;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $tahunList     = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif    = TahunPenilaian::where('is_active', true)->first();
        $selectedTahun = $request->input('tahun_penilaian_id', $tahunAktif?->id);
        $totalKompetensi = Kompetensi::count();

        $karyawanList = Karyawan::with([
            'pangkalan',
            'transaksi' => fn($q) => $q->when($selectedTahun, fn($q) =>
                $q->where('tahun_penilaian_id', $selectedTahun))->with('kompetensi.kategoriKinerja'),
        ])
        ->when($selectedTahun, fn($q) => $q->where('tahun_penilaian_id', $selectedTahun))
        ->orderBy('nama_karyawan')
        ->paginate(20)
        ->withQueryString();

        return view('admin.transaksi.index', compact(
            'karyawanList', 'tahunList', 'selectedTahun', 'totalKompetensi'
        ));
    }

    public function create(Request $request)
    {
        $karyawanList  = Karyawan::with('pangkalan')->orderBy('nama_karyawan')->get();
        $tahunList     = TahunPenilaian::orderByDesc('periode_penilaian')->get();
        $tahunAktif    = TahunPenilaian::where('is_active', true)->first();
        $kategoriList  = KategoriKinerja::with(['kompetensi' => fn($q) => $q->orderBy('kode_kompetensi')])
                            ->orderBy('kode_kategori')->get();

        $selectedKaryawan = null;
        $selectedTahun    = null;
        $existingNilai    = [];

        if ($request->filled('karyawan_id') && $request->filled('tahun_penilaian_id')) {
            $selectedKaryawan = Karyawan::with('pangkalan')->find($request->karyawan_id);
            $selectedTahun    = TahunPenilaian::find($request->tahun_penilaian_id);
            $existingNilai    = Transaksi::where('karyawan_id', $request->karyawan_id)
                ->where('tahun_penilaian_id', $request->tahun_penilaian_id)
                ->pluck('nilai', 'kompetensi_id')
                ->toArray();
        }

        return view('admin.transaksi.create', compact(
            'karyawanList', 'tahunList', 'tahunAktif',
            'kategoriList', 'selectedKaryawan', 'selectedTahun', 'existingNilai'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'        => 'required|exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
            'nilai'              => 'required|array',
            'nilai.*'            => 'nullable|numeric|min:0|max:100',
        ]);

        $karyawanId = $request->karyawan_id;
        $tahunId    = $request->tahun_penilaian_id;
        $counter    = Transaksi::max('id') ?? 0;

        foreach ($request->nilai as $kompetensiId => $nilai) {
            if ($nilai === null || $nilai === '') continue;
            $counter++;
            Transaksi::updateOrCreate(
                [
                    'karyawan_id'        => $karyawanId,
                    'tahun_penilaian_id' => $tahunId,
                    'kompetensi_id'      => $kompetensiId,
                ],
                [
                    'kode_transaksi' => 'TRX-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
                    'nilai'          => $nilai,
                ]
            );
        }

        return redirect()->route('admin.transaksi.index', ['tahun_penilaian_id' => $tahunId])
            ->with('success', 'Nilai penilaian berhasil disimpan.');
    }

    public function edit(Transaksi $transaksi)
    {
        // Redirect to scoresheet for that karyawan
        return redirect()->route('admin.transaksi.create', [
            'karyawan_id'        => $transaksi->karyawan_id,
            'tahun_penilaian_id' => $transaksi->tahun_penilaian_id,
        ]);
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        return redirect()->route('admin.transaksi.index');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return back()->with('success', 'Data penilaian dihapus.');
    }

    /** Delete all penilaian for one karyawan in one tahun */
    public function destroyByKaryawan(Request $request, Karyawan $karyawan)
    {
        $tahunId = $request->input('tahun_penilaian_id');
        Transaksi::where('karyawan_id', $karyawan->id)
            ->when($tahunId, fn($q) => $q->where('tahun_penilaian_id', $tahunId))
            ->delete();

        return redirect()->route('admin.transaksi.index', ['tahun_penilaian_id' => $tahunId])
            ->with('success', 'Semua penilaian karyawan berhasil dihapus.');
    }
}

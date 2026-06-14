<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pangkalan;
use App\Models\TahunPenilaian;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    /**
     * Mutasi Tahun Ajaran - pindahkan karyawan ke tahun ajaran aktif
     */
    public function index(Request $request)
    {
        $tahunList = TahunPenilaian::orderByDesc('periode_penilaian')->get();

        $karyawan = Karyawan::with(['tahunPenilaian', 'user'])
            ->orderBy('nama_karyawan');

        $karyawan = $this->paginateWithPerPage($karyawan, $request, 10);

        return view('admin.mutasi.index', compact('karyawan', 'tahunList'));
    }

    public function assign(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
        ]);

        $karyawan->update(['tahun_penilaian_id' => $request->tahun_penilaian_id]);

        return back()->with('success', 'Tahun ajaran karyawan <strong>'.$karyawan->nama_karyawan.'</strong> berhasil diperbarui.');
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'karyawan_ids' => 'required|array|min:1',
            'karyawan_ids.*' => 'exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
        ]);

        $tahun = TahunPenilaian::findOrFail($request->tahun_penilaian_id);
        $count = Karyawan::whereIn('id', $request->karyawan_ids)
            ->update(['tahun_penilaian_id' => $tahun->id]);

        return back()->with('success', '<strong>'.$count.' karyawan</strong> berhasil dipindahkan ke tahun <strong>'.$tahun->periode_penilaian.'</strong>.');
    }

    /**
     * Mutasi Antar Pangkalan Job - pindahkan karyawan ke pangkalan lain
     */
    public function pangkalanIndex(Request $request)
    {
        $pangkalanList = Pangkalan::orderBy('nama_pangkalan')->get();
        $filterPangkalan = $request->input('pangkalan_id');
        $search = trim((string) $request->input('q'));

        $karyawan = Karyawan::with(['pangkalan', 'user'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_karyawan', 'like', "%{$search}%")
                        ->orWhere('kode_karyawan', 'like', "%{$search}%");
                });
            })
            ->when($filterPangkalan, fn ($q) => $q->where('pangkalan_id', $filterPangkalan))
            ->orderBy('nama_karyawan');

        $karyawan = $this->paginateWithPerPage($karyawan, $request, 10);

        return view('admin.mutasi.pangkalan', compact('karyawan', 'pangkalanList', 'filterPangkalan', 'search'));
    }

    public function assignPangkalan(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'pangkalan_id' => 'required|exists:pangkalan,id',
        ]);

        $karyawan->update(['pangkalan_id' => $request->pangkalan_id]);

        $pangkalan = Pangkalan::find($request->pangkalan_id);

        return back()->with('success', 'Pangkalan karyawan <strong>'.$karyawan->nama_karyawan.'</strong> berhasil dipindahkan ke <strong>'.$pangkalan->nama_pangkalan.'</strong>.');
    }

    public function bulkAssignPangkalan(Request $request)
    {
        $request->validate([
            'karyawan_ids' => 'required|array|min:1',
            'karyawan_ids.*' => 'exists:karyawan,id',
            'pangkalan_id' => 'required|exists:pangkalan,id',
        ]);

        $pangkalan = Pangkalan::findOrFail($request->pangkalan_id);
        $count = Karyawan::whereIn('id', $request->karyawan_ids)
            ->update(['pangkalan_id' => $pangkalan->id]);

        return back()->with('success', '<strong>'.$count.' karyawan</strong> berhasil dipindahkan ke pangkalan <strong>'.$pangkalan->nama_pangkalan.'</strong>.');
    }
}

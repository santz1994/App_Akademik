<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\TahunPenilaian;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = TahunPenilaian::orderByDesc('periode_penilaian')->get();

        $karyawan = Karyawan::with(['tahunPenilaian', 'user'])
            ->orderBy('nama_karyawan')
            ->paginate(15)
            ->withQueryString();

        return view('admin.mutasi.index', compact('karyawan', 'tahunList'));
    }

    public function assign(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
        ]);

        $karyawan->update(['tahun_penilaian_id' => $request->tahun_penilaian_id]);

        return back()->with('success', 'Tahun ajaran karyawan <strong>' . $karyawan->nama_karyawan . '</strong> berhasil diperbarui.');
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'karyawan_ids'       => 'required|array|min:1',
            'karyawan_ids.*'     => 'exists:karyawan,id',
            'tahun_penilaian_id' => 'required|exists:tahun_penilaian,id',
        ]);

        $tahun  = TahunPenilaian::findOrFail($request->tahun_penilaian_id);
        $count  = Karyawan::whereIn('id', $request->karyawan_ids)
                    ->update(['tahun_penilaian_id' => $tahun->id]);

        return back()->with('success', '<strong>' . $count . ' karyawan</strong> berhasil dipindahkan ke tahun <strong>' . $tahun->periode_penilaian . '</strong>.');
    }
}


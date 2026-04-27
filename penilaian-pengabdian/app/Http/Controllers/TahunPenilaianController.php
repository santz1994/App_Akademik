<?php

namespace App\Http\Controllers;

use App\Models\TahunPenilaian;
use Illuminate\Http\Request;

class TahunPenilaianController extends Controller
{
    public function index(Request $request)
    {
        $data = TahunPenilaian::latest();
        $data = $this->paginateWithPerPage($data, $request, 10);
        return view('admin.tahun_penilaian.index', compact('data'));
    }

    public function create()
    {
        return view('admin.tahun_penilaian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_penilaian' => 'required|string|max:100',
            'keterangan'        => 'nullable|string',
            'is_active'         => 'nullable|boolean',
        ]);

        if ($request->boolean('is_active')) {
            TahunPenilaian::where('is_active', true)->update(['is_active' => false]);
        }

        TahunPenilaian::create([
            'periode_penilaian' => $request->periode_penilaian,
            'keterangan'        => $request->keterangan,
            'is_active'         => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.tahun-penilaian.index')
            ->with('success', 'Tahun penilaian berhasil ditambahkan.');
    }

    public function edit(TahunPenilaian $tahunPenilaian)
    {
        return view('admin.tahun_penilaian.edit', compact('tahunPenilaian'));
    }

    public function update(Request $request, TahunPenilaian $tahunPenilaian)
    {
        $request->validate([
            'periode_penilaian' => 'required|string|max:100',
            'keterangan'        => 'nullable|string',
            'is_active'         => 'nullable|boolean',
        ]);

        if ($request->boolean('is_active')) {
            TahunPenilaian::where('is_active', true)
                ->where('id', '!=', $tahunPenilaian->id)
                ->update(['is_active' => false]);
        }

        $tahunPenilaian->update([
            'periode_penilaian' => $request->periode_penilaian,
            'keterangan'        => $request->keterangan,
            'is_active'         => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.tahun-penilaian.index')
            ->with('success', 'Tahun penilaian berhasil diperbarui.');
    }

    public function destroy(TahunPenilaian $tahunPenilaian)
    {
        $tahunPenilaian->delete();
        return redirect()->route('admin.tahun-penilaian.index')
            ->with('success', 'Tahun penilaian berhasil dihapus.');
    }
}

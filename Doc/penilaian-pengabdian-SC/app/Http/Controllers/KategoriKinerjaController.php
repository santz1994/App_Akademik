<?php

namespace App\Http\Controllers;

use App\Models\KategoriKinerja;
use Illuminate\Http\Request;

class KategoriKinerjaController extends Controller
{
    public function index()
    {
        $data = KategoriKinerja::withCount('kompetensi')->latest()->paginate(10);
        return view('admin.kategori_kinerja.index', compact('data'));
    }

    public function create()
    {
        $kode = 'KTG-' . str_pad(KategoriKinerja::count() + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.kategori_kinerja.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:150',
            'bobot'    => 'required|numeric|min:0|max:100',
        ]);

        $kode = 'KTG-' . str_pad(KategoriKinerja::count() + 1, 3, '0', STR_PAD_LEFT);

        KategoriKinerja::create([
            'kode_kategori' => $kode,
            'kategori'      => $request->kategori,
            'bobot'         => $request->bobot,
        ]);

        return redirect()->route('admin.kategori-kinerja.index')
            ->with('success', 'Kategori kinerja berhasil ditambahkan.');
    }

    public function edit(KategoriKinerja $kategoriKinerja)
    {
        return view('admin.kategori_kinerja.edit', compact('kategoriKinerja'));
    }

    public function update(Request $request, KategoriKinerja $kategoriKinerja)
    {
        $request->validate([
            'kategori' => 'required|string|max:150',
            'bobot'    => 'required|numeric|min:0|max:100',
        ]);

        $kategoriKinerja->update($request->only('kategori', 'bobot'));

        return redirect()->route('admin.kategori-kinerja.index')
            ->with('success', 'Kategori kinerja berhasil diperbarui.');
    }

    public function destroy(KategoriKinerja $kategoriKinerja)
    {
        $kategoriKinerja->delete();
        return redirect()->route('admin.kategori-kinerja.index')
            ->with('success', 'Kategori kinerja berhasil dihapus.');
    }
}

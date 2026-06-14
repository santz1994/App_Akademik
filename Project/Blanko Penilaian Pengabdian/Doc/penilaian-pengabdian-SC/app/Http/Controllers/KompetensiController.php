<?php

namespace App\Http\Controllers;

use App\Models\Kompetensi;
use App\Models\KategoriKinerja;
use Illuminate\Http\Request;

class KompetensiController extends Controller
{
    public function index()
    {
        $data = Kompetensi::with('kategoriKinerja')->latest()->paginate(10);
        return view('admin.kompetensi.index', compact('data'));
    }

    public function create()
    {
        $kategori = KategoriKinerja::all();
        $kode     = 'KMP-' . str_pad(Kompetensi::count() + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.kompetensi.create', compact('kategori', 'kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_kinerja_id' => 'required|exists:kategori_kinerja,id',
            'kompetensi'          => 'required|string|max:255',
        ]);

        $kode = 'KMP-' . str_pad(Kompetensi::count() + 1, 3, '0', STR_PAD_LEFT);

        Kompetensi::create([
            'kode_kompetensi'    => $kode,
            'kategori_kinerja_id'=> $request->kategori_kinerja_id,
            'kompetensi'         => $request->kompetensi,
        ]);

        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Kompetensi berhasil ditambahkan.');
    }

    public function edit(Kompetensi $kompetensi)
    {
        $kategori = KategoriKinerja::all();
        return view('admin.kompetensi.edit', compact('kompetensi', 'kategori'));
    }

    public function update(Request $request, Kompetensi $kompetensi)
    {
        $request->validate([
            'kategori_kinerja_id' => 'required|exists:kategori_kinerja,id',
            'kompetensi'          => 'required|string|max:255',
        ]);

        $kompetensi->update($request->only('kategori_kinerja_id', 'kompetensi'));

        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    public function destroy(Kompetensi $kompetensi)
    {
        $kompetensi->delete();
        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Kompetensi berhasil dihapus.');
    }
}

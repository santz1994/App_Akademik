<?php

namespace App\Http\Controllers;

use App\Models\Pangkalan;
use Illuminate\Http\Request;

class PangkalanController extends Controller
{
    public function index()
    {
        $data = Pangkalan::withCount('karyawan')->latest()->paginate(15);
        return view('admin.pangkalan.index', compact('data'));
    }

    public function create()
    {
        $kode = 'PNG-' . str_pad(Pangkalan::count() + 1, 3, '0', STR_PAD_LEFT);
        return view('admin.pangkalan.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pangkalan' => 'required|string|max:200',
            'pimpinan_pos'   => 'nullable|string|max:150',
            'keterangan'     => 'nullable|string',
        ]);

        $kode = 'PNG-' . str_pad(Pangkalan::count() + 1, 3, '0', STR_PAD_LEFT);

        Pangkalan::create([
            'kode_pangkalan' => $kode,
            'nama_pangkalan' => $request->nama_pangkalan,
            'pimpinan_pos'   => $request->pimpinan_pos,
            'keterangan'     => $request->keterangan,
        ]);

        return redirect()->route('admin.pangkalan.index')
            ->with('success', 'Data pangkalan berhasil ditambahkan.');
    }

    public function edit(Pangkalan $pangkalan)
    {
        return view('admin.pangkalan.edit', compact('pangkalan'));
    }

    public function update(Request $request, Pangkalan $pangkalan)
    {
        $request->validate([
            'nama_pangkalan' => 'required|string|max:200',
            'pimpinan_pos'   => 'nullable|string|max:150',
            'keterangan'     => 'nullable|string',
        ]);

        $pangkalan->update($request->only('nama_pangkalan', 'pimpinan_pos', 'keterangan'));

        return redirect()->route('admin.pangkalan.index')
            ->with('success', 'Data pangkalan berhasil diperbarui.');
    }

    public function destroy(Pangkalan $pangkalan)
    {
        $pangkalan->delete();
        return redirect()->route('admin.pangkalan.index')
            ->with('success', 'Data pangkalan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\KategoriKinerja;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KategoriKinerjaController extends Controller
{
    public function index(Request $request)
    {
        $data = KategoriKinerja::withCount('kompetensi')
            ->orderBy('jenis')
            ->orderBy('kode_kategori');

        $data = $this->paginateWithPerPage($data, $request, 10);

        return view('admin.kategori_kinerja.index', compact('data'));
    }

    public function create()
    {
        $kode = $this->generateNextKodeKategori();
        return view('admin.kategori_kinerja.create', compact('kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:150',
            'jenis'    => 'required|in:kinerja,kegiatan',
            'bobot'    => 'nullable|numeric|min:0|max:100',
        ]);

        $jenis = (string) $request->input('jenis');

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                KategoriKinerja::create([
                    'kode_kategori' => $this->generateNextKodeKategori(),
                    'kategori'      => $request->kategori,
                    'jenis'         => $jenis,
                    'is_wajib'      => false,
                    'bobot'         => (float) $request->input('bobot', 0),
                ]);

                return redirect()->route('admin.kategori-kinerja.index')
                    ->with('success', 'Kategori berhasil ditambahkan.');
            } catch (QueryException $exception) {
                if (!$this->isDuplicateKodeKategoriException($exception)) {
                    throw $exception;
                }
            }
        }

        return back()
            ->withInput()
            ->withErrors(['kategori' => 'Gagal membuat kode kategori unik. Silakan coba lagi.']);
    }

    public function edit(KategoriKinerja $kategoriKinerja)
    {
        return view('admin.kategori_kinerja.edit', compact('kategoriKinerja'));
    }

    public function update(Request $request, KategoriKinerja $kategoriKinerja)
    {
        $request->validate([
            'kategori' => 'required|string|max:150',
            'jenis'    => 'required|in:kinerja,kegiatan',
            'bobot'    => 'nullable|numeric|min:0|max:100',
        ]);

        $jenis = (string) $request->input('jenis');

        $kategoriKinerja->update([
            'kategori' => $request->input('kategori'),
            'jenis' => $jenis,
            'is_wajib' => false,
            'bobot' => (float) $request->input('bobot', 0),
        ]);

        return redirect()->route('admin.kategori-kinerja.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriKinerja $kategoriKinerja)
    {
        $kategoriKinerja->delete();
        return redirect()->route('admin.kategori-kinerja.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function generateNextKodeKategori(): string
    {
        $nextNumber = ((int) KategoriKinerja::query()
            ->selectRaw("COALESCE(MAX(CAST(SUBSTRING_INDEX(kode_kategori, '-', -1) AS UNSIGNED)), 0) as max_num")
            ->value('max_num')) + 1;

        do {
            $kode = 'KTG-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (KategoriKinerja::where('kode_kategori', $kode)->exists());

        return $kode;
    }

    private function isDuplicateKodeKategoriException(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'duplicate entry')
            && (str_contains($message, 'kode_kategori') || str_contains($message, 'kategori_kinerja_kode_kategori_unique'));
    }
}
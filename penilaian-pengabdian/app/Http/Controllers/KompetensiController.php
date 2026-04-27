<?php

namespace App\Http\Controllers;

use App\Models\Kompetensi;
use App\Models\KategoriKinerja;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KompetensiController extends Controller
{
    public function index(Request $request)
    {
        $data = Kompetensi::with('kategoriKinerja')->latest();
        $data = $this->paginateWithPerPage($data, $request, 10);
        return view('admin.kompetensi.index', compact('data'));
    }

    public function create()
    {
        $kategori = KategoriKinerja::orderBy('jenis')->orderBy('kode_kategori')->get();
        $kode     = $this->generateNextKodeKompetensi();
        return view('admin.kompetensi.create', compact('kategori', 'kode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_kinerja_ids' => 'required|array|min:1',
            'kategori_kinerja_ids.*' => 'required|exists:kategori_kinerja,id',
            'kompetensi' => 'required|string|max:255',
        ]);

        $kategoriIds = collect($request->input('kategori_kinerja_ids', []))->map(fn($id) => (int) $id)->unique()->values();

        $kompetensi = null;
        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                $kompetensi = Kompetensi::create([
                    'kode_kompetensi'    => $this->generateNextKodeKompetensi(),
                    'kategori_kinerja_id'=> $kategoriIds->first(),
                    'kompetensi'         => $request->kompetensi,
                ]);
                break;
            } catch (QueryException $exception) {
                if (!$this->isDuplicateKodeKompetensiException($exception)) {
                    throw $exception;
                }
            }
        }

        if (!$kompetensi) {
            return back()
                ->withInput()
                ->withErrors(['kompetensi' => 'Gagal membuat kode kompetensi unik. Silakan coba lagi.']);
        }

        $kompetensi->kategoriKinerja()->sync($kategoriIds);

        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Kompetensi berhasil ditambahkan.');
    }

    public function edit(Kompetensi $kompetensi)
    {
        $kategori = KategoriKinerja::orderBy('jenis')->orderBy('kode_kategori')->get();
        $kompetensi->load('kategoriKinerja');

        return view('admin.kompetensi.edit', compact('kompetensi', 'kategori'));
    }

    public function update(Request $request, Kompetensi $kompetensi)
    {
        $request->validate([
            'kategori_kinerja_ids' => 'required|array|min:1',
            'kategori_kinerja_ids.*' => 'required|exists:kategori_kinerja,id',
            'kompetensi' => 'required|string|max:255',
        ]);

        $kategoriIds = collect($request->input('kategori_kinerja_ids', []))->map(fn($id) => (int) $id)->unique()->values();

        $kompetensi->update([
            'kompetensi' => $request->kompetensi,
            'kategori_kinerja_id' => $kategoriIds->first(),
        ]);

        $kompetensi->kategoriKinerja()->sync($kategoriIds);

        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    public function destroy(Kompetensi $kompetensi)
    {
        $kompetensi->delete();
        return redirect()->route('admin.kompetensi.index')
            ->with('success', 'Kompetensi berhasil dihapus.');
    }

    private function generateNextKodeKompetensi(): string
    {
        $nextNumber = ((int) Kompetensi::query()
            ->selectRaw("COALESCE(MAX(CAST(SUBSTRING_INDEX(kode_kompetensi, '-', -1) AS UNSIGNED)), 0) as max_num")
            ->value('max_num')) + 1;

        do {
            $kode = 'KMP-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (Kompetensi::where('kode_kompetensi', $kode)->exists());

        return $kode;
    }

    private function isDuplicateKodeKompetensiException(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'duplicate entry')
            && (str_contains($message, 'kode_kompetensi') || str_contains($message, 'kompetensi_kode_kompetensi_unique'));
    }
}

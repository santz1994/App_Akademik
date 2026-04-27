<?php

namespace App\Http\Controllers;

use App\Models\Pangkalan;
use App\Models\KategoriKinerja;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PangkalanController extends Controller
{
    public function index(Request $request)
    {
        $data = Pangkalan::with(['kategoriKinerja:id,kategori'])
            ->withCount(['karyawan', 'kategoriKinerja'])
            ->latest();
        $data = $this->paginateWithPerPage($data, $request, 10);
        return view('admin.pangkalan.index', compact('data'));
    }

    public function create()
    {
        $kode = $this->generateNextKodePangkalan();
        $kategoriKinerjaList = KategoriKinerja::query()
            ->where('jenis', 'kinerja')
            ->orderBy('kode_kategori')
            ->get();

        return view('admin.pangkalan.create', compact('kode', 'kategoriKinerjaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pangkalan' => 'required|string|max:200',
            'pimpinan_pos'   => 'nullable|string|max:150',
            'keterangan'     => 'nullable|string',
            'kategori_kinerja_ids' => 'nullable|array',
            'kategori_kinerja_ids.*' => 'integer|exists:kategori_kinerja,id',
        ]);

        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                $pangkalan = Pangkalan::create([
                    'kode_pangkalan' => $this->generateNextKodePangkalan(),
                    'nama_pangkalan' => $request->nama_pangkalan,
                    'pimpinan_pos'   => $request->pimpinan_pos,
                    'keterangan'     => $request->keterangan,
                ]);

                $pangkalan->kategoriKinerja()->sync(
                    $this->sanitizeKategoriKinerjaIds((array) $request->input('kategori_kinerja_ids', []))
                );

                return redirect()->route('admin.pangkalan.index')
                    ->with('success', 'Data pangkalan berhasil ditambahkan.');
            } catch (QueryException $exception) {
                if (!$this->isDuplicateKodePangkalanException($exception)) {
                    throw $exception;
                }
            }
        }

        return back()
            ->withInput()
            ->withErrors(['nama_pangkalan' => 'Gagal membuat kode pangkalan unik. Silakan coba lagi.']);
    }

    public function edit(Pangkalan $pangkalan)
    {
        $pangkalan->load('kategoriKinerja:id');
        $kategoriKinerjaList = KategoriKinerja::query()
            ->where('jenis', 'kinerja')
            ->orderBy('kode_kategori')
            ->get();

        return view('admin.pangkalan.edit', compact('pangkalan', 'kategoriKinerjaList'));
    }

    public function update(Request $request, Pangkalan $pangkalan)
    {
        $request->validate([
            'nama_pangkalan' => 'required|string|max:200',
            'pimpinan_pos'   => 'nullable|string|max:150',
            'keterangan'     => 'nullable|string',
            'kategori_kinerja_ids' => 'nullable|array',
            'kategori_kinerja_ids.*' => 'integer|exists:kategori_kinerja,id',
        ]);

        $pangkalan->update($request->only('nama_pangkalan', 'pimpinan_pos', 'keterangan'));
        $pangkalan->kategoriKinerja()->sync(
            $this->sanitizeKategoriKinerjaIds((array) $request->input('kategori_kinerja_ids', []))
        );

        return redirect()->route('admin.pangkalan.index')
            ->with('success', 'Data pangkalan berhasil diperbarui.');
    }

    public function destroy(Pangkalan $pangkalan)
    {
        $pangkalan->delete();
        return redirect()->route('admin.pangkalan.index')
            ->with('success', 'Data pangkalan berhasil dihapus.');
    }

    private function generateNextKodePangkalan(): string
    {
        $nextNumber = ((int) Pangkalan::query()
            ->selectRaw("COALESCE(MAX(CAST(SUBSTRING_INDEX(kode_pangkalan, '-', -1) AS UNSIGNED)), 0) as max_num")
            ->value('max_num')) + 1;

        do {
            $kode = 'PNG-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (Pangkalan::where('kode_pangkalan', $kode)->exists());

        return $kode;
    }

    private function isDuplicateKodePangkalanException(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'duplicate entry')
            && (str_contains($message, 'kode_pangkalan') || str_contains($message, 'pangkalan_kode_pangkalan_unique'));
    }

    private function sanitizeKategoriKinerjaIds(array $kategoriIds): array
    {
        if (empty($kategoriIds)) {
            return [];
        }

        return KategoriKinerja::query()
            ->whereIn('id', $kategoriIds)
            ->where('jenis', 'kinerja')
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }
}

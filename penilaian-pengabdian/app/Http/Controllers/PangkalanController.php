<?php

namespace App\Http\Controllers;

use App\Models\Pangkalan;
use App\Models\KategoriKinerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PangkalanController extends Controller
{
    public function index(Request $request)
    {
        $filterStatusAktif = $request->input('status_aktif', 'aktif');

        $data = Pangkalan::with(['kategoriKinerja:id,kategori,jenis,is_wajib', 'kepalaUser:id,name'])
            ->withCount(['kategoriKinerja'])
            ->when($filterStatusAktif === 'aktif', fn($q) => $q->where('pangkalan.is_active', true))
            ->when($filterStatusAktif === 'nonaktif', fn($q) => $q->where('pangkalan.is_active', false))
            ->latest();
        $data = $this->paginateWithPerPage($data, $request, 10);
        return view('admin.pangkalan.index', compact('data', 'filterStatusAktif'));
    }

    public function create()
    {
        $kode = $this->generateNextKodePangkalan();
        $kategoriKinerjaList = KategoriKinerja::query()
            ->orderBy('jenis')
            ->orderBy('kode_kategori')
            ->get();
        $userList = User::where('role', '!=', 'admin')->orderBy('name')->get();

        return view('admin.pangkalan.create', compact('kode', 'kategoriKinerjaList', 'userList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pangkalan' => 'required|string|max:200',
            'pimpinan_pos'   => 'nullable|string|max:150',
            'keterangan'     => 'nullable|string',
            'is_active'      => 'required|boolean',
            'is_wajib'       => 'nullable|boolean',
            'kepala_user_id' => 'nullable|exists:users,id',
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
                    'is_active'      => $request->boolean('is_active', true),
                    'is_wajib'       => $request->boolean('is_wajib', false),
                    'kepala_user_id' => $request->kepala_user_id ?: null,
                ]);

                $pangkalan->kategoriKinerja()->sync(
                    $this->sanitizeKategoriKinerjaIds((array) $request->input('kategori_kinerja_ids', []))
                );

                // Jika wajib, tambahkan ke semua karyawan aktif
                if ($pangkalan->is_wajib) {
                    $this->syncWajibPangkalan($pangkalan);
                }

                // Auto-sync is_kepala on User
                $this->syncKepalaStatus($pangkalan);

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
            ->orderBy('jenis')
            ->orderBy('kode_kategori')
            ->get();
        $userList = User::where('role', '!=', 'admin')->orderBy('name')->get();

        return view('admin.pangkalan.edit', compact('pangkalan', 'kategoriKinerjaList', 'userList'));
    }

    public function update(Request $request, Pangkalan $pangkalan)
    {
        $request->validate([
            'nama_pangkalan' => 'required|string|max:200',
            'pimpinan_pos'   => 'nullable|string|max:150',
            'keterangan'     => 'nullable|string',
            'is_active'      => 'required|boolean',
            'is_wajib'       => 'nullable|boolean',
            'kepala_user_id' => 'nullable|exists:users,id',
            'kategori_kinerja_ids' => 'nullable|array',
            'kategori_kinerja_ids.*' => 'integer|exists:kategori_kinerja,id',
        ]);

        $oldKepalaUserId = $pangkalan->kepala_user_id;
        $wasWajib = (bool) $pangkalan->is_wajib;

        $pangkalan->update([
            'nama_pangkalan' => $request->nama_pangkalan,
            'pimpinan_pos'   => $request->pimpinan_pos,
            'keterangan'     => $request->keterangan,
            'is_active'      => $request->boolean('is_active', true),
            'is_wajib'       => $request->boolean('is_wajib', false),
            'kepala_user_id' => $request->kepala_user_id ?: null,
        ]);

        $pangkalan->kategoriKinerja()->sync(
            $this->sanitizeKategoriKinerjaIds((array) $request->input('kategori_kinerja_ids', []))
        );

        // Handle perubahan is_wajib
        $isNowWajib = (bool) $pangkalan->is_wajib;
        if ($isNowWajib && !$wasWajib) {
            // Baru jadi wajib: tambahkan ke semua karyawan aktif
            $this->syncWajibPangkalan($pangkalan);
        } elseif (!$isNowWajib && $wasWajib) {
            // Berubah dari wajib ke tidak wajib: hapus dari semua karyawan
            $this->removeWajibPangkalan($pangkalan);
        }

        // Auto-sync is_kepala on User (both old and new)
        $this->syncKepalaStatus($pangkalan, $oldKepalaUserId);

        return redirect()->route('admin.pangkalan.index')
            ->with('success', 'Data pangkalan berhasil diperbarui.');
    }

    public function destroy(Pangkalan $pangkalan)
    {
        $pangkalan->delete();
        return redirect()->route('admin.pangkalan.index')
            ->with('success', 'Data pangkalan berhasil dihapus.');
    }

    public function toggleStatus(Pangkalan $pangkalan)
    {
        $pangkalan->update(['is_active' => !$pangkalan->is_active]);

        // Jika wajib dan baru diaktifkan, sync ke semua karyawan
        if ($pangkalan->is_wajib && $pangkalan->is_active) {
            $this->syncWajibPangkalan($pangkalan);
        }

        return back()->with(
            'success',
            'Status pangkalan ' . $pangkalan->nama_pangkalan . ' berhasil diubah menjadi ' . ($pangkalan->is_active ? 'Aktif.' : 'Tidak Aktif.')
        );
    }

    /**
     * Auto-sync is_kepala on User based on pangkalan kepala assignment.
     */
    private function syncKepalaStatus(Pangkalan $pangkalan, ?int $oldKepalaUserId = null): void
    {
        // Set new kepala as is_kepala = true
        if ($pangkalan->kepala_user_id) {
            User::where('id', $pangkalan->kepala_user_id)->update(['is_kepala' => true]);

            // Sync kepala_pangkalan pivot
            $user = User::find($pangkalan->kepala_user_id);
            if ($user) {
                // Add this pangkalan to the user's kepala_pangkalan
                if (!$user->kepalaPangkalan()->where('pangkalan_id', $pangkalan->id)->exists()) {
                    $user->kepalaPangkalan()->attach($pangkalan->id);
                }
                // Update user's pangkalan_id if not set
                if (!$user->pangkalan_id) {
                    $user->update(['pangkalan_id' => $pangkalan->id]);
                }

                // Also add karyawan to this pangkalan's karyawan_pangkalan pivot
                $karyawan = \App\Models\Karyawan::where('user_id', $user->id)->first();
                if ($karyawan) {
                    if (!$karyawan->pangkalans()->where('pangkalan_id', $pangkalan->id)->exists()) {
                        $karyawan->pangkalans()->attach($pangkalan->id);
                    }
                }
            }
        }

        // Check if old kepala still leads other pangkalans
        if ($oldKepalaUserId && $oldKepalaUserId != $pangkalan->kepala_user_id) {
            $stillKepala = Pangkalan::where('kepala_user_id', $oldKepalaUserId)
                ->where('id', '!=', $pangkalan->id)
                ->exists();

            if (!$stillKepala) {
                User::where('id', $oldKepalaUserId)->update(['is_kepala' => false]);
            }

            // Remove from kepala_pangkalan pivot for this pangkalan
            $oldUser = User::find($oldKepalaUserId);
            if ($oldUser) {
                $oldUser->kepalaPangkalan()->detach($pangkalan->id);
            }
        }
    }

    /**
     * Tambahkan pangkalan wajib ke semua karyawan aktif (kecuali kepala).
     */
    private function syncWajibPangkalan(Pangkalan $pangkalan): void
    {
        $karyawanIds = \App\Models\Karyawan::where('is_active', true)
            ->whereDoesntHave('user', fn($q) => $q->where('is_kepala', true))
            ->pluck('id');
        foreach ($karyawanIds as $karyawanId) {
            $karyawan = \App\Models\Karyawan::find($karyawanId);
            if ($karyawan && !$karyawan->pangkalans()->where('pangkalan_id', $pangkalan->id)->exists()) {
                $karyawan->pangkalans()->attach($pangkalan->id);
            }
        }
    }

    /**
     * Hapus pangkalan wajib dari semua karyawan (saat berubah jadi non-wajib).
     */
    private function removeWajibPangkalan(Pangkalan $pangkalan): void
    {
        $pangkalan->karyawanPivot()->detach();
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
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }
}



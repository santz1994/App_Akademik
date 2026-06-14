<?php

namespace App\Http\Controllers;

use App\Models\Pangkalan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $selectedPangkalan = $request->input('pangkalan_id');
        $search = trim((string) $request->input('q'));

        $users = User::with(['karyawan.pangkalans', 'pangkalan'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($selectedPangkalan, fn ($q) => $q->whereHas('karyawan.pangkalans', fn ($pq) => $pq->where('pangkalan.id', $selectedPangkalan)))
            ->latest();

        $users = $this->paginateWithPerPage($users, $request, 10);

        $pangkalanList = Pangkalan::orderBy('nama_pangkalan')->get();

        return view('admin.users.index', compact('users', 'pangkalanList', 'selectedPangkalan'));
    }

    public function create()
    {
        $pangkalanList = Pangkalan::orderBy('nama_pangkalan')->get();

        return view('admin.users.create', compact('pangkalanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,user,tata_usaha',
        ]);

        $createdUser = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_kepala' => false, // Kepala ditentukan dari data pangkalan
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $pangkalanList = Pangkalan::orderBy('nama_pangkalan')->get();

        return view('admin.users.edit', compact('user', 'pangkalanList'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,user,tata_usaha',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only('name', 'username', 'email', 'role');
        // is_kepala tidak diubah dari sini, ditentukan dari data pangkalan

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Remove kepala assignment from all pangkalan before deleting user
        Pangkalan::where('kepala_user_id', $user->id)->update(['kepala_user_id' => null]);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ]);

        $rows = Excel::toArray([], $request->file('file'))[0] ?? [];
        if (empty($rows)) {
            return back()->with('error', 'File import kosong atau format tidak dikenali.');
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $idx => $row) {
            if (! is_array($row)) {
                $skipped++;

                continue;
            }

            if ($idx === 0 && $this->looksLikeUserHeaderRow($row)) {
                continue;
            }

            $name = trim((string) ($row[0] ?? ''));
            $username = trim((string) ($row[1] ?? ''));
            $email = trim((string) ($row[2] ?? ''));
            $passwordRaw = trim((string) ($row[3] ?? ''));
            $role = strtolower(trim((string) ($row[4] ?? 'user')));
            $kodePangkalan = trim((string) ($row[5] ?? ''));
            $isKepalaRaw = $row[6] ?? null;

            if ($name === '' || $username === '' || $email === '') {
                $skipped++;

                continue;
            }

            if (! in_array($role, ['admin', 'user'], true)) {
                $skipped++;

                continue;
            }

            $conflictEmail = User::where('email', $email)
                ->where('username', '!=', $username)
                ->exists();
            if ($conflictEmail) {
                $skipped++;

                continue;
            }

            $pangkalanId = null;
            if ($role !== 'admin' && $kodePangkalan !== '') {
                $pangkalan = Pangkalan::where('kode_pangkalan', $kodePangkalan)->first();
                if (! $pangkalan) {
                    $skipped++;

                    continue;
                }
                $pangkalanId = $pangkalan->id;
            }

            $isKepala = $this->parseBooleanValue($isKepalaRaw) ?? false;
            if ($role === 'admin') {
                $isKepala = false;
                $pangkalanId = null;
            }

            if ($isKepala && ! $pangkalanId) {
                $skipped++;

                continue;
            }

            $existing = User::where('username', $username)->first();

            $data = [
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'pangkalan_id' => $pangkalanId,
                'is_kepala' => false, // Kepala ditentukan dari data pangkalan
            ];

            if ($passwordRaw !== '') {
                $data['password'] = Hash::make($passwordRaw);
            } elseif (! $existing) {
                $data['password'] = Hash::make('user12345');
            }

            if ($existing) {
                $existing->update($data);
                $updated++;
            } else {
                User::create($data);
                $imported++;
            }
        }

        return back()->with('success', "Import user selesai. Baru: {$imported}, update: {$updated}, dilewati: {$skipped}.");
    }

    private function looksLikeUserHeaderRow(array $row): bool
    {
        $header = strtolower(implode(' ', array_map(fn ($v) => trim((string) $v), $row)));

        return str_contains($header, 'username')
            || str_contains($header, 'email')
            || str_contains($header, 'password')
            || str_contains($header, 'role')
            || str_contains($header, 'is_kepala');
    }

    private function parseBooleanValue(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        if (in_array($normalized, ['1', 'true', 'yes', 'ya', 'aktif', 'active'], true)) {
            return true;
        }

        if (in_array($normalized, ['0', 'false', 'no', 'tidak', 'nonaktif', 'inactive'], true)) {
            return false;
        }

        return null;
    }

    private function syncPimpinanPosByUser(User $user, ?array $before, array $pangkalanTambahan = []): void
    {
        if ($before) {
            $this->clearPimpinanPosIfOwned($before);
        }

        // Sync kepala_pangkalan pivot table
        if ($user->is_kepala && $user->pangkalan_id) {
            // Collect all pangkalan IDs: primary + tambahan
            $allPangkalanIds = array_unique(array_merge(
                [$user->pangkalan_id],
                array_map('intval', $pangkalanTambahan)
            ));
            $user->kepalaPangkalan()->sync($allPangkalanIds);
        } else {
            // If not kepala, remove all pivot entries
            $user->kepalaPangkalan()->detach();
        }

        if (! $user->is_kepala || ! $user->pangkalan_id) {
            return;
        }

        Pangkalan::where('id', $user->pangkalan_id)->update([
            'pimpinan_pos' => $user->name,
        ]);
    }

    private function clearPimpinanPosIfOwned(array $userSnapshot): void
    {
        if (empty($userSnapshot['is_kepala']) || empty($userSnapshot['pangkalan_id'])) {
            return;
        }

        $pangkalan = Pangkalan::find($userSnapshot['pangkalan_id']);
        if (! $pangkalan) {
            return;
        }

        if (trim((string) $pangkalan->pimpinan_pos) === trim((string) $userSnapshot['name'])) {
            $pangkalan->update(['pimpinan_pos' => null]);
        }
    }
}

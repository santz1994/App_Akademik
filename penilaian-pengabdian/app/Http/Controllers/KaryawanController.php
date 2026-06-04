<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\SettingLembaga;
use App\Models\TahunPenilaian;
use App\Models\User;
use App\Models\Pangkalan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Format;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q'));
        $filterPangkalan = $request->input('pangkalan_id');
        $filterStatusAktif = $request->input('status_aktif');
        $filterStatusKepala = $request->input('status_kepala', 'nonkepala');

        $karyawan = Karyawan::with(['tahunPenilaian', 'pangkalan', 'user'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_karyawan', 'like', "%{$search}%")
                        ->orWhere('kode_karyawan', 'like', "%{$search}%")
                        ->orWhere('nomor_induk', 'like', "%{$search}%");
                });
            })
            ->when($filterPangkalan, fn($q) => $q->where('pangkalan_id', $filterPangkalan))
            ->when($filterStatusAktif === 'aktif', fn($q) => $q->where('is_active', true))
            ->when($filterStatusAktif === 'nonaktif', fn($q) => $q->where('is_active', false))
            ->when($filterStatusKepala === 'kepala', fn($q) =>
                $q->whereHas('user', fn($uq) => $uq->where('is_kepala', true))
            )
            ->when($filterStatusKepala === 'nonkepala', fn($q) =>
                $q->where(function ($sub) {
                    $sub->whereDoesntHave('user')
                        ->orWhereHas('user', fn($uq) => $uq->where('is_kepala', false));
                })
            )
            ->latest();

        $karyawan = $this->paginateWithPerPage($karyawan, $request, 10);
        $pangkalanList = Pangkalan::orderBy('nama_pangkalan')->get();

        return view('admin.karyawan.index', compact(
            'karyawan',
            'pangkalanList',
            'search',
            'filterPangkalan',
            'filterStatusAktif',
            'filterStatusKepala'
        ));
    }

    public function create()
    {
        $tahunAktif   = TahunPenilaian::where('is_active', true)->first();
        $kode         = $this->generateNextKodeKaryawan();
        $linkedUserIds = Karyawan::whereNotNull('user_id')->pluck('user_id');
        $users        = User::with('pangkalan')->whereNotIn('id', $linkedUserIds)->orderBy('name')->get();
        $pangkalan    = Pangkalan::orderBy('nama_pangkalan')->get();
        return view('admin.karyawan.create', compact('tahunAktif', 'kode', 'users', 'pangkalan'));
    }

    public function profilePdf(Karyawan $karyawan)
    {
        $karyawan->load(['tahunPenilaian', 'pangkalan', 'user']);
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();

        $fileName = 'profil-karyawan-' . strtolower($karyawan->kode_karyawan) . '.pdf';

        return Pdf::loadView('admin.karyawan.profile_pdf', compact('karyawan', 'setting'))
            ->setPaper('a4', 'portrait')
            ->stream($fileName);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'nullable|string|max:150',
            'nomor_induk'   => 'nullable|string|max:50|unique:karyawan,nomor_induk',
            'jenis_kelamin' => 'nullable|in:L,P',
            'nomor_surat_tugas' => 'nullable|string|max:100',
            'tanggal_surat_tugas' => 'nullable|date',
            'is_active'     => 'required|boolean',
            'alamat'        => 'nullable|string',
            'email'         => 'nullable|email|max:150',
            'no_hp'         => 'nullable|string|max:20',
            'kontak_darurat'=> 'nullable|string|max:150',
            'foto'          => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'user_id'       => 'nullable|exists:users,id|unique:karyawan,user_id',
            'pangkalan_id'  => 'nullable|exists:pangkalan,id',
            'pangkalan_tambahan' => 'nullable|array',
            'pangkalan_tambahan.*' => 'exists:pangkalan,id',
            'tugas_khusus'  => 'nullable|string|max:255',
        ]);

        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('karyawan-foto', 'public')
            : null;
        $linkedUser = $request->filled('user_id') ? User::find($request->user_id) : null;
        $resolvedNamaKaryawan = trim((string) $request->input('nama_karyawan', ''));
        if ($resolvedNamaKaryawan === '' && $linkedUser) {
            $resolvedNamaKaryawan = trim((string) $linkedUser->name);
        }
        if ($resolvedNamaKaryawan === '') {
            return back()->withInput()->withErrors([
                'nama_karyawan' => 'Nama karyawan wajib diisi atau pilih user account yang memiliki nama.',
            ]);
        }

        $resolvedPangkalanId = $request->pangkalan_id ?: null;

        if ($linkedUser?->pangkalan_id) {
            $resolvedPangkalanId = $linkedUser->pangkalan_id;
        }

        if ($linkedUser?->is_kepala) {
            if (!$linkedUser->pangkalan_id) {
                return back()
                    ->withInput()
                    ->withErrors(['user_id' => 'User kepala harus memiliki pangkalan. Silakan perbarui data user terlebih dahulu.']);
            }
            $resolvedPangkalanId = $linkedUser->pangkalan_id;
        }

        $payload = [
            'nama_karyawan'      => $resolvedNamaKaryawan,
            'nomor_induk'        => $request->filled('nomor_induk') ? trim((string) $request->nomor_induk) : null,
            'jenis_kelamin'      => $request->filled('jenis_kelamin') ? $request->jenis_kelamin : null,
            'nomor_surat_tugas'  => $request->filled('nomor_surat_tugas') ? trim((string) $request->nomor_surat_tugas) : null,
            'tanggal_surat_tugas'=> $request->filled('tanggal_surat_tugas') ? $request->tanggal_surat_tugas : null,
            'is_active'          => $request->boolean('is_active', true),
            'alamat'             => $request->alamat,
            'email'              => $request->filled('email') ? trim((string) $request->email) : null,
            'no_hp'              => $request->filled('no_hp') ? trim((string) $request->no_hp) : null,
            'kontak_darurat'     => $request->filled('kontak_darurat') ? trim((string) $request->kontak_darurat) : null,
            'foto_path'          => $fotoPath,
            'tahun_penilaian_id' => $tahunAktif?->id,
            'user_id'            => $request->user_id ?: null,
            'pangkalan_id'       => $resolvedPangkalanId,
            'tugas_khusus'       => $request->tugas_khusus,
        ];

        $created = false;
        for ($attempt = 0; $attempt < 3; $attempt++) {
            try {
                Karyawan::create(array_merge($payload, [
                    'kode_karyawan' => $this->generateNextKodeKaryawan(),
                ]));
                $created = true;
                break;
            } catch (QueryException $exception) {
                if (!$this->isDuplicateKodeKaryawanException($exception)) {
                    throw $exception;
                }
            }
        }

        if (!$created) {
            return back()
                ->withInput()
                ->withErrors(['nama_karyawan' => 'Gagal membuat kode karyawan unik. Silakan coba lagi.']);
        }

        // Sync pangkalan tambahan pivot table
        $lastKaryawan = Karyawan::latest('id')->first();
        if ($lastKaryawan) {
            $pangkalanTambahan = $request->input('pangkalan_tambahan', []);
            $allPangkalanIds = array_unique(array_merge(
                $resolvedPangkalanId ? [$resolvedPangkalanId] : [],
                array_map('intval', $pangkalanTambahan)
            ));
            $lastKaryawan->pangkalanLain()->sync($allPangkalanIds);
        }

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        $linkedUserIds = Karyawan::whereNotNull('user_id')
            ->where('id', '!=', $karyawan->id)
            ->pluck('user_id');
        $users     = User::with('pangkalan')->whereNotIn('id', $linkedUserIds)->orderBy('name')->get();
        $pangkalan = Pangkalan::orderBy('nama_pangkalan')->get();
        return view('admin.karyawan.edit', compact('karyawan', 'users', 'pangkalan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama_karyawan' => 'nullable|string|max:150',
            'nomor_induk'   => 'nullable|string|max:50|unique:karyawan,nomor_induk,' . $karyawan->id,
            'jenis_kelamin' => 'nullable|in:L,P',
            'nomor_surat_tugas' => 'nullable|string|max:100',
            'tanggal_surat_tugas' => 'nullable|date',
            'is_active'     => 'required|boolean',
            'alamat'        => 'nullable|string',
            'email'         => 'nullable|email|max:150',
            'no_hp'         => 'nullable|string|max:20',
            'kontak_darurat'=> 'nullable|string|max:150',
            'foto'          => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'user_id'       => 'nullable|exists:users,id|unique:karyawan,user_id,' . $karyawan->id,
            'pangkalan_id'  => 'nullable|exists:pangkalan,id',
            'pangkalan_tambahan' => 'nullable|array',
            'pangkalan_tambahan.*' => 'exists:pangkalan,id',
            'tugas_khusus'  => 'nullable|string|max:255',
        ]);
    
        // AWAL: Gunakan path lama sebagai default agar tidak undefined
        $fotoPath = $karyawan->foto_path;
    
        if ($request->hasFile('foto')) {
            try {
                // Hapus foto lama jika ada file baru yang masuk
                if ($karyawan->foto_path && \Storage::disk('public')->exists($karyawan->foto_path)) {
                    \Storage::disk('public')->delete($karyawan->foto_path);
                }
    
                $file = $request->file('foto');
                $filename = time() . '.png';
                
                // Membaca file gambar dari temporary path menggunakan decodePath (v4.x API)
                $img = Image::decodePath($file->getRealPath());
                
                // Melakukan auto-crop dengan rasio 3:4
                $img->cover(300, 400);
                
                $savePath = 'karyawan-foto/' . $filename;
                
                // Encode menggunakan PNG format (v4.x API) dan simpan ke storage
                $encoded = $img->encodeUsingFormat(Format::PNG);
                \Storage::disk('public')->put($savePath, (string) $encoded);
                
                // Update variabel path untuk database
                $fotoPath = $savePath;
    
            } catch (\Throwable $e) {
                // Gunakan \Throwable agar Error fatal tetap tertangkap dan kembali ke form
                return back()->withInput()->withErrors(['foto' => 'Gagal memproses gambar: ' . $e->getMessage()]);
            }
        }
    
        $linkedUser = $request->filled('user_id') ? \App\Models\User::find($request->user_id) : null;
        $resolvedNamaKaryawan = trim((string) $request->input('nama_karyawan', ''));
        
        if ($resolvedNamaKaryawan === '' && $linkedUser) {
            $resolvedNamaKaryawan = trim((string) $linkedUser->name);
        }
        
        if ($resolvedNamaKaryawan === '') {
            return back()->withInput()->withErrors(['nama_karyawan' => 'Nama karyawan wajib diisi.']);
        }
    
        $resolvedPangkalanId = $request->pangkalan_id ?: $karyawan->pangkalan_id;
        if ($linkedUser?->pangkalan_id) { 
            $resolvedPangkalanId = $linkedUser->pangkalan_id; 
        }
    
        // UPDATE DATABASE
        $karyawan->update([
            'nama_karyawan'       => $resolvedNamaKaryawan,
            'nomor_induk'         => $request->filled('nomor_induk') ? trim((string) $request->nomor_induk) : null,
            'jenis_kelamin'       => $request->filled('jenis_kelamin') ? $request->jenis_kelamin : null,
            'nomor_surat_tugas'   => $request->filled('nomor_surat_tugas') ? trim((string) $request->nomor_surat_tugas) : null,
            'tanggal_surat_tugas' => $request->filled('tanggal_surat_tugas') ? $request->tanggal_surat_tugas : null,
            'is_active'           => $request->boolean('is_active', true),
            'alamat'              => $request->alamat,
            'email'              => $request->filled('email') ? trim((string) $request->email) : null,
            'no_hp'              => $request->filled('no_hp') ? trim((string) $request->no_hp) : null,
            'kontak_darurat'     => $request->filled('kontak_darurat') ? trim((string) $request->kontak_darurat) : null,
            'foto_path'           => $fotoPath, // Sekarang variabel ini pasti ada isinya
            'user_id'             => $request->user_id ?: null,
            'pangkalan_id'        => $resolvedPangkalanId,
            'tugas_khusus'        => $request->tugas_khusus,
        ]);
    
        // Sync pangkalan tambahan pivot table
        $pangkalanTambahan = $request->input('pangkalan_tambahan', []);
        $allPangkalanIds = array_unique(array_merge(
            $resolvedPangkalanId ? [$resolvedPangkalanId] : [],
            array_map('intval', $pangkalanTambahan)
        ));
        $karyawan->pangkalanLain()->sync($allPangkalanIds);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function toggleStatus(Karyawan $karyawan)
    {
        $karyawan->update(['is_active' => !$karyawan->is_active]);

        return back()->with(
            'success',
            'Status karyawan ' . $karyawan->nama_karyawan . ' berhasil diubah menjadi ' . ($karyawan->is_active ? 'Aktif.' : 'Tidak Aktif.')
        );
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

        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $counter = Karyawan::count();
        $imported = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $idx => $row) {
            if (!is_array($row)) {
                $skipped++;
                continue;
            }

            if ($idx === 0 && $this->looksLikeKaryawanHeaderRow($row)) {
                continue;
            }

            $kode = trim((string)($row[0] ?? ''));
            $nama = trim((string)($row[1] ?? ''));
            $kodePangkalan = trim((string)($row[2] ?? ''));
            $tugasKhusus = trim((string)($row[3] ?? ''));
            $alamat = trim((string)($row[4] ?? ''));
            $isActiveRaw = $row[5] ?? null;
            $username = trim((string)($row[6] ?? ''));
            $tahunRaw = trim((string)($row[7] ?? ''));

            if ($nama === '') {
                $skipped++;
                continue;
            }

            $pangkalan = null;
            if ($kodePangkalan !== '') {
                $pangkalan = Pangkalan::where('kode_pangkalan', $kodePangkalan)->first();
                if (!$pangkalan) {
                    $skipped++;
                    continue;
                }
            }

            $user = null;
            if ($username !== '') {
                $user = User::where('username', $username)->first();
                if (!$user) {
                    $skipped++;
                    continue;
                }
            }

            $tahun = $tahunAktif;
            if ($tahunRaw !== '') {
                $tahun = is_numeric($tahunRaw)
                    ? TahunPenilaian::find((int) $tahunRaw)
                    : TahunPenilaian::where('periode_penilaian', $tahunRaw)->first();
            }

            if (!$tahun) {
                $skipped++;
                continue;
            }

            $isActiveParsed = $this->parseActiveFlag($isActiveRaw);
            $isActive = $isActiveParsed ?? true;

            if ($kode === '') {
                $counter++;
                $kode = $this->generateNextKodeKaryawan();
            }

            $existing = Karyawan::where('kode_karyawan', $kode)->first();

            if ($user) {
                $linkedUserExists = Karyawan::where('user_id', $user->id)
                    ->when($existing, fn($q) => $q->where('id', '!=', $existing->id))
                    ->exists();

                if ($linkedUserExists) {
                    $user = null;
                }
            }

            $payload = [
                'kode_karyawan' => $kode,
                'nama_karyawan' => $nama,
                'is_active' => $isActive,
                'alamat' => $alamat !== '' ? $alamat : null,
                'tugas_khusus' => $tugasKhusus !== '' ? $tugasKhusus : null,
                'tahun_penilaian_id' => $tahun->id,
                'pangkalan_id' => $pangkalan?->id,
                'user_id' => $user?->id,
            ];

            if ($existing) {
                $existing->update($payload);
                $updated++;
            } else {
                Karyawan::create($payload);
                $imported++;
            }
        }

        return back()->with('success', "Import karyawan selesai. Baru: {$imported}, update: {$updated}, dilewati: {$skipped}.");
    }

    private function looksLikeKaryawanHeaderRow(array $row): bool
    {
        $header = strtolower(implode(' ', array_map(fn($v) => trim((string) $v), $row)));

        return str_contains($header, 'nama_karyawan')
            || str_contains($header, 'kode_pangkalan')
            || str_contains($header, 'tugas_khusus')
            || str_contains($header, 'is_active')
            || str_contains($header, 'username');
    }

    private function parseActiveFlag(mixed $value): ?bool
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

    private function generateNextKodeKaryawan(): string
    {
        $nextNumber = ((int) Karyawan::query()
            ->selectRaw("COALESCE(MAX(CAST(SUBSTRING_INDEX(kode_karyawan, '-', -1) AS UNSIGNED)), 0) as max_num")
            ->value('max_num')) + 1;

        do {
            $kode = 'KRY-' . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (Karyawan::where('kode_karyawan', $kode)->exists());

        return $kode;
    }

    private function isDuplicateKodeKaryawanException(QueryException $exception): bool
    {
        $message = strtolower($exception->getMessage());

        return str_contains($message, 'duplicate entry')
            && (str_contains($message, 'kode_karyawan') || str_contains($message, 'karyawan_kode_karyawan_unique'));
    }
}
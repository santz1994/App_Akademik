<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\TahunPenilaian;
use App\Models\User;
use App\Models\Pangkalan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::with(['tahunPenilaian', 'pangkalan'])->latest()->paginate(10);
        return view('admin.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        $tahunAktif   = TahunPenilaian::where('is_active', true)->first();
        $kode         = 'KRY-' . str_pad(Karyawan::count() + 1, 4, '0', STR_PAD_LEFT);
        $linkedUserIds = Karyawan::whereNotNull('user_id')->pluck('user_id');
        $users        = User::whereNotIn('id', $linkedUserIds)->orderBy('name')->get();
        $pangkalan    = Pangkalan::orderBy('nama_pangkalan')->get();
        return view('admin.karyawan.create', compact('tahunAktif', 'kode', 'users', 'pangkalan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'required|string|max:150',
            'alamat'        => 'nullable|string',
            'user_id'       => 'nullable|exists:users,id|unique:karyawan,user_id',
            'pangkalan_id'  => 'nullable|exists:pangkalan,id',
            'tugas_khusus'  => 'nullable|string|max:255',
        ]);

        $tahunAktif = TahunPenilaian::where('is_active', true)->first();
        $kode = 'KRY-' . str_pad(Karyawan::count() + 1, 4, '0', STR_PAD_LEFT);

        Karyawan::create([
            'kode_karyawan'      => $kode,
            'nama_karyawan'      => $request->nama_karyawan,
            'alamat'             => $request->alamat,
            'tahun_penilaian_id' => $tahunAktif?->id,
            'user_id'            => $request->user_id ?: null,
            'pangkalan_id'       => $request->pangkalan_id ?: null,
            'tugas_khusus'       => $request->tugas_khusus,
        ]);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        $linkedUserIds = Karyawan::whereNotNull('user_id')
            ->where('id', '!=', $karyawan->id)
            ->pluck('user_id');
        $users     = User::whereNotIn('id', $linkedUserIds)->orderBy('name')->get();
        $pangkalan = Pangkalan::orderBy('nama_pangkalan')->get();
        return view('admin.karyawan.edit', compact('karyawan', 'users', 'pangkalan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama_karyawan' => 'required|string|max:150',
            'alamat'        => 'nullable|string',
            'user_id'       => 'nullable|exists:users,id|unique:karyawan,user_id,' . $karyawan->id,
            'pangkalan_id'  => 'nullable|exists:pangkalan,id',
            'tugas_khusus'  => 'nullable|string|max:255',
        ]);

        $karyawan->update([
            'nama_karyawan' => $request->nama_karyawan,
            'alamat'        => $request->alamat,
            'user_id'       => $request->user_id ?: null,
            'pangkalan_id'  => $request->pangkalan_id ?: null,
            'tugas_khusus'  => $request->tugas_khusus,
        ]);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }
}

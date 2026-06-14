<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        return view('user.dashboard', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        $karyawan = $user->karyawan;

        return view('user.profile', compact('user', 'karyawan'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'no_hp' => 'nullable|string|max:20',
            'kontak_darurat' => 'nullable|string|max:150',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Also update karyawan data if exists
        if ($user->karyawan) {
            $user->karyawan->update([
                'email' => $request->email,
                'no_hp' => $request->filled('no_hp') ? $request->no_hp : $user->karyawan->no_hp,
                'kontak_darurat' => $request->filled('kontak_darurat') ? $request->kontak_darurat : $user->karyawan->kontak_darurat,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}

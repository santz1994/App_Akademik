<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\SettingLembaga;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }

        // Generate dynamic captcha (4 random digits)
        $captchaCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        session(['login_captcha' => $captchaCode]);

        // Get logo from settings
        $setting = SettingLembaga::where('is_active', true)->latest()->first()
            ?? SettingLembaga::latest()->first();
        $loginLogoUrl = null;
        if ($setting?->show_logo && ! empty($setting->logo_path)) {
            $logoPath = ltrim((string) $setting->logo_path, '/');
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
                $loginLogoUrl = asset('storage/'.$logoPath);
            }
        }

        return view('auth.login', compact('captchaCode', 'loginLogoUrl'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'captcha.required' => 'Captcha wajib diisi.',
        ]);

        // Validate captcha
        $expectedCaptcha = session('login_captcha');
        session()->forget('login_captcha');

        if ($request->captcha !== $expectedCaptcha) {
            return back()->withErrors([
                'captcha' => 'Captcha tidak sesuai. Silakan coba lagi.',
            ])->withInput($request->only('username'));
        }

        $loginInput = $request->username;

        // Support login with email: find user by email, then use username for auth
        $userByEmail = User::where('email', $loginInput)->first();
        if ($userByEmail) {
            $loginInput = $userByEmail->username;
        }

        $credentials = [
            'username' => $loginInput,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Check if linked karyawan is inactive
            if ($user->karyawan && ! $user->karyawan->is_active && $user->role !== 'admin') {
                Auth::logout();

                return back()->withErrors([
                    'username' => 'Akun Anda telah dinonaktifkan karena data karyawan tidak aktif. Silakan hubungi Admin.',
                ])->withInput($request->only('username'));
            }

            $request->session()->regenerate();

            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Show activation form for karyawan to create their user account
     */
    public function showActivate()
    {
        return view('auth.activate');
    }

    /**
     * Process activation: karyawan enters email that exists in karyawan table
     */
    public function activate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $email = $request->email;

        // Find karyawan by email
        $karyawan = Karyawan::where('email', $email)->first();

        if (! $karyawan) {
            return back()->withInput()->withErrors([
                'email' => 'Email tidak terdaftar di data karyawan. Silakan hubungi Admin.',
            ]);
        }

        if (! $karyawan->is_active) {
            return back()->withInput()->withErrors([
                'email' => 'Data karyawan tidak aktif. Silakan hubungi Admin.',
            ]);
        }

        // Check if karyawan already has user account
        if ($karyawan->user_id) {
            return back()->withInput()->withErrors([
                'email' => 'Karyawan ini sudah memiliki akun user. Silakan login menggunakan akun yang sudah ada.',
            ]);
        }

        // Check if email already used by another user
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            return back()->withInput()->withErrors([
                'email' => 'Email sudah digunakan oleh akun lain. Silakan hubungi Admin.',
            ]);
        }

        // Create user account
        $username = strtolower(str_replace(' ', '_', $karyawan->nama_karyawan));

        // Ensure unique username
        $baseUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername.'_'.$counter;
            $counter++;
        }

        $user = User::create([
            'name' => $karyawan->nama_karyawan,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Link karyawan to user
        $karyawan->update(['user_id' => $user->id]);

        return redirect()->route('login')->with('success', 'Akun berhasil diaktifkan! Silakan login dengan email atau username: <strong>'.$username.'</strong>');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'tata_usaha') {
            return redirect()->route('tata-usaha.dashboard');
        }

        if ($user->is_kepala) {
            return redirect()->route('kepala.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}

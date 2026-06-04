<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Penilaian Pengabdian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5; /* Background abu-abu muda untuk mengisolasi kartu */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }
        .login-card {
            width: 100%;
            max-width: 380px; /* Sedikit lebih ramping seperti referensi */
            background: #fff;
            border-radius: 1.5rem; /* Lebih membulat */
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            padding: 3rem 2.5rem 1.5rem; /* Spacing internal */
        }
        /* Elemen Gelombang Blue Di Pojok Atas Kanan */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 160px;
            height: 80px;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border-bottom-left-radius: 100%;
            z-index: 0;
        }
        /* Logo */
        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }
        .logo-container img {
            width: auto;
            height: 48px; /* Sesuaikan tinggi logo */
            margin-bottom: 0.5rem;
        }
        .logo-container h5 {
            font-weight: 700;
            color: #1e3a8a;
            margin: 0;
            font-size: 1.1rem;
        }
        /* Teks Welcome */
        .welcome-text {
            text-align: center;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        /* Styling Input Group Underline */
        .input-group-underline {
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .input-group-underline .input-icon {
            color: #888;
            font-size: 1.2rem;
            margin-right: 12px;
        }
        .input-group-underline .form-control {
            border: none;
            border-bottom: 2px solid #ccc;
            border-radius: 0;
            background: none;
            padding: 10px 0;
            color: #555;
            font-size: 0.95rem;
            width: 100%;
        }
        .input-group-underline .form-control:focus {
            box-shadow: none;
            border-color: #3b82f6;
        }
        /* Toggle Password dengan Icon Eye Line */
        .password-toggle {
            position: absolute;
            right: 0;
            bottom: 12px;
            background: none;
            border: none;
            padding: 0;
            color: #888;
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 2;
        }
        .password-toggle:hover {
            color: #3b82f6;
        }
        /* Styling Captcha Field */
        .captcha-container {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-end;
            position: relative;
            z-index: 1;
        }
        .captcha-box {
            background-color: #eee;
            border-radius: 4px;
            padding: 10px;
            width: 80px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            color: #222;
            margin-right: 15px;
        }
        .captcha-box .code-6 { color: #d63384; } /* Warna code */
        .captcha-box .code-8 { color: #6f42c1; }
        .captcha-box .code-3 { color: #0dcaf0; }
        .captcha-input {
            width: calc(100% - 95px);
        }
        /* Styling Checkbox 'Remember me' */
        .form-check {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .form-check-input {
            width: 18px; height: 18px;
            border-radius: 3px;
            margin-top: 0;
            border: 2px solid #3b82f6;
            margin-right: 8px;
        }
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .form-check-label {
            color: #555;
            font-size: 0.85rem;
            margin-bottom: 0;
        }
        /* Styling Tombol Login */
        .btn-login {
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 0;
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            position: relative;
            z-index: 1;
        }
        .btn-login:hover {
            background-color: #1d4ed8;
        }
        /* Remove original header and footer content but keep their functionality */
        .original-header, .original-footer { display: none; }
    </style>
</head>
<body>

<div class="login-card">
    
    <div class="logo-container">
        @if(!empty($loginLogoUrl))
            <img src="{{ $loginLogoUrl }}" alt="Logo">
        @else
            <i class="bi bi-award-fill" style="font-size:48px; color:#1e3a8a;"></i>
        @endif
        <h5>Penilaian Pengabdian</h5>
    </div>

    <div class="welcome-text">Silahkan login ke panel admin</div>

    <div class="login-body">
        
        @if(session('success'))
            <div class="alert alert-success alert-sm py-2" style="font-size: 0.85rem; border-radius: 4px;">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-sm py-2" style="font-size: 0.85rem; border-radius: 4px;">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="input-group-underline">
                <i class="bi bi-person input-icon"></i>
                <input type="text"
                       name="username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}"
                       placeholder="Username atau Email"
                       required
                       autofocus>
            </div>

            <div class="input-group-underline">
                <i class="bi bi-lock input-icon"></i>
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password"
                       required>
                <button class="password-toggle" type="button" onclick="togglePassword()" title="Tampilkan/Sembunyikan">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>

            <div class="captcha-container">
                <div class="captcha-box" style="letter-spacing: 3px; user-select: none; font-family: 'Courier New', monospace; font-style: italic; text-decoration: line-through; opacity: 0.85;">
                    @foreach(str_split($captchaCode) as $i => $digit)
                        <span class="code-{{ $digit }}" style="color: {{ ['#d63384','#6f42c1','#0dcaf0','#198754','#dc3545','#fd7e14','#0d6efd','#6610f2','#20c997','#ffc107'][$digit] }};">{{ $digit }}</span>
                    @endforeach
                </div>
                <div class="input-group-underline captcha-input">
                    <input type="text" name="captcha" class="form-control" placeholder="Masukkan captcha" required maxlength="4" autocomplete="off">
                </div>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="text-center mt-3" style="position: relative; z-index: 1;">
            <span style="color: #888; font-size: 0.85rem;">Belum punya akun?</span>
            <a href="{{ route('activate') }}" style="color: #3b82f6; font-size: 0.85rem; text-decoration: none; font-weight: 600;">
                Aktivasi Akun
            </a>
        </div>
    </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/**
 * Fungsi Toggle Password: Diperbarui untuk mengubah icon mata line art.
 */
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash'; // Icon mata tercoret
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye'; // Icon mata terbuka
    }
}
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Penilaian Pengabdian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $setting = \App\Models\SettingLembaga::where('is_active', true)->latest()->first()
            ?? \App\Models\SettingLembaga::latest()->first();
        $loginTitle = trim((string) ($setting?->sidebar_title ?? ''));
        $loginSubtitle = trim((string) ($setting?->sidebar_subtitle_1 ?? ''));
        $loginSubtitle2 = trim((string) ($setting?->sidebar_subtitle_2 ?? ''));
        $logoPath = ltrim((string) ($setting?->logo_path ?? ''), '/');
        $logoUrl = null;
        if ($setting?->show_logo && $logoPath !== '' && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
            $logoUrl = asset('storage/' . $logoPath);
        }
        if ($loginTitle === '') {
            $loginTitle = 'Penilaian Pengabdian';
        }
    @endphp
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1a56db 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(147, 51, 234, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(1deg); }
            66% { transform: translate(-20px, 20px) rotate(-1deg); }
        }

        /* Decorative circles */
        .deco-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            z-index: 0;
        }
        .deco-circle-1 {
            width: 400px; height: 400px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            top: -120px; right: -100px;
            animation: pulse 8s ease-in-out infinite;
        }
        .deco-circle-2 {
            width: 300px; height: 300px;
            background: linear-gradient(135deg, #06b6d4, #3b82f6);
            bottom: -80px; left: -60px;
            animation: pulse 10s ease-in-out infinite reverse;
        }
        .deco-circle-3 {
            width: 150px; height: 150px;
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            top: 30%; left: 10%;
            animation: pulse 6s ease-in-out infinite 2s;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.08; }
            50% { transform: scale(1.1); opacity: 0.12; }
        }

        /* Main container */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }

        /* Login Card */
        .login-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 1.25rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3),
                        0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            backdrop-filter: blur(20px);
        }

        /* Top gradient banner */
        .login-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #1a56db 50%, #3b82f6 100%);
            padding: 2rem 2rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .login-banner::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        .login-banner::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        /* Logo */
        .login-logo-wrap {
            width: 72px;
            height: 72px;
            margin: 0 auto 1rem;
            background: #fff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
            overflow: hidden;
            padding: 8px;
        }
        .login-logo-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }
        .login-logo-wrap .logo-placeholder {
            font-size: 2rem;
            color: #1a56db;
        }

        .login-banner h1 {
            color: #fff;
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0 0 0.3rem;
            letter-spacing: -0.01em;
            position: relative;
            z-index: 1;
        }
        .login-banner .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
            font-weight: 400;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        .login-banner .subtitle + .subtitle {
            font-size: 0.72rem;
            margin-top: 0.15rem;
            color: rgba(255, 255, 255, 0.65);
        }

        /* Wave divider */
        .wave-divider {
            margin-top: -1px;
            line-height: 0;
        }
        .wave-divider svg {
            display: block;
            width: 100%;
            height: 32px;
        }

        /* Form body */
        .login-body {
            padding: 1.75rem 2rem 2rem;
        }

        .login-body .welcome-heading {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        .login-body .welcome-sub {
            font-size: 0.84rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* Alert styling */
        .alert {
            border-radius: 0.65rem;
            font-size: 0.84rem;
            border: none;
            padding: 0.7rem 1rem;
            margin-bottom: 1.25rem;
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
        }
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        /* Input groups */
        .input-field {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .input-field .input-icon-wrap {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 2;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }
        .input-field .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 2.8rem 0.75rem 2.8rem;
            font-size: 0.9rem;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.2s ease;
            height: auto;
        }
        .input-field .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .input-field .form-control:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .input-field .form-control:focus + .input-icon-wrap,
        .input-field .form-control:focus ~ .input-icon-wrap {
            color: #3b82f6;
        }
        .input-field:focus-within .input-icon-wrap {
            color: #3b82f6;
        }
        .input-field .is-invalid {
            border-color: #ef4444;
        }
        .input-field .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 4px;
            color: #94a3b8;
            font-size: 1.1rem;
            cursor: pointer;
            z-index: 2;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }
        .password-toggle:hover {
            color: #3b82f6;
        }

        /* Remember me & forgot password row */
        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }
        .form-check-input {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 2px solid #cbd5e1;
            margin: 0;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .form-check-label {
            font-size: 0.83rem;
            color: #64748b;
            cursor: pointer;
            margin: 0;
        }

        /* Login button */
        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #1a56db, #3b82f6);
            color: #fff;
            border: none;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1e40af, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(26, 86, 219, 0.35);
        }
        .btn-login:hover::before {
            left: 100%;
        }
        .btn-login:active {
            transform: translateY(0);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            padding: 0 2rem 1.5rem;
        }
        .login-footer p {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-wrapper {
                padding: 0.5rem;
            }
            .login-banner {
                padding: 1.5rem 1.5rem 2rem;
            }
            .login-body {
                padding: 1.25rem 1.5rem 1.5rem;
            }
            .login-logo-wrap {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>

    <!-- Decorative circles -->
    <div class="deco-circle deco-circle-1"></div>
    <div class="deco-circle deco-circle-2"></div>
    <div class="deco-circle deco-circle-3"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Banner with logo -->
            <div class="login-banner">
                <div class="login-logo-wrap">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Logo">
                    @else
                        <i class="bi bi-award-fill logo-placeholder"></i>
                    @endif
                </div>
                <h1>{{ $loginTitle }}</h1>
                @if($loginSubtitle !== '')
                    <p class="subtitle">{{ $loginSubtitle }}</p>
                @endif
                @if($loginSubtitle2 !== '')
                    <p class="subtitle">{{ $loginSubtitle2 }}</p>
                @endif
            </div>

            <!-- Wave divider -->
            <div class="wave-divider">
                <svg viewBox="0 0 440 32" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0,0 C120,32 320,32 440,0 L440,0 L0,0 Z" fill="#3b82f6" opacity="0.15"/>
                    <path d="M0,8 C100,28 340,28 440,8 L440,0 L0,0 Z" fill="#1a56db"/>
                </svg>
            </div>

            <!-- Form body -->
            <div class="login-body">
                <div class="welcome-heading">Selamat Datang 👋</div>
                <p class="welcome-sub">Silakan masuk ke akun Anda untuk melanjutkan</p>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                    @csrf

                    <div class="input-field">
                        <span class="input-icon-wrap"><i class="bi bi-person"></i></span>
                        <input type="text"
                               name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username') }}"
                               placeholder="Username"
                               required
                               autofocus>
                    </div>

                    <div class="input-field">
                        <span class="input-icon-wrap"><i class="bi bi-lock"></i></span>
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

                    <div class="login-options">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="btnLogin">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk</span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>&copy; {{ date('Y') }} {{ $loginTitle }}. All rights reserved.</p>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Add loading state to login button
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnLogin');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
});
</script>
</body>
</html>
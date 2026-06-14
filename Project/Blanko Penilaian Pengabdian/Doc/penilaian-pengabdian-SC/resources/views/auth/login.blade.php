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
            background: linear-gradient(135deg, #1e293b 0%, #1a56db 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #1a56db, #1e429f);
            color: #fff;
            padding: 2rem;
            text-align: center;
        }
        .login-header .logo-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
        }
        .login-header h4 { font-weight: 700; margin: 0; }
        .login-header p  { margin: .25rem 0 0; opacity: .85; font-size: .875rem; }
        .login-body { padding: 2rem; }

        .form-label { font-weight: 600; font-size: .875rem; color: #374151; }
        .form-control {
            border-radius: .5rem;
            border-color: #d1d5db;
            padding: .6rem .9rem;
        }
        .form-control:focus {
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26,86,219,.15);
        }
        .input-group-text {
            background: #f3f4f6;
            border-color: #d1d5db;
            border-radius: .5rem 0 0 .5rem !important;
            color: #6b7280;
        }
        .btn-login {
            background: linear-gradient(135deg, #1a56db, #1e429f);
            border: none;
            border-radius: .5rem;
            color: #fff;
            font-weight: 600;
            padding: .65rem;
            width: 100%;
            font-size: .95rem;
            transition: opacity .2s;
        }
        .btn-login:hover { opacity: .9; color: #fff; }
        .login-footer {
            text-align: center;
            padding: 1rem 2rem;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
            font-size: .78rem;
            color: #6b7280;
        }
        .role-hint {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: .5rem;
            padding: .75rem 1rem;
            font-size: .8rem;
            color: #1e40af;
            margin-bottom: 1.25rem;
        }
        .role-hint table { width: 100%; margin: 0; }
        .role-hint td { padding: .1rem .4rem; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <div class="logo-icon"><i class="bi bi-award-fill"></i></div>
        <h4>Penilaian Pengabdian</h4>
        <p>Sistem Manajemen Kinerja Karyawan</p>
    </div>

    <div class="login-body">
        @if(session('success'))
            <div class="alert alert-success alert-sm py-2">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-sm py-2">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
            </div>
        @endif

        {{-- Demo credentials hint --}}
        <div class="role-hint">
            <strong><i class="bi bi-info-circle me-1"></i>Demo Login:</strong>
            <table class="mt-1">
                <tr><td><strong>Admin:</strong></td><td>admin / admin123</td></tr>
                <tr><td><strong>User:</strong></td><td>user / user123</td></tr>
            </table>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text"
                           name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}"
                           placeholder="Masukkan username"
                           autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Masukkan password">
                    <button class="btn btn-outline-secondary" type="button"
                            onclick="togglePassword()" title="Tampilkan/Sembunyikan">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.875rem;">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>
    </div>

    <div class="login-footer">
        &copy; {{ date('Y') }} Penilaian Pengabdian &mdash; Sistem Manajemen Kinerja
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
</script>
</body>
</html>

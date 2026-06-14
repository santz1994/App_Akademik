<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun — Penilaian Pengabdian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }
        .activate-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            padding: 3rem 2.5rem 2rem;
        }
        .activate-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 160px;
            height: 80px;
            background: linear-gradient(135deg, #059669, #10b981);
            border-bottom-left-radius: 100%;
            z-index: 0;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }
        .logo-container i {
            font-size: 48px;
            color: #059669;
        }
        .logo-container h5 {
            font-weight: 700;
            color: #059669;
            margin: 0.5rem 0 0;
            font-size: 1.1rem;
        }
        .input-group-underline {
            margin-bottom: 1.2rem;
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
            border-color: #10b981;
        }
        .btn-activate {
            background-color: #10b981;
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
        .btn-activate:hover {
            background-color: #059669;
        }
        .info-box {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: #065f46;
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

<div class="activate-card">
    <div class="logo-container">
        <i class="bi bi-person-check"></i>
        <h5>Aktivasi Akun</h5>
    </div>

    <div class="info-box">
        <i class="bi bi-info-circle me-1"></i>
        Masukkan email yang terdaftar di data karyawan untuk membuat akun login.
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-sm py-2" style="font-size: 0.85rem; border-radius: 4px;">
            <i class="bi bi-check-circle me-1"></i>{!! session('success') !!}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-sm py-2" style="font-size: 0.85rem; border-radius: 4px;">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('activate.post') }}">
        @csrf

        <div class="input-group-underline">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="Email terdaftar di data karyawan"
                   required
                   autofocus>
        </div>

        <div class="input-group-underline">
            <i class="bi bi-lock input-icon"></i>
            <input type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Buat Password"
                   required>
        </div>

        <div class="input-group-underline">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Konfirmasi Password"
                   required>
        </div>

        <button type="submit" class="btn-activate">
            <i class="bi bi-check-circle me-1"></i>Aktivasi Akun
        </button>
    </form>

    <div class="text-center mt-3" style="position: relative; z-index: 1;">
        <a href="{{ route('login') }}" class="text-decoration-none" style="color: #059669; font-size: 0.9rem;">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
        </a>
    </div>
</div>

</body>
</html>

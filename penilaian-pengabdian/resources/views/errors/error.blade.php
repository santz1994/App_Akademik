<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $code ?? 500 }} - Terjadi Kesalahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-card { max-width: 520px; text-align: center; }
        .error-code { font-size: 6rem; font-weight: 800; color: #1e293b; line-height: 1; }
        .error-icon { font-size: 4rem; color: #ef4444; }
        .error-detail { font-size: .8rem; color: #94a3b8; max-height: 120px; overflow-y: auto; text-align: left; background: #f8fafc; border-radius: 8px; padding: .75rem; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="card shadow-sm border-0 p-4">
            <div class="error-code">{{ $code ?? 500 }}</div>
            <div class="error-icon my-3">
                @if(($code ?? 500) === 403)
                    <i class="bi bi-shield-exclamation"></i>
                @elseif(($code ?? 500) === 419)
                    <i class="bi bi-clock-history"></i>
                @elseif(($code ?? 500) === 429)
                    <i class="bi bi-hourglass-split"></i>
                @elseif(($code ?? 500) === 503)
                    <i class="bi bi-tools"></i>
                @else
                    <i class="bi bi-exclamation-triangle"></i>
                @endif
            </div>
            <h4 class="fw-bold text-dark mb-2">
                @if(($code ?? 500) === 403)
                    Akses Ditolak
                @elseif(($code ?? 500) === 419)
                    Sesi Telah Berakhir
                @elseif(($code ?? 500) === 429)
                    Terlalu Banyak Permintaan
                @elseif(($code ?? 500) === 503)
                    Sedang Dalam Perawatan
                @else
                    Terjadi Kesalahan
                @endif
            </h4>
            <p class="text-muted mb-3">
                @if(($code ?? 500) === 403)
                    Anda tidak memiliki izin untuk mengakses halaman ini.
                @elseif(($code ?? 500) === 419)
                    Halaman telah kedaluwarsa karena tidak aktif terlalu lama. Silakan login kembali.
                @elseif(($code ?? 500) === 429)
                    Anda telah mengirim terlalu banyak permintaan. Silakan tunggu beberapa saat dan coba lagi.
                @elseif(($code ?? 500) === 503)
                    Aplikasi sedang dalam perawatan. Silakan coba beberapa saat lagi.
                @else
                    {{ $message ?? 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi atau hubungi administrator.' }}
                @endif
            </p>

            @if(isset($detail) && $detail)
                <div class="error-detail mb-3">
                    <strong>Detail:</strong><br>
                    {{ $detail }}
                </div>
            @endif

            <div class="d-flex gap-2 justify-content-center flex-wrap">
                @if(($code ?? 500) === 419)
                    <a href="{{ url('/login') }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login Kembali
                    </a>
                @else
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="bi bi-house me-1"></i>Kembali ke Beranda
                    </a>
                @endif
                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Halaman Sebelumnya
                </a>
            </div>
        </div>
    </div>
</body>
</html>

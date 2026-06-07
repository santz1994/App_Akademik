<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Telah Berakhir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-card { max-width: 480px; text-align: center; }
        .error-code { font-size: 6rem; font-weight: 800; color: #1e293b; line-height: 1; }
        .error-icon { font-size: 4rem; color: #f59e0b; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="card shadow-sm border-0 p-4">
            <div class="error-code">419</div>
            <div class="error-icon my-3"><i class="bi bi-clock-history"></i></div>
            <h4 class="fw-bold text-dark mb-2">Sesi Telah Berakhir</h4>
            <p class="text-muted mb-4">Halaman telah kedaluwarsa karena tidak aktif terlalu lama. Ini biasanya terjadi ketika Anda menunggu terlama sebelum melakukan aksi. Silakan login kembali.</p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ url('/login') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Login Kembali
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Halaman Sebelumnya
                </a>
            </div>
        </div>
    </div>
</body>
</html>

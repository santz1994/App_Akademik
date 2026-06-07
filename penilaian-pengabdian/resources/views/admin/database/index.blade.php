@extends('layouts.app')
@section('title', 'Pengaturan Database')
@section('page-title', 'Pengaturan Database')
@section('content')

@php
    $dbConfig = config('database.connections.mysql');
    $dbName = $dbConfig['database'];
    try {
        $tables = \Illuminate\Support\Facades\DB::select("SHOW TABLES");
        $tableKey = 'Tables_in_' . $dbName;
        $totalRows = 0;
        $tableList = [];
        foreach ($tables as $t) {
            $name = $t->$tableKey;
            $count = \Illuminate\Support\Facades\DB::table($name)->count();
            $totalRows += $count;
            $tableList[] = ['name' => $name, 'count' => $count];
        }
        $totalTables = count($tableList);
    } catch (\Throwable $e) {
        $totalTables = 0;
        $totalRows = 0;
        $tableList = [];
    }
@endphp

{{-- Info Database --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <i class="bi bi-database fs-1 text-primary mb-2"></i>
            <div class="fw-bold fs-5">{{ $dbName }}</div>
            <small class="text-muted">Nama Database</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <i class="bi bi-table fs-1 text-success mb-2"></i>
            <div class="fw-bold fs-5">{{ $totalTables }}</div>
            <small class="text-muted">Total Tabel</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <i class="bi bi-list-ul fs-1 text-info mb-2"></i>
            <div class="fw-bold fs-5">{{ number_format($totalRows) }}</div>
            <small class="text-muted">Total Baris Data</small>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Backup Section --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header py-2 fw-semibold">
                <i class="bi bi-download me-2"></i>Backup Database
            </div>
            <div class="card-body">
                <p class="text-muted" style="font-size:.875rem;">
                    Backup akan menyimpan <strong>SEMUA data</strong> yang sudah diinput ({{ number_format($totalRows) }} baris dari {{ $totalTables }} tabel) ke file SQL.
                    File dapat di-download atau disimpan di server.
                </p>
                <form method="POST" action="{{ route('admin.database.backup') }}" id="backupForm">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="backupBtn">
                        <i class="bi bi-download me-1"></i>Backup & Simpan di Server
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Restore Section --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header py-2 fw-semibold">
                <i class="bi bi-upload me-2"></i>Restore Database
            </div>
            <div class="card-body">
                <p class="text-muted" style="font-size:.875rem;">
                    <strong class="text-danger">⚠️ PERHATIAN:</strong> Restore akan <strong>mengganti seluruh data</strong> database dengan data dari file backup. Data yang ada saat ini akan <strong>hilang</strong>. Proses ini tidak dapat dibatalkan!
                </p>
                <form method="POST" action="{{ route('admin.database.restore') }}" enctype="multipart/form-data" id="restoreForm">
                    @csrf
                    <div class="mb-3">
                        <label for="backup_file" class="form-label">Pilih File Backup (.sql)</label>
                        <input type="file" class="form-control @error('backup_file') is-invalid @enderror"
                               id="backup_file" name="backup_file" accept=".sql" required>
                        @error('backup_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('⚠️ PERINGATAN: Restore akan mengganti SELURUH data database!\n\nData saat ini ({{ number_format($totalRows) }} baris) akan HILANG.\n\nApakah Anda yakin ingin melanjutkan?')">
                        <i class="bi bi-upload me-1"></i>Restore Database
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Detail Tabel --}}
<div class="card mt-4">
    <div class="card-header py-2 fw-semibold">
        <i class="bi bi-table me-2"></i>Detail Tabel Database
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height:300px; overflow-y:auto;">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light" style="position:sticky; top:0;">
                    <tr>
                        <th>No</th>
                        <th>Nama Tabel</th>
                        <th class="text-end">Jumlah Baris</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tableList as $idx => $tbl)
                    <tr>
                        <td class="text-muted">{{ $idx + 1 }}</td>
                        <td><code>{{ $tbl['name'] }}</code></td>
                        <td class="text-end fw-semibold">{{ number_format($tbl['count']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2" class="text-end">Total</th>
                        <th class="text-end">{{ number_format($totalRows) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

{{-- Backup Files List --}}
<div class="card mt-4">
    <div class="card-header py-2 fw-semibold">
        <i class="bi bi-folder2-open me-2"></i>File Backup Tersedia
    </div>
    <div class="card-body">
        @if(empty($files))
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                Belum ada file backup. Klik "Backup & Simpan di Server" untuk membuat backup pertama.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td><i class="bi bi-file-earmark-code me-1 text-primary"></i>{{ $file['name'] }}</td>
                                <td>{{ number_format($file['size'] / 1024, 1) }} KB</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($file['modified'])->format('d/m/Y H:i:s') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.database.download', $file['name']) }}"
                                       class="btn btn-sm btn-outline-primary" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.database.destroy', $file['name']) }}"
                                          class="d-inline" onsubmit="return confirm('Yakin ingin menghapus file backup ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('backupForm').addEventListener('submit', function() {
    var btn = document.getElementById('backupBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sedang backup...';
});
document.getElementById('restoreForm').addEventListener('submit', function() {
    var btn = this.querySelector('button[type=submit]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Sedang restore...';
});
</script>
@endpush

@endsection

@extends('layouts.app')
@section('title','Tambah Karyawan')
@section('page-title','Data Karyawan › Add Data')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-person-plus me-2"></i>Form Tambah Karyawan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.karyawan.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Karyawan</label>
                <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
                <small class="text-muted">Kode otomatis digenerate oleh sistem</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">User Account <span class="text-danger">*</span></label>
                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                    <option value="">— Tidak Terhubung —</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->username }}) — {{ ucfirst($u->role) }}
                    </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Hubungkan karyawan ini ke akun login. Setiap akun hanya bisa dipasangkan ke 1 karyawan.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Karyawan <span class="text-danger">*</span></label>
                <input type="text" name="nama_karyawan" class="form-control @error('nama_karyawan') is-invalid @enderror"
                       value="{{ old('nama_karyawan') }}" placeholder="Nama lengkap karyawan">
                @error('nama_karyawan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                          rows="3" placeholder="Alamat karyawan">{{ old('alamat') }}</textarea>
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pangkalan Job</label>
                <select name="pangkalan_id" class="form-select @error('pangkalan_id') is-invalid @enderror">
                    <option value="">— Pilih Pangkalan —</option>
                    @foreach($pangkalan as $p)
                    <option value="{{ $p->id }}" {{ old('pangkalan_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->kode_pangkalan }} — {{ $p->nama_pangkalan }}
                    </option>
                    @endforeach
                </select>
                @error('pangkalan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Tugas Khusus</label>
                <input type="text" name="tugas_khusus" class="form-control @error('tugas_khusus') is-invalid @enderror"
                       value="{{ old('tugas_khusus') }}" placeholder="mis. TU MI, Kemasjidan">
                @error('tugas_khusus')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Tahun Penilaian Aktif</label>
                <input type="text" class="form-control bg-light"
                       value="{{ $tahunAktif ? $tahunAktif->periode_penilaian . ' — ' . $tahunAktif->keterangan : 'Tidak ada tahun aktif' }}" readonly>
                <input type="hidden" name="tahun_penilaian_id" value="{{ $tahunAktif?->id }}">
                <small class="text-muted"><i class="bi bi-info-circle"></i> Karyawan otomatis terdaftar pada tahun penilaian yang sedang aktif.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

@extends('layouts.app')
@section('title','Edit Karyawan')
@section('page-title','Data Karyawan › Edit')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-pencil me-2"></i>Form Edit Karyawan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.karyawan.update', $karyawan) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Karyawan</label>
                <input type="text" class="form-control bg-light" value="{{ $karyawan->kode_karyawan }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Karyawan <span class="text-danger">*</span></label>
                <input type="text" name="nama_karyawan" class="form-control @error('nama_karyawan') is-invalid @enderror"
                       value="{{ old('nama_karyawan', $karyawan->nama_karyawan) }}">
                @error('nama_karyawan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pangkalan Job</label>
                <select name="pangkalan_id" class="form-select @error('pangkalan_id') is-invalid @enderror">
                    <option value="">— Pilih Pangkalan —</option>
                    @foreach($pangkalan as $p)
                    <option value="{{ $p->id }}"
                        {{ old('pangkalan_id', $karyawan->pangkalan_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->kode_pangkalan }} — {{ $p->nama_pangkalan }}
                    </option>
                    @endforeach
                </select>
                @error('pangkalan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tugas Khusus</label>
                <input type="text" name="tugas_khusus" class="form-control"
                       value="{{ old('tugas_khusus', $karyawan->tugas_khusus) }}" placeholder="mis. TU MI, Kemasjidan">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">User Account</label>
                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                    <option value="">— Tidak Terhubung —</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}"
                        {{ old('user_id', $karyawan->user_id) == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->username }}) — {{ ucfirst($u->role) }}
                    </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Setiap akun hanya bisa dipasangkan ke 1 karyawan.</small>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Tahun Penilaian Aktif</label>
                <input type="text" class="form-control bg-light"
                       value="{{ $karyawan->tahunPenilaian ? $karyawan->tahunPenilaian->periode_penilaian . ' — ' . $karyawan->tahunPenilaian->keterangan : 'Tidak ada' }}" readonly>
                <input type="hidden" name="tahun_penilaian_id" value="{{ $karyawan->tahun_penilaian_id }}">
                <small class="text-muted"><i class="bi bi-info-circle"></i> Tahun penilaian mengikuti periode yang sedang aktif.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

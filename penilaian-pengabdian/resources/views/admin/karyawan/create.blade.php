@extends('layouts.app')
@section('title','Tambah Karyawan')
@section('page-title','Data Karyawan › Add Data')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
    <div class="card-header py-2 fw-semibold"><i class="bi bi-person-plus me-2"></i>Form Tambah Karyawan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.karyawan.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Karyawan</label>
                <input type="text" class="form-control bg-light" value="{{ $kode }}" readonly>
                <small class="text-muted">Kode otomatis digenerate oleh sistem</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">User Account <span class="text-danger">*</span></label>
                <select id="userSelect" name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                    <option value="">— Tidak Terhubung —</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}"
                            data-name="{{ $u->name }}"
                            data-email="{{ $u->email }}"
                            data-pangkalan-id="{{ $u->pangkalan_id ?? '' }}"
                            data-pangkalan-label="{{ $u->pangkalan ? $u->pangkalan->kode_pangkalan . ' — ' . $u->pangkalan->nama_pangkalan : '' }}"
                            {{ old('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} ({{ $u->username }}) — {{ ucfirst($u->role) }}
                    </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Hubungkan karyawan ini ke akun login. Setiap akun hanya bisa dipasangkan ke 1 karyawan.</small>
            </div>
            <div class="row g-3 mb-3" id="userAccountPreview" style="display:none;">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama User</label>
                    <input type="text" id="userNamePreview" class="form-control bg-light" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email User</label>
                    <input type="text" id="userEmailPreview" class="form-control bg-light" readonly>
                </div>
                <div class="col-12">
                    <small class="text-muted" id="userPangkalanPreview"></small>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Karyawan <span class="text-danger">*</span></label>
                <input type="text" id="namaKaryawanInput" name="nama_karyawan" class="form-control @error('nama_karyawan') is-invalid @enderror"
                       value="{{ old('nama_karyawan') }}" placeholder="Nama lengkap karyawan">
                @error('nama_karyawan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor Induk</label>
                    <input type="text" name="nomor_induk" class="form-control @error('nomor_induk') is-invalid @enderror"
                           value="{{ old('nomor_induk') }}" placeholder="Contoh: NIK/NI-001">
                    @error('nomor_induk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                        <option value="">— Pilih Jenis Kelamin —</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor Surat Tugas</label>
                    <input type="text" name="nomor_surat_tugas" class="form-control @error('nomor_surat_tugas') is-invalid @enderror"
                           value="{{ old('nomor_surat_tugas') }}" placeholder="Contoh: 001/ST/2026">
                    @error('nomor_surat_tugas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Surat Tugas</label>
                    <input type="date" name="tanggal_surat_tugas" class="form-control @error('tanggal_surat_tugas') is-invalid @enderror"
                           value="{{ old('tanggal_surat_tugas') }}">
                    @error('tanggal_surat_tugas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Status Karyawan <span class="text-danger">*</span></label>
                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                    <option value="1" {{ (string)old('is_active', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ (string)old('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('is_active')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                          rows="3" placeholder="Alamat karyawan">{{ old('alamat') }}</textarea>
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Foto 3x4 (JPEG/PNG, max 200KB)</label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png">
                @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pangkalan Job</label>
                <select id="pangkalanSelect" name="pangkalan_id" class="form-select @error('pangkalan_id') is-invalid @enderror">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userSelect = document.getElementById('userSelect');
        const namaInput = document.getElementById('namaKaryawanInput');
        const pangkalanSelect = document.getElementById('pangkalanSelect');
        const previewWrap = document.getElementById('userAccountPreview');
        const userNamePreview = document.getElementById('userNamePreview');
        const userEmailPreview = document.getElementById('userEmailPreview');
        const userPangkalanPreview = document.getElementById('userPangkalanPreview');

        if (!userSelect) {
            return;
        }

        const applyUserAccountData = () => {
            const selected = userSelect.options[userSelect.selectedIndex];
            const userId = userSelect.value;

            if (!userId || !selected) {
                previewWrap.style.display = 'none';
                userNamePreview.value = '';
                userEmailPreview.value = '';
                userPangkalanPreview.textContent = '';
                return;
            }

            const userName = selected.getAttribute('data-name') || '';
            const userEmail = selected.getAttribute('data-email') || '';
            const pangkalanId = selected.getAttribute('data-pangkalan-id') || '';
            const pangkalanLabel = selected.getAttribute('data-pangkalan-label') || '';

            previewWrap.style.display = 'flex';
            userNamePreview.value = userName;
            userEmailPreview.value = userEmail;
            userPangkalanPreview.textContent = pangkalanLabel !== ''
                ? 'Pangkalan user account: ' + pangkalanLabel
                : 'User account belum memiliki pangkalan.';

            if (namaInput) {
                namaInput.value = userName;
            }

            if (pangkalanSelect && pangkalanId !== '') {
                pangkalanSelect.value = pangkalanId;
            }
        };

        userSelect.addEventListener('change', applyUserAccountData);
        applyUserAccountData();
    });
</script>
@endpush
@endsection

@extends('layouts.app')
@section('title', 'Edit Reward/Punishment')
@section('page-title', 'Edit Reward & Punishment')
@section('content')

<div class="card">
    <div class="card-header py-2 fw-semibold">
        <i class="bi bi-pencil me-2"></i>Edit Data Reward/Punishment
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.reward-punishment.update', $rewardPunishment) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode') is-invalid @enderror"
                           id="kode" name="kode" value="{{ old('kode', $rewardPunishment->kode) }}" required>
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                        <option value="reward" {{ old('tipe', $rewardPunishment->tipe) === 'reward' ? 'selected' : '' }}>Reward</option>
                        <option value="punishment" {{ old('tipe', $rewardPunishment->tipe) === 'punishment' ? 'selected' : '' }}>Punishment</option>
                    </select>
                    @error('tipe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                    <select class="form-select @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $g)
                            <option value="{{ $g }}" {{ old('grade', $rewardPunishment->grade) === $g ? 'selected' : '' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                    @error('grade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                           id="nama" name="nama" value="{{ old('nama', $rewardPunishment->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                              id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $rewardPunishment->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('jumlah') is-invalid @enderror"
                           id="jumlah" name="jumlah" value="{{ old('jumlah', $rewardPunishment->jumlah) }}" min="0" required>
                    @error('jumlah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control @error('satuan') is-invalid @enderror"
                           id="satuan" name="satuan" value="{{ old('satuan', $rewardPunishment->satuan) }}">
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', $rewardPunishment->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $rewardPunishment->is_active) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Perbarui
                </button>
                <a href="{{ route('admin.reward-punishment.index') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

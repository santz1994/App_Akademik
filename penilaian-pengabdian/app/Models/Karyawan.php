<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pangkalan;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'kode_karyawan',
        'nama_karyawan',
        'nomor_induk',
        'jenis_kelamin',
        'nomor_surat_tugas',
        'tanggal_surat_tugas',
        'is_active',
        'alamat',
        'foto_path',
        'tugas_khusus',
        'tahun_penilaian_id',
        'pangkalan_id',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_surat_tugas' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pangkalan()
    {
        return $this->belongsTo(Pangkalan::class);
    }

    public function tahunPenilaian()
    {
        return $this->belongsTo(TahunPenilaian::class);
    }

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function penilaianLocks()
    {
        return $this->hasMany(PenilaianLock::class);
    }

    public function penilaianUnlockRequests()
    {
        return $this->hasMany(PenilaianUnlockRequest::class);
    }

    public function scopeBukanKepala($query)
    {
        return $query->where(function ($sub) {
            $sub->whereDoesntHave('user')
                ->orWhereHas('user', fn($uq) => $uq->where('is_kepala', false));
        });
    }
}

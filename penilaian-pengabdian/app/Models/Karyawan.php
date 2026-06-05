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
        'email',
        'no_hp',
        'kontak_darurat',
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

    /**
     * Pangkalan utama karyawan (derived dari pivot table, untuk backward compatibility).
     */
    public function pangkalan()
    {
        return $this->belongsTo(Pangkalan::class);
    }

    /**
     * Semua pangkalan tempat karyawan bekerja (many-to-many via pivot table).
     * Ini adalah sumber kebenaran utama untuk relasi pangkalan.
     */
    public function pangkalans()
    {
        return $this->belongsToMany(Pangkalan::class, 'karyawan_pangkalan', 'karyawan_id', 'pangkalan_id')->withTimestamps();
    }

    /**
     * Alias untuk backward compatibility.
     */
    public function pangkalanLain()
    {
        return $this->pangkalans();
    }

    /**
     * Mendapatkan semua ID pangkalan tempat karyawan bekerja dari pivot table.
     */
    public function getAllPangkalanIds(): array
    {
        return $this->pangkalans()->pluck('pangkalan_id')->map(fn($id) => (int) $id)->toArray();
    }

    /**
     * Sync pangkalan dan update pangkalan_id (derived field) dari pivot table.
     */
    public function syncPangkalan(array $pangkalanIds): void
    {
        $this->pangkalans()->sync($pangkalanIds);
        // Update derived pangkalan_id = first selected (or null)
        $this->update(['pangkalan_id' => !empty($pangkalanIds) ? (int) $pangkalanIds[0] : null]);
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

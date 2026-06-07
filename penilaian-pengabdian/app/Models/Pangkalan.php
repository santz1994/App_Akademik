<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pangkalan extends Model
{
    protected $table = 'pangkalan';

    protected $fillable = ['kode_pangkalan', 'nama_pangkalan', 'pimpinan_pos', 'keterangan', 'is_active', 'kepala_user_id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * User yang menjadi kepala pangkalan ini.
     */
    public function kepalaUser()
    {
        return $this->belongsTo(User::class, 'kepala_user_id');
    }

    /**
     * Semua karyawan yang bekerja di pangkalan ini (via pivot table).
     */
    public function karyawanPivot()
    {
        return $this->belongsToMany(Karyawan::class, 'karyawan_pangkalan', 'pangkalan_id', 'karyawan_id')->withTimestamps();
    }

    public function kategoriKinerja()
    {
        return $this->belongsToMany(
            KategoriKinerja::class,
            'pangkalan_kategori_kinerja',
            'pangkalan_id',
            'kategori_kinerja_id'
        )
        ->withPivot('penanggung_jawab_user_id')
        ->withTimestamps()
        ->orderBy('jenis')
        ->orderBy('kode_kategori');
    }

    /**
     * Kategori kinerja yang dipilih (bukan kegiatan wajib global).
     */
    public function kategoriKinerjaDipilih()
    {
        return $this->belongsToMany(
            KategoriKinerja::class,
            'pangkalan_kategori_kinerja',
            'pangkalan_id',
            'kategori_kinerja_id'
        )
        ->withPivot('penanggung_jawab_user_id')
        ->withTimestamps()
        ->orderBy('jenis')
        ->orderBy('kode_kategori');
    }

    /**
     * User yang menjadi penanggung jawab untuk kategori tertentu di pangkalan ini.
     */
    public function penanggungJawabKategori(KategoriKinerja $kategori): ?User
    {
        $pivot = $this->kategoriKinerja()
            ->where('kategori_kinerja_id', $kategori->id)
            ->first()?->pivot;

        if ($pivot && $pivot->penanggung_jawab_user_id) {
            return User::find($pivot->penanggung_jawab_user_id);
        }

        return $this->kepalaUser;
    }
}

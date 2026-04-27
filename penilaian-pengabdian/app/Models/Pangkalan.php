<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pangkalan extends Model
{
    protected $table = 'pangkalan';

    protected $fillable = ['kode_pangkalan', 'nama_pangkalan', 'pimpinan_pos', 'keterangan'];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function kategoriKinerja()
    {
        return $this->belongsToMany(
            KategoriKinerja::class,
            'pangkalan_kategori_kinerja',
            'pangkalan_id',
            'kategori_kinerja_id'
        )
        ->withTimestamps()
        ->orderBy('kode_kategori');
    }
}

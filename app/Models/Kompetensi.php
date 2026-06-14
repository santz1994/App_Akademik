<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    protected $table = 'kompetensi';

    protected $fillable = ['kode_kompetensi', 'kategori_kinerja_id', 'kompetensi'];

    public function kategoriKinerja()
    {
        return $this->belongsToMany(KategoriKinerja::class, 'kategori_kinerja_kompetensi', 'kompetensi_id', 'kategori_kinerja_id')
            ->withTimestamps()
            ->orderBy('kode_kategori');
    }
}

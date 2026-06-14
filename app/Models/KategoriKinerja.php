<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKinerja extends Model
{
    protected $table = 'kategori_kinerja';

    protected $fillable = ['kode_kategori', 'kategori', 'jenis', 'is_wajib', 'bobot'];

    protected $casts = [
        'is_wajib' => 'boolean',
        'bobot' => 'float',
    ];

    public function kompetensi()
    {
        return $this->belongsToMany(Kompetensi::class, 'kategori_kinerja_kompetensi', 'kategori_kinerja_id', 'kompetensi_id')
            ->withTimestamps()
            ->orderBy('kode_kompetensi');
    }

    public function pangkalan()
    {
        return $this->belongsToMany(
            Pangkalan::class,
            'pangkalan_kategori_kinerja',
            'kategori_kinerja_id',
            'pangkalan_id'
        )->withTimestamps();
    }
}

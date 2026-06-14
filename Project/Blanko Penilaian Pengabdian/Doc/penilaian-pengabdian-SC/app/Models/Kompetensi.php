<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    protected $table = 'kompetensi';

    protected $fillable = ['kode_kompetensi', 'kategori_kinerja_id', 'kompetensi'];

    public function kategoriKinerja()
    {
        return $this->belongsTo(KategoriKinerja::class);
    }
}

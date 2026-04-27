<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKinerja extends Model
{
    protected $table = 'kategori_kinerja';

    protected $fillable = ['kode_kategori', 'kategori', 'bobot'];

    public function kompetensi()
    {
        return $this->hasMany(Kompetensi::class);
    }
}

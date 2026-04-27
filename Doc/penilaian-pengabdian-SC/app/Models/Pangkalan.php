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
}

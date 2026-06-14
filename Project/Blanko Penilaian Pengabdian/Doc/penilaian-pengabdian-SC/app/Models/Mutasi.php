<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';

    protected $fillable = [
        'kode_mutasi',
        'karyawan_id',
        'tahun_penilaian_id',
        'jenis_mutasi',
        'keterangan',
        'tanggal_mutasi',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function tahunPenilaian()
    {
        return $this->belongsTo(TahunPenilaian::class);
    }
}

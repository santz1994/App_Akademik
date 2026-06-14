<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'karyawan_id',
        'pangkalan_id',
        'tahun_penilaian_id',
        'kompetensi_id',
        'kategori_kinerja_id',
        'performance_rating_id',
        'nilai',
        'keterangan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pangkalan()
    {
        return $this->belongsTo(Pangkalan::class);
    }

    public function tahunPenilaian()
    {
        return $this->belongsTo(TahunPenilaian::class);
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class);
    }

    public function performanceRating()
    {
        return $this->belongsTo(PerformanceRating::class);
    }
}

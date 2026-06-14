<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pangkalan;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = ['kode_karyawan', 'nama_karyawan', 'alamat', 'tugas_khusus', 'tahun_penilaian_id', 'pangkalan_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pangkalan()
    {
        return $this->belongsTo(Pangkalan::class);
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunPenilaian extends Model
{
    protected $table = 'tahun_penilaian';

    protected $fillable = ['periode_penilaian', 'keterangan', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function settingLembaga()
    {
        return $this->hasOne(SettingLembaga::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianUnlockRequest extends Model
{
    protected $table = 'penilaian_unlock_requests';

    protected $fillable = [
        'karyawan_id',
        'tahun_penilaian_id',
        'requested_by_user_id',
        'alasan',
        'status',
        'reviewed_by_user_id',
        'reviewed_at',
        'catatan_admin',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function tahunPenilaian()
    {
        return $this->belongsTo(TahunPenilaian::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}

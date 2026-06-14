<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianLock extends Model
{
    protected $table = 'penilaian_locks';

    protected $fillable = [
        'karyawan_id',
        'tahun_penilaian_id',
        'is_final_submitted',
        'is_locked',
        'submitted_by_user_id',
        'submitted_at',
        'locked_by_user_id',
        'locked_at',
        'unlocked_by_user_id',
        'unlocked_at',
    ];

    protected $casts = [
        'is_final_submitted' => 'boolean',
        'is_locked' => 'boolean',
        'submitted_at' => 'datetime',
        'locked_at' => 'datetime',
        'unlocked_at' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function tahunPenilaian()
    {
        return $this->belongsTo(TahunPenilaian::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by_user_id');
    }

    public function unlockedBy()
    {
        return $this->belongsTo(User::class, 'unlocked_by_user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardPunishment extends Model
{
    protected $table = 'reward_punishment';

    protected $fillable = [
        'kode',
        'tipe',
        'grade',
        'nama',
        'deskripsi',
        'satuan',
        'jumlah',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jumlah' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePunishment($query)
    {
        return $query->where('tipe', 'punishment');
    }

    public function scopeReward($query)
    {
        return $query->where('tipe', 'reward');
    }

    public function scopeForGrade($query, string $grade)
    {
        return $query->where('grade', $grade);
    }

    /**
     * Get punishment/reward info for a given score
     */
    public static function getInfoForScore(?float $nilai): ?array
    {
        if ($nilai === null) {
            return null;
        }

        if ($nilai >= 90) {
            $grade = 'A';
        } elseif ($nilai >= 80) {
            $grade = 'B';
        } elseif ($nilai >= 70) {
            $grade = 'C';
        } elseif ($nilai >= 60) {
            $grade = 'D';
        } else {
            $grade = 'E';
        }

        $items = static::active()
            ->forGrade($grade)
            ->get();

        if ($items->isEmpty()) {
            return null;
        }

        return [
            'grade' => $grade,
            'items' => $items,
        ];
    }
}

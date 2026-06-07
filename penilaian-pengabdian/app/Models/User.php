<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Karyawan;
use App\Models\Pangkalan;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'pangkalan_id',
        'is_kepala',
    ];

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }

    public function pangkalan()
    {
        return $this->belongsTo(Pangkalan::class);
    }

    /**
     * Pangkalan yang dipimpin oleh kepala (many-to-many).
     */
    public function kepalaPangkalan()
    {
        return $this->belongsToMany(Pangkalan::class, 'kepala_pangkalan', 'user_id', 'pangkalan_id')->withTimestamps();
    }

    /**
     * Mendapatkan semua pangkalan yang bisa diakses kepala.
     * Termasuk pangkalan utama (pangkalan_id) dan pangkalan tambahan.
     */
    public function getAllPangkalanIds(): array
    {
        $ids = $this->kepalaPangkalan()->pluck('pangkalan_id')->map(fn($id) => (int) $id)->toArray();
        if ($this->pangkalan_id && !in_array((int) $this->pangkalan_id, $ids, true)) {
            $ids[] = (int) $this->pangkalan_id;
        }
        return array_unique($ids);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_kepala' => 'boolean',
        ];
    }

    public function canAccessPenilaianArea(): bool
    {
        return $this->role === 'admin' || $this->is_kepala;
    }

    /**
     * Check if user is tata usaha role
     */
    public function isTataUsaha(): bool
    {
        return $this->role === 'tata_usaha';
    }

    /**
     * Get the display label for the user's role
     */
    public function getRoleLabelAttribute(): string
    {
        if ($this->is_kepala) {
            return 'Kepala Pimpinan Pos';
        }

        return match ($this->role) {
            'admin' => 'Admin',
            'tata_usaha' => 'Tata Usaha',
            default => 'User',
        };
    }
}

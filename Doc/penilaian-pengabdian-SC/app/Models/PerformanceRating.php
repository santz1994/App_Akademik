<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceRating extends Model
{
    protected $table = 'performance_rating';

    protected $fillable = ['kode_rating', 'rating', 'keterangan'];
}

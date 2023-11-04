<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tps extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'nomor_tps'];

    public function pemilih(): HasMany
    {
        return $this->hasMany(Pemilih::class);
    }
}

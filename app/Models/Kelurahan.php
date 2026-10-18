<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelurahan extends Model
{
    use HasFactory;
    protected $fillable = [
        'kecamatan_id',
        'code',
        'name',
        'full_code',
        'pos_code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Get timezone attribute inherited from parent hierarchy.
     */
    public function getTimezoneAttribute(): string
    {
        return $this->kecamatan ? $this->kecamatan->timezone : 'WIB';
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function pemilih(): HasMany
    {
        return $this->hasMany(Pemilih::class);
    }
}

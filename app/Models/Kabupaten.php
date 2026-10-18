<?php

/**
 * API Wilayah Indonesia 2026 - Kemendagri Standard
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kabupaten extends Model
{
    use HasFactory;
    protected $fillable = [
        'provinsi_id',
        'type',
        'name',
        'code',
        'full_code',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected $appends = [
        'timezone',
    ];

    /**
     * Get timezone attribute inherited from parent province.
     */
    public function getTimezoneAttribute(): string
    {
        return $this->provinsi ? $this->provinsi->timezone : 'WIB';
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kecamatan(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }
}

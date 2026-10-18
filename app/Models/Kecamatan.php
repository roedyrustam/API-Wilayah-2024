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

class Kecamatan extends Model
{
    use HasFactory;
    protected $fillable = [
        'kabupaten_id',
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

    /**
     * Get timezone attribute inherited from parent kabupaten/provinsi.
     */
    public function getTimezoneAttribute(): string
    {
        return $this->kabupaten ? $this->kabupaten->timezone : 'WIB';
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kelurahan(): HasMany
    {
        return $this->hasMany(Kelurahan::class);
    }
}

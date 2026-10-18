<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provinsi extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'latitude',
        'longitude',
        'timezone',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected $appends = [
        'timezone',
    ];

    /**
     * Get timezone attribute with automatic fallback by province code.
     */
    public function getTimezoneAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        $code = (int) $this->code;
        if (in_array($code, [51, 52, 53, 63, 64, 65, 71, 72, 73, 74, 75, 76], true)) {
            return 'WITA';
        }
        if (in_array($code, [81, 82, 91, 92, 93, 94, 95, 96], true)) {
            return 'WIT';
        }

        return 'WIB';
    }

    public function kabupaten(): HasMany
    {
        return $this->hasMany(Kabupaten::class);
    }

    public function kecamatan()
    {
        return $this->hasManyThrough(Kecamatan::class, Kabupaten::class);
    }
}

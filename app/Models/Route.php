<?php

namespace App\Models;

use Database\Factories\RouteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $origin_town_id
 * @property int $destination_town_id
 * @property int $base_price
 * @property int|null $duration_minutes
 * @property bool $is_active
 */
#[Fillable(['origin_town_id', 'destination_town_id', 'base_price', 'duration_minutes', 'is_active'])]
class Route extends Model
{
    /** @use HasFactory<RouteFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'base_price' => 'integer',
            'duration_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Town, $this> */
    public function origin(): BelongsTo
    {
        return $this->belongsTo(Town::class, 'origin_town_id');
    }

    /** @return BelongsTo<Town, $this> */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Town::class, 'destination_town_id');
    }

    /** @return HasMany<Schedule, $this> */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}

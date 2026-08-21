<?php

namespace App\Models;

use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $route_id
 * @property int $bus_id
 * @property string $departure_time
 * @property int|null $price
 * @property bool $is_active
 */
#[Fillable(['route_id', 'bus_id', 'departure_time', 'price', 'is_active'])]
class Schedule extends Model
{
    /** @use HasFactory<ScheduleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /** @return BelongsTo<Route, $this> */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /** @return BelongsTo<Bus, $this> */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /** @return HasMany<Departure, $this> */
    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }
}

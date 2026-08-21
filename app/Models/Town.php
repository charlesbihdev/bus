<?php

namespace App\Models;

use Database\Factories\TownFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $region
 * @property bool $is_active
 */
#[Fillable(['name', 'region', 'is_active'])]
class Town extends Model
{
    /** @use HasFactory<TownFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /** @return HasMany<Route, $this> */
    public function departingRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'origin_town_id');
    }

    /** @return HasMany<Route, $this> */
    public function arrivingRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'destination_town_id');
    }
}

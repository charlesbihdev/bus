<?php

namespace App\Models;

use Database\Factories\BusFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $operator
 * @property int $seat_count
 * @property array<int, array<int, string|null>> $layout
 * @property bool $is_active
 */
#[Fillable(['name', 'operator', 'seat_count', 'layout', 'is_active'])]
class Bus extends Model
{
    /** @use HasFactory<BusFactory> */
    use HasFactory;

    protected $table = 'buses';

    protected function casts(): array
    {
        return [
            'seat_count' => 'integer',
            'layout' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<Schedule, $this> */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}

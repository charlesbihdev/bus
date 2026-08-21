<?php

namespace App\Models;

use Database\Factories\DepartureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $schedule_id
 * @property Carbon $travel_date
 * @property string $status
 * @property int|null $price
 */
#[Fillable(['schedule_id', 'travel_date', 'status', 'price'])]
class Departure extends Model
{
    /** @use HasFactory<DepartureFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'travel_date' => 'date',
            'price' => 'integer',
        ];
    }

    /** Effective price: departure override, else the schedule price, else the route base price. */
    public function priceInPesewas(): int
    {
        return $this->price ?? $this->schedule->price ?? $this->schedule->route->base_price;
    }

    /** @return BelongsTo<Schedule, $this> */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /** @return HasMany<Booking, $this> */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /** @return HasMany<BookingSeat, $this> */
    public function seats(): HasMany
    {
        return $this->hasMany(BookingSeat::class);
    }
}

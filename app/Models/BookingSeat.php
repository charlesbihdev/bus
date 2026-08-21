<?php

namespace App\Models;

use Database\Factories\BookingSeatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $booking_id
 * @property int $departure_id
 * @property string $seat_label
 * @property string|null $passenger_name
 */
#[Fillable(['booking_id', 'departure_id', 'seat_label', 'passenger_name'])]
class BookingSeat extends Model
{
    /** @use HasFactory<BookingSeatFactory> */
    use HasFactory;

    /** @return BelongsTo<Booking, $this> */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /** @return BelongsTo<Departure, $this> */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }
}

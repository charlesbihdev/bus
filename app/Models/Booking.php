<?php

namespace App\Models;

use Database\Factories\BookingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $departure_id
 * @property string $reference
 * @property string $contact_name
 * @property string $contact_phone
 * @property int $total_amount
 * @property string $status
 * @property Carbon|null $expires_at
 * @property Carbon|null $paid_at
 */
#[Fillable([
    'user_id', 'departure_id', 'reference', 'contact_name',
    'contact_phone', 'total_amount', 'status', 'expires_at', 'paid_at',
])]
class Booking extends Model
{
    /** @use HasFactory<BookingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'total_amount' => 'integer',
            'expires_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    /** A pending hold that has passed its window still counts as blocking until swept. */
    public function isActiveHold(): bool
    {
        return $this->status === 'pending'
            && $this->expires_at !== null
            && $this->expires_at->isFuture();
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Departure, $this> */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    /** @return HasMany<BookingSeat, $this> */
    public function seats(): HasMany
    {
        return $this->hasMany(BookingSeat::class);
    }

    /** @return HasOne<Payment, $this> */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}

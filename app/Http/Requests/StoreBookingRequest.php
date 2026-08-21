<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * The route is already behind the auth middleware, so any signed-in
     * user may place a booking.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'seats' => ['required', 'array', 'min:1', 'max:6'],
            'seats.*' => ['required', 'string'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'passenger_names' => ['nullable', 'array'],
            'passenger_names.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}

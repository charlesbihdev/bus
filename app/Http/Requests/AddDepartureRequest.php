<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AddDepartureRequest extends FormRequest
{
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
            'direction' => ['required', 'in:forward,return'],
            'bus_id' => ['required', 'integer', 'exists:buses,id'],
            'departure_time' => ['required', 'date_format:H:i'],
            'price' => ['nullable', 'numeric', 'min:0'], // GHS; blank = route default
        ];
    }
}

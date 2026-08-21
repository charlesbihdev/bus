<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRouteRequest extends FormRequest
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
            'origin_town_id' => ['required', 'integer', 'exists:towns,id'],
            'destination_town_id' => [
                'required', 'integer', 'exists:towns,id', 'different:origin_town_id',
                Rule::unique('routes', 'destination_town_id')->where('origin_town_id', $this->integer('origin_town_id')),
            ],
            'base_price' => ['required', 'numeric', 'min:0'], // GHS, converted to pesewas on store
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}

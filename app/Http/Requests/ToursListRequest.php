<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ToursListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'nullable|numeric',
            'priceTo' => 'nullable|numeric',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date',
            'sortBy' => 'nullable|in:price',
            'sortOrder' => 'nullable|in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            'sortBy.in' => "The 'sortBy' must be one of the following types: price",
            'sortOrder.in' => "The 'sortOrder' must be one of the following types: asc, desc.",
        ];
    }
}

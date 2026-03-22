<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'      => 'required|string',
            'maximum_amount'   => 'required|numeric|min:0.01',
            'alert_percentage' => 'required|integer|min:1|max:100',
            'start_date'       => 'required|date_format:Y-m-d',
            'end_date'         => 'required|date_format:Y-m-d|after:start_date',
            'currency'         => 'nullable|string|size:3',
        ];
    }
}

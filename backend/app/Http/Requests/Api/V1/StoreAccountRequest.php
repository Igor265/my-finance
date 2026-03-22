<?php

namespace App\Http\Requests\Api\V1;

use App\Contexts\Finance\Domain\ValueObjects\AccountType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::enum(AccountType::class)],
            'initial_amount' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
        ];
    }
}

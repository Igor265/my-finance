<?php

namespace App\Http\Requests\Api\V1;

use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
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
            'account_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'type' => ['required', Rule::enum(TransactionType::class)],
            'description' => 'required|string|max:255',
            'category_id' => 'nullable|string',
            'date' => 'required|date_format:Y-m-d',
        ];
    }
}

<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->accountId,
            'amount' => $this->amount->toFloat(),
            'currency' => $this->amount->currency,
            'type' => $this->type->value,
            'description' => $this->description,
            'category_id' => $this->categoryId,
            'date' => $this->date->format('Y-m-d'),
        ];
    }
}

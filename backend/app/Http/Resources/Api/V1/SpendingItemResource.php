<?php

namespace App\Http\Resources\Api\V1;

use App\Contexts\Insights\Application\DTOs\SpendingItemDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SpendingItemDTO */
class SpendingItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'category_id' => $this->categoryId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'transaction_count' => $this->transactionCount,
        ];
    }
}

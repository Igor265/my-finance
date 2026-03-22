<?php

namespace App\Http\Resources\Api\V1;

use App\Contexts\Insights\Application\DTOs\SummaryDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SummaryDTO */
class SummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'total_balance' => $this->totalBalance,
            'currency' => $this->currency,
            'account_count' => $this->accountCount,
        ];
    }
}

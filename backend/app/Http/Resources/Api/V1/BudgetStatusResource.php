<?php

namespace App\Http\Resources\Api\V1;

use App\Contexts\Insights\Application\DTOs\BudgetStatusDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BudgetStatusDTO */
class BudgetStatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'budget_id' => $this->budgetId,
            'category_id' => $this->categoryId,
            'maximum_amount' => $this->maximumAmount,
            'spent_amount' => $this->spentAmount,
            'percentage' => $this->percentage,
            'currency' => $this->currency,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];
    }
}

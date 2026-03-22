<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
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
            'category_id' => $this->categoryId,
            'maximum_amount' => $this->limit->maximum->toFloat(),
            'currency' => $this->limit->maximum->currency,
            'alert_percentage' => $this->limit->alertPercentage,
            'start_date' => $this->period->startDate->format('Y-m-d'),
            'end_date' => $this->period->endDate->format('Y-m-d'),
        ];
    }
}

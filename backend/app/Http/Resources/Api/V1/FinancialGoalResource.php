<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FinancialGoalResource extends JsonResource
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
            'name' => $this->name,
            'target_amount' => $this->targetAmount->toFloat(),
            'current_amount' => $this->currentAmount->toFloat(),
            'currency' => $this->targetAmount->currency,
            'deadline' => $this->deadline->format('Y-m-d'),
        ];
    }
}

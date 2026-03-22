<?php

namespace App\Http\Resources\Api\V1;

use App\Contexts\Insights\Application\DTOs\GoalProgressDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GoalProgressDTO */
class GoalProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'goal_id' => $this->goalId,
            'name' => $this->name,
            'target_amount' => $this->targetAmount,
            'current_amount' => $this->currentAmount,
            'percentage' => $this->percentage,
            'currency' => $this->currency,
            'deadline' => $this->deadline,
        ];
    }
}

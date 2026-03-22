<?php

namespace App\Http\Resources\Api\V1;

use App\Contexts\Finance\Domain\Entities\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Account */
class AccountResource extends JsonResource
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
            'type' => $this->type->value,
            'balance' => $this->balance->toFloat(),
            'currency' => $this->balance->currency,
        ];
    }
}

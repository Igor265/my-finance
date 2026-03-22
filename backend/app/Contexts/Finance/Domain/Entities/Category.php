<?php

namespace App\Contexts\Finance\Domain\Entities;

use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

class Category
{
    readonly string $id;
    readonly string $userId;
    readonly string $name;
    readonly TransactionType $type;

    public function __construct(string $id, string $userId, string $name, TransactionType $type)
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Name is required');
        }
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->type = $type;
    }
}

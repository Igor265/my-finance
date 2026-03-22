<?php

namespace App\Contexts\Finance\Domain\ValueObjects;

use DateTimeImmutable;

class Period
{
    public readonly DateTimeImmutable $startDate;

    public readonly DateTimeImmutable $endDate;

    public function __construct(DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        if ($startDate > $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public static function fromStrings(string $startDate, string $endDate): self
    {
        return new self(new DateTimeImmutable($startDate), new DateTimeImmutable($endDate));
    }

    public function contains(DateTimeImmutable $date): bool
    {
        if ($date < $this->startDate || $date > $this->endDate) {
            return false;
        }

        return true;
    }

    public function inDays(): int
    {
        return $this->startDate->diff($this->endDate)->days;
    }
}

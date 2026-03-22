<?php

use App\Contexts\Finance\Domain\ValueObjects\Period;

it('should correct creat from string Y-m-d', function () {
    $period = Period::fromStrings('2026-03-01', '2026-03-31');
    expect($period->startDate->format('Y-m-d'))->toBe('2026-03-01')
        ->and($period->endDate->format('Y-m-d'))->toBe('2026-03-31');
});

it('should throw an error when startDate > endDate', function () {
    expect(fn() => Period::fromStrings('2026-03-31', '2026-03-01'))->toThrow(\InvalidArgumentException::class);
});

it('should return true when a date is on a period', function () {
    $period = Period::fromStrings('2026-03-01', '2026-03-31');
    expect($period->contains(new DateTimeImmutable('2026-03-10')))->toBeTrue();
});

it('should return false when a date is not on a period', function () {
    $period = Period::fromStrings('2026-03-01', '2026-03-31');
    expect($period->contains(new DateTimeImmutable('2026-04-10')))->toBeFalse();
});

it('should return the correct number of days', function () {
    $period = Period::fromStrings('2026-03-01', '2026-03-31');
    expect($period->inDays())->toBe(30);
});

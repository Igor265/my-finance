<?php

use App\Contexts\Finance\Domain\Entities\Category;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;

it('should create a category', function () {
    $category = new Category('1', 'user-1', 'test', TransactionType::Income);
    expect($category->id)->toBe('1');
});

it('should throw an error when creating a category with a blank name', function () {
    expect(fn () => new Category('1', 'user-1', '', TransactionType::Income))->toThrow(InvalidArgumentException::class);
});

it('should throw an error when creating a category with a name filed with spaces', function () {
    expect(fn () => new Category('1', 'user-1', '       ', TransactionType::Income))->toThrow(InvalidArgumentException::class);
});

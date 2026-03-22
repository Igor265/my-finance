<?php

namespace App\Contexts\Finance\Application\UseCases;

use App\Contexts\Finance\Application\DTOs\CreateCategoryDTO;
use App\Contexts\Finance\Domain\Entities\Category;
use App\Contexts\Finance\Domain\Exceptions\CategoryAlreadyExistsException;
use App\Contexts\Finance\Domain\Repositories\CategoryRepository;
use App\Contexts\Finance\Domain\ValueObjects\TransactionType;
use Illuminate\Support\Str;

class CreateCategoryUseCase
{
    public readonly CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function execute(CreateCategoryDTO $dto): Category
    {
        $existing = $this->categoryRepository->findByUserIdAndName($dto->userId, $dto->name);
        if ($existing) {
            throw new CategoryAlreadyExistsException('Category already exists');
        }
        $category = new Category((string) Str::uuid(), $dto->userId, $dto->name, TransactionType::from($dto->type));
        $this->categoryRepository->save($category);

        return $category;
    }
}

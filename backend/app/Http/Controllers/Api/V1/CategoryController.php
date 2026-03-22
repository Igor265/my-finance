<?php

namespace App\Http\Controllers\Api\V1;

use App\Contexts\Finance\Application\DTOs\CreateCategoryDTO;
use App\Contexts\Finance\Application\UseCases\CreateCategoryUseCase;
use App\Contexts\Finance\Domain\Repositories\CategoryRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public readonly CreateCategoryUseCase $createCategoryUseCase;

    public readonly CategoryRepository $categoryRepository;

    public function __construct(CreateCategoryUseCase $createCategoryUseCase, CategoryRepository $categoryRepository)
    {
        $this->createCategoryUseCase = $createCategoryUseCase;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * List categories
     *
     * Returns all categories belonging to the authenticated user. Supports pagination via `per_page` query parameter (default: 15).
     */
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);
        $categories = $this->categoryRepository->paginateByUserId((string) $request->user()->id, $perPage);

        return CategoryResource::collection($categories);
    }

    /**
     * Create category
     *
     * Creates a new transaction category. Returns 422 if a category with the same name already exists.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        $dto = new CreateCategoryDTO(
            (string) $request->user()->id,
            $validated['name'],
            $validated['type'],
        );
        $category = $this->createCategoryUseCase->execute($dto);

        return (new CategoryResource($category))->response()->setStatusCode(201);

    }

    /**
     * Get category
     *
     * Returns a specific category owned by the authenticated user.
     */
    public function show(string $id, Request $request)
    {
        $category = $this->categoryRepository->findById($id);
        if (! $category || $category->userId !== (string) $request->user()->id) {
            return response()->json(['message' => __('messages.category_not_found')], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

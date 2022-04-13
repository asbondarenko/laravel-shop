<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = Category::jsonPaginate();
        return CategoryResource::collection($products);
    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $result = $this->categoryService->create($request->validated());

        return response(new CategoryResource($result), 201);
    }

    /**
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return CategoryResource
     */
    public function update(CategoryRequest $request, Category $category): CategoryResource
    {
        $result = $this->categoryService->update($request->validated(), $category);
        return new CategoryResource($result);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $result = $this->categoryService->delete($category);
        if (!$result) {
            return response()->json([
                'message' => __('Failed to delete.'),
                'errors' => [
                    'id' => __('Category has products.'),
                ],
            ], 422);
        }

        return response()->noContent();
    }
}

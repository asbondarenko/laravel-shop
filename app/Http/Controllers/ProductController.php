<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected ProductService $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $products = Product::makeBuilder()->with('categories')->jsonPaginate();
        return ProductResource::collection($products);
    }

    /**
     * @param ProductRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(ProductRequest $request)
    {
        $result = $this->productService->create($request->validated());

        return response(new ProductResource($result), 201);
    }

    /**
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $result = $this->productService->update($request->validated(), $product);
        return new ProductResource($result);
    }

    /**
     * @param Product $product
     * @return ProductResource
     */
    public function archive(Product $product): ProductResource
    {
        $result = $this->productService->archive($product);
        return new ProductResource($result);
    }

    /**
     * @param int $id
     * @return ProductResource
     */
    public function restore(int $id): ProductResource
    {
        $result = $this->productService->restore($id);
        return new ProductResource($result);
    }
}

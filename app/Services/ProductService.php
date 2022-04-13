<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return Product::create($data);
    }

    /**
     * @param $data
     * @param Product $product
     * @return Product
     */
    public function update($data, Product $product): Product
    {
        $product->update($data);
        return $product;
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function archive(Product $product): Product
    {
        $product->delete();

        return $product;
    }

    /**
     * @param string $id
     * @return Product
     */
    public function restore(string $id): Product
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return $product;
    }
}

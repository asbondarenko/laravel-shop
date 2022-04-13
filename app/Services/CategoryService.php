<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    private array $errors = [];

    function addError($error)
    {
        $this->errors[] = $error;
    }

    function getErrors()
    {
        return $this->errors;
    }
    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return Category::create($data);
    }

    /**
     * @param $data
     * @param Category $category
     * @return Category
     */
    public function update($data, Category $category): Category
    {
        $category->update($data);
        return $category;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function delete(Category $category)
    {
        if($category->products()->withTrashed()->count()) {
            return false;
        }

        $category->delete();

        return true;
    }
}

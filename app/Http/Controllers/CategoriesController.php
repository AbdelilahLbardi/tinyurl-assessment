<?php

namespace App\Http\Controllers;

use App\Actions\Category\CreateCategory;
use App\Actions\Category\GetCategories;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoriesController
{
    public function index(GetCategories $getCategories): CategoryCollection
    {
        return CategoryCollection::make($getCategories->execute());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategory $createCategory): CategoryResource
    {
        $data = request()->validate([
            'name' => 'required|unique:categories'
        ]);

        $category = $createCategory->execute($data['name']);

        return new CategoryResource($category);
    }
}

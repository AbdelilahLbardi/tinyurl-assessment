<?php

namespace App\Actions\Category;


use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CreateCategory {

    public function execute(string $name): Category|Model
    {
        return Category::query()->create(
            compact('name')
        );
    }

}

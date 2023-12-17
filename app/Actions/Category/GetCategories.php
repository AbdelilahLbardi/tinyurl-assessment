<?php

namespace App\Actions\Category;


use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class GetCategories {

    public function execute(): Collection
    {
        return Category::query()->select('id', 'name')->get();
    }

}

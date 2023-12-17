<?php

namespace App\Actions\Product;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class CreateProduct {

    public function execute(string $name, string $description, int $price): Product|Model
    {
        return Product::query()->create(
            compact('name', 'description', 'price')
        );
    }

}

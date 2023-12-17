<?php

namespace App\Actions\Product;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class UpdateProduct {

    public function execute(int $id, string $name, string $description, int $price): Product|Model
    {
        $product = Product::query()->findOrFail($id);

        $product->update(
            compact('name', 'description', 'price')
        );

        return $product;
    }

}

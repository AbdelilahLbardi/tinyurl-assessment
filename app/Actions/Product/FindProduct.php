<?php

namespace App\Actions\Product;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class FindProduct {

    public function execute(int $id): Product|Model
    {
        $product = Product::query()->findOrFail($id);

        $product->update([
            'viewed_at' => now()
        ]);

        return $product;
    }

}

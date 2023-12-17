<?php

namespace App\Actions\Product;


use Database\Factories\ProductFactory;

class GenerateProducts {

    public function execute(): void
    {
        ProductFactory::new()->count(config('products.chunk_limit'))->create();
    }
}

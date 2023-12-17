<?php

namespace App\Actions\Product;


use Database\Factories\ProductFactory;

class GenerateProducts {

    const LIMIT = 1000;

    public function execute(): void
    {
        ProductFactory::new()->count(self::LIMIT)->create();
    }
}

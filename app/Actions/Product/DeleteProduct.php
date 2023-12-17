<?php

namespace App\Actions\Product;


use App\Models\Product;

class DeleteProduct {

    public function execute(int $id): void
    {
        Product::query()->delete($id);
    }

}

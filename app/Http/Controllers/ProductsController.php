<?php

namespace App\Http\Controllers;

use App\Actions\Product\CreateProduct;
use App\Actions\Product\DeleteProduct;
use App\Actions\Product\FindProduct;
use App\Actions\Product\GetProducts;
use App\Actions\Product\UpdateProduct;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Jobs\Product\GenerateProducts as GenerateProductsJob;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductsController
{
    public function index(GetProducts $getProducts): ProductCollection
    {
        $data = $getProducts->execute(request()->all());

        return ProductCollection::make($data);
    }

    public function store(CreateProduct $createProduct): ProductResource
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|integer|gte:0'
        ]);

        $product = $createProduct->execute(
            $data['name'], $data['description'], $data['price']
        );

        return new ProductResource($product);
    }

    public function generate(): JsonResponse
    {
        $productCount = config('products.generation_limit');

        $chunkLimit = config('products.chunk_limit');

        $jobsCount = (int)($productCount / $chunkLimit);

        for ($i = 0; $i < $jobsCount; $i++) {
            GenerateProductsJob::dispatch();
        }

        return response()->json([
            'message' => __("{$productCount} products will be available in your account shortly.")
        ]);
    }

    public function attachCategories(string $productId): ProductResource
    {
        $data = request()->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        /** @var Product $product */
        $product = Product::withTrashed()->findOrFail($productId);

        $product->categories()->sync(array_values($data['categories']));

        $product->load('categories');

        return ProductResource::make($product);
    }

    public function show(FindProduct $findProduct, string $id): ProductResource
    {
        return ProductResource::make(
            $findProduct->execute($id)
        );
    }

    public function update(UpdateProduct $updateProduct, string $id): ProductResource
    {
        $data = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|integer|gte:0'
        ]);

        $product = $updateProduct->execute(
            $id, $data['name'], $data['description'], $data['price']
        );

        return ProductResource::make($product);
    }

    public function destroy(DeleteProduct $deleteProduct, string $id): Response
    {
        $deleteProduct->execute($id);

        return response()->noContent();
    }
}

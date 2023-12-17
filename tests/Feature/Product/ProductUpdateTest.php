<?php

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

describe('Product update', function () {

    beforeEach(fn () => $this->product = ProductFactory::new()->create());

    test('validation is correct', function ($key, $value) {
        $response = productUpdateEndpoint(
            $this->product->id,
            [$key => $value]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrorFor($key);
    })->with([
        'empty name is invalid' => [
            'name', ''
        ],
        'null name is invalid' => [
            'name', null
        ],
        'empty description is invalid' => [
            'description', ''
        ],
        'null description is invalid' => [
            'description', null
        ],
        'empty price is invalid' => [
            'price', ''
        ],
        'null price is invalid' => [
            'price', null
        ],
        'negative price is invalid' => [
            'price', -1
        ]
    ]);

    test('Product with price set to 0 is valid', function () {
        $response = productUpdateEndpoint($this->product->id, [
            'price' => 0
        ]);

        $response->assertStatus(Response::HTTP_OK);
    });

    test('Product updated correctly', function () {
        $response = productUpdateEndpoint($this->product->id, [
            'name' => 'new name',
            'description' => 'new description',
            'price' => 0
        ]);

        $response->assertStatus(Response::HTTP_OK);

        assertDatabaseCount(Product::class, 1);

        assertDatabaseHas(Product::class, [
            'name' => 'new name',
            'description' => 'new description',
            'price' => 0,
        ]);
    });

});

function productUpdateEndpoint(string $id, array $data = []): TestResponse
{
    return putJson(
        route('products.update', $id),
        array_merge([
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100
        ], $data)
    );
}


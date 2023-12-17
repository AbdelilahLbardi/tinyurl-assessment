<?php

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\deleteJson;
use function PHPUnit\Framework\assertNotNull;

describe('Product deletion', function () {

    beforeEach(fn () => $this->product = ProductFactory::new()->create());

    test('other resources are ignore when ID is invalid', function () {

        assertDatabaseCount(Product::class, 1);

        productDeleteEndpoint(100)->assertStatus(Response::HTTP_NO_CONTENT);

        assertDatabaseCount(Product::class, 1);
    });

    test('Product deleted is still in the database but marked as deleted', function () {

        ProductFactory::new()->create([
            'name' => 'second product'
        ]);

        assertDatabaseCount(Product::class, 2);

        productDeleteEndpoint($this->product->id)->assertStatus(Response::HTTP_NO_CONTENT);

        assertDatabaseCount(Product::class, 2);

        assertNotNull($this->product->refresh()->deleted_at);

    });

});

function productDeleteEndpoint(string $id): TestResponse
{
    return deleteJson(route('api.products.destroy', $id));
}


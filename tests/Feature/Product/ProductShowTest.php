<?php

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\getJson;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

describe('Product show', function () {

    beforeEach(fn () => $this->product = ProductFactory::new()->create());

    test('throws 404 when model is not found', function () {

        assertDatabaseCount(Product::class, 1);

        $response = productGetEndpoint(100);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    });

    test('Valid ID returns correct resource', function () {

        ProductFactory::new()->create([
            'name' => 'second product'
        ]);

        $response = productGetEndpoint($this->product->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonPath('data.id', $this->product->id);
        $response->assertJsonPath('data.name', $this->product->name);
    });

    test('Viewing a product sets viewed_at', function () {

        assertNull($this->product->viewed_at);

        productGetEndpoint($this->product->id);

        $this->product->refresh();

        assertNotNull($this->product->viewed_at);
    });

});

function productGetEndpoint(string $id): TestResponse
{
    return getJson(route('products.show', $id));
}


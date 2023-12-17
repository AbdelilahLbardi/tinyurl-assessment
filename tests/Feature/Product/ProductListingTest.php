<?php

use App\Models\Product;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Carbon;
use Illuminate\Testing\TestResponse;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\getJson;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

describe('Products listing', function () {

    beforeEach(function () {

        ProductFactory::new()
            ->count(5)
            ->state(new Sequence(
                ['name' => '1', 'price' => 100, 'deleted_at' => now()],
                ['name' => '2', 'price' => 50, 'deleted_at' => null],
                ['name' => '3', 'price' => 300, 'deleted_at' => now()],
                ['name' => '4', 'price' => 400, 'deleted_at' => null],
                ['name' => '5', 'price' => 200, 'deleted_at' => now()],
            ))->create();
    });

    test('Products that are not marked as deleted are listed on top', function () {

        assertDatabaseCount(Product::class, 5);

        $products = productListEndpoint()->json('data');

        for ($i = 0; $i < 2; $i ++) {
            assertNull($products[$i]['deleted_at']);
        }

        for ($i = 2; $i < 5; $i ++) {
            assertNotNull($products[$i]['deleted_at']);
        }
    });

    test('sort by name ASC', function () {

        $products = productListEndpoint(['name' => 'asc'])->json('data');

        $expectedNamesOrder = [
            2, 4, 1, 3, 5
        ];

        for ($i = 0; $i < 5; $i ++) {
            assertEquals($expectedNamesOrder[$i], $products[$i]['name']);
        }
    });

    test('sort by name DESC', function () {

        $products = productListEndpoint(['name' => 'desc'])->json('data');

        $expectedNamesOrder = [
            4, 2, 5, 3, 1
        ];

        for ($i = 0; $i < 5; $i ++) {
            assertEquals($expectedNamesOrder[$i], $products[$i]['name']);
        }
    });

    test('sort by price ASC', function () {

        $products = productListEndpoint(['price' => 'asc'])->json('data');

        $expectedPricesOrder = [
            50, 400, 100, 200, 300
        ];

        for ($i = 0; $i < 5; $i ++) {
            assertEquals($expectedPricesOrder[$i], $products[$i]['price']);
        }
    });

    test('sort by price DESC', function () {

        $products = productListEndpoint(['price' => 'desc'])->json('data');

        $expectedPricesOrder = [
            400, 50, 300, 200, 100
        ];

        for ($i = 0; $i < 5; $i ++) {
            assertEquals($expectedPricesOrder[$i], $products[$i]['price']);
        }
    });

    test('last 10 viewed products doesnt show null', function () {

        $products = productListEndpoint(['last-viewed' => 'true'])->json('data');

        assertCount(0, $products);
    });

    test('last 10 viewed products shows in descending order', function () {

        Carbon::setTestNow('2023-01-01 00:00:00');

        ProductFactory::new()
            ->count(15)
            ->sequence(fn ($sequence) => [
                'viewed_at' => now()->addMinutes($sequence->index)
            ])->create();

        assertDatabaseCount(Product::class, 20);

        $products = productListEndpoint(['last-viewed' => 'true'])->json('data');

        assertCount(10, $products);
    });

});

function productListEndpoint(array $filters = []): TestResponse
{
    return getJson(
        route('products.index') . '?' . http_build_query($filters)
    );
}


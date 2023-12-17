<?php

use App\Actions\Product\GenerateProducts as GenerateProductsAction;
use App\Jobs\Product\GenerateProducts;
use App\Models\Product;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;

describe('Dummy products data generation', function () {

    test('products generated job is queued', function () {
        Queue::fake();

        Queue::assertNothingPushed();

        GenerateProductsEndpoint();

        Queue::assertPushed(GenerateProducts::class);
    });

    test('product generator creates up to the limit only', function () {

        assertDatabaseCount(Product::class, 0);

        $generateProductsAction = resolve(GenerateProductsAction::class);

        $generateProductsAction->execute();

        assertDatabaseCount(Product::class, GenerateProductsAction::LIMIT);

    });
});

function GenerateProductsEndpoint(): TestResponse
{
    return postJson(route('products.generate'));
}


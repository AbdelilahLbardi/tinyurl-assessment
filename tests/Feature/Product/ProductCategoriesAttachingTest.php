<?php

use App\Models\Product;
use Database\Factories\CategoryFactory;
use Database\Factories\ProductFactory;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;
use function PHPUnit\Framework\assertSame;

describe('Product Categories attaching', function () {

    beforeEach(function () {
        $this->product = ProductFactory::new()->create();

        $this->category1 = CategoryFactory::new()->create([
            'name' => 'category1'
        ]);

        $this->category2 = CategoryFactory::new()->create([
            'name' => 'category2'
        ]);

        $this->category3 = CategoryFactory::new()->create([
            'name' => 'category3'
        ]);
    });

    test('product has no categories', function () {
        assertSame(0, $this->product->categories()->count());
    });

    test('categories ids are required', function () {
        productCategoryAttachmentEndpoint(
            $this->product->id, []
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    });

    test('attaching invalid ids results invalid request', function () {
        productCategoryAttachmentEndpoint(
            $this->product->id,
            [
                $this->category1->id,
                100,
                $this->category2->id
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    });

    test('attaching valid ids to products are assigned correctly', function () {

        assertSame(0, $this->product->categories()->count());

        productCategoryAttachmentEndpoint(
            $this->product->id,
            [
                $this->category1->id,
                $this->category3->id
            ]
        )->assertStatus(Response::HTTP_OK);

        $this->product->refresh();

        assertSame(2, $this->product->categories()->count());

        assertSame('category1', $this->product->categories[0]->name);
        assertSame('category3', $this->product->categories[1]->name);
    });

    test('attaching new categories will override the other attached', function () {
        assertSame(0, $this->product->categories()->count());

        $this->product->categories()->sync([
            $this->category1->id,
            $this->category2->id
        ]);

        assertSame(2, $this->product->categories()->count());

        productCategoryAttachmentEndpoint(
            $this->product->id,
            [
                $this->category3->id
            ]
        )->assertStatus(Response::HTTP_OK);

        $this->product->refresh();

        assertSame(1, $this->product->categories()->count());

        assertSame('category3', $this->product->categories[0]->name);
    });

});

function productCategoryAttachmentEndpoint(string $id, array $categoriesIds = []): TestResponse
{
    return putJson(
        route('products.categories', $id), [
            'categories' => $categoriesIds
        ]
    );
}


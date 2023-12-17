<?php

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\postJson;

describe('Product creation', function () {

    test('invalid data doesnt create a product', function ($key, $value) {
        $response = productCreateEndpoint([$key => $value]);

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
        $response = productCreateEndpoint([
            'price' => 0
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    });

});

function productCreateEndpoint(array $data = []): TestResponse
{
    return postJson(
        route('api.products.store'),
        array_merge([
            'name' => 'test name',
            'description' => 'test description',
            'price' => 100
        ], $data)
    );
}


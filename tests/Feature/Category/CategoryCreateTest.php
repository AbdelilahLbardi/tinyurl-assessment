<?php

use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\postJson;

describe('Category Creation', function () {

    test('Invalid data is marked as unprocessable', function ($key, $value) {
        $response = categoryCreateEndpoint([$key => $value]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrorFor($key);
    })->with([
        'empty name is invalid' => [
            'name', ''
        ],
        'null name is invalid' => [
            'name', null
        ],
    ]);

    test('create a category', function () {
        $response = categoryCreateEndpoint();

        $response->assertStatus(Response::HTTP_CREATED);
    });

});

function categoryCreateEndpoint(array $data = []): TestResponse
{
    return postJson(
        route('api.categories.store'),
        array_merge([
            'name' => 'test name',
        ], $data)
    );
}


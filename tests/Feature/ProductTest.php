<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    DB::beginTransaction();
});

afterEach(function () {
    DB::rollBack();
});

it('returns a successful response', function () {
    $response = $this->get('/');
    $response->assertStatus(200);

});

it('all products', function () {
    $this->withExceptionHandling();
    $response = $this->get('/api/products');

    $response->assertStatus(200);
});

it('single product', function () {
    $this->withExceptionHandling();
    $response = $this->get('/api/products/1');


    $response->assertStatus(200);
});

it('store a new product', function () {
    $this->withExceptionHandling();

    $category = Category::factory()->create();

    $response = $this->post('/api/products', [
        'category_id' => $category->id,
        'name' => 'Product 1',
        'description' => 'Description 1',
        'price' => 100,
        'photos' => [
            [
                'photo' => 'test.jpg',
            ]
        ]
    ]);

    $response->assertStatus(201);
});

it('Update product', function () {
    $this->withExceptionHandling();
    $response = $this->patch('/api/products/1', [
        'name' => 'Product 11',
    ]);

    $product = Product::find(1);
    $this->assertEquals('Product 11', $product->name);

    $response->assertStatus(200);
});

it('Delete product', function () {
    $this->withExceptionHandling();
    $response = $this->delete('/api/products/1');

    $response->assertStatus(204);
});

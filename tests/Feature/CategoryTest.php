<?php

use App\Models\Category;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    DB::beginTransaction();
});

afterEach(function () {
    DB::rollBack();
});

it('all categories', function () {
    $this->withExceptionHandling();
    $response = $this->get('/api/categories');

    $response->assertStatus(200);
});

it('single category', function () {
    $this->withExceptionHandling();
    $response = $this->get('/api/categories/1');

    $response->assertStatus(200);
});

it('create category', function () {
    $this->withExceptionHandling();

    $response = $this->post('/api/categories', [
        'name' => 'test',
        'photo' => 'test',
    ]);

    $response->assertStatus(201);
});

it('update category', function () {
    $this->withExceptionHandling();

    $category = Category::factory()->create();
    $response = $this->put('/api/categories/' . $category->id, [
        'name' => 'test',
        'photo' => 'test',
    ]);

    $response->assertStatus(200);
});

it('delete category', function () {
    $this->withExceptionHandling();

    $category = Category::factory()->create();
    $response = $this->delete('/api/categories/' . $category->id);

    $response->assertStatus(204);
});



<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MainBannerController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubBannerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request){
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'index'])->name('login');

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('options', OptionController::class);
Route::delete('options/{optionId}/values/{valueId}', [OptionController::class, 'destroyValue']);

Route::apiResource('main-banners', MainBannerController::class);
Route::apiResource('sub-banners', SubBannerController::class);

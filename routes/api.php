<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MainBannerController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\PatentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\SubBannerController;
use App\Http\Controllers\Api\TeamController;
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

Route::apiResource('promos', PromoController::class);
Route::apiResource('patents', PatentController::class);
Route::apiResource('teams', TeamController::class);

Route::get('/download/{path}', [FileController::class, 'download'])->where('path', '.*');

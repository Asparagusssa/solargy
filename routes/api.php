<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContactSocialController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MainBannerController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\PageSectionController;
use App\Http\Controllers\Api\PatentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SubBannerController;
use App\Http\Controllers\Api\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request){
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'index'])->name('login');

Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('options', OptionController::class)->only(['index', 'show']);
Route::apiResource('main-banners', MainBannerController::class)->only(['index', 'show']);
Route::apiResource('sub-banners', SubBannerController::class)->only(['index', 'show']);
Route::apiResource('promos', PromoController::class)->only(['index', 'show']);
Route::apiResource('patents', PatentController::class)->only(['index', 'show']);
Route::apiResource('teams', TeamController::class)->only(['index', 'show']);
Route::apiResource('pages', PageSectionController::class)->only(['index', 'show']);
Route::apiResource('contacts', ContactController::class)->only(['index', 'show']);
Route::apiResource('socials', ContactSocialController::class)->only(['index', 'show']);


Route::get('/download/{path}', [FileController::class, 'download'])->where('path', '.*');
Route::get('search', SearchController::class);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('options', OptionController::class)->except(['index', 'show']);
    Route::delete('options/{optionId}/values/{valueId}', [OptionController::class, 'destroyValue']);

    Route::apiResource('main-banners', MainBannerController::class)->except(['index', 'show']);
    Route::apiResource('sub-banners', SubBannerController::class)->except(['index', 'show']);

    Route::apiResource('promos', PromoController::class)->except(['index', 'show']);
    Route::apiResource('patents', PatentController::class)->except(['index', 'show']);
    Route::apiResource('teams', TeamController::class)->except(['index', 'show']);

    Route::apiResource('pages', PageSectionController::class)->except(['index', 'show']);
    Route::apiResource('contacts', ContactController::class)->except(['index', 'show']);
    Route::apiResource('socials', ContactSocialController::class)->except(['index', 'show']);
});

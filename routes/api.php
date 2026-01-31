<?php

use App\Http\Controllers\Api\CallController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContactSocialController;
use App\Http\Controllers\Api\CustomStorageController;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\EmailTypeController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\FileLibraryController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ImageLibraryController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MainBannerController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageSectionController;
use App\Http\Controllers\Api\PatentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductOptionPriceController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\PurchasePlaceController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SeoController;
use App\Http\Controllers\Api\SubBannerController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\YMLController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request){
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'index'])->name('login');

Route::get('/yml-feed', [YMLController::class, 'generate'])->name('yml-feed');

Route::get('categories-with-services', [CategoryController::class, 'indexWithServices']);

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
Route::apiResource('purchase-place', PurchasePlaceController::class)->only(['index', 'show']);
Route::apiResource('companies', CompanyController::class)->only(['index', 'show']);
Route::apiResource('emails', EmailController::class)->only(['index', 'show']);
Route::apiResource('email-types', EmailTypeController::class)->only(['index', 'show']);
Route::apiResource('seos', SeoController::class)->only(['index', 'show']);
Route::apiResource('news', NewsController::class)->only(['index', 'show']);

Route::post('support', [SupportController::class, 'submitForm']);
Route::post('order', [OrderController::class, 'submitForm']);
Route::post('call', [CallController::class, 'submitForm']);

Route::apiResource('product-option-prices', ProductOptionPriceController::class);

Route::get('/download/{path}', [FileController::class, 'download'])->where('path', '.*');
Route::get('search', [SearchController::class, 'search']);
Route::get('search-fast', [SearchController::class, 'searchFast']);
Route::get('search/options', [SearchController::class, 'searchOptions']);

Route::get('/products/{productId}/photos/{color}', [ProductController::class, 'getProductPhotosByColor']);
Route::get('/products/{productId}/colors', [ProductController::class, 'getProductColorsForSelect']);

Route::get('my-storage/{path}', CustomStorageController::class)
    ->where('path', '.*');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('products/{product}/export-options', [ProductController::class, 'exportOptions']);
    Route::post('products/{product}/import-options', [ProductController::class, 'importOptions']);
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('options', OptionController::class)->except(['index', 'show']);
    Route::get('options/{option}/export', [OptionController::class, 'export']);
    Route::post('options/import', [OptionController::class, 'import']);
    Route::delete('options/{optionId}/values/{valueId}', [OptionController::class, 'destroyValue']);
    Route::delete('productPhoto/{ProductPhoto}', [ProductController::class, 'deletePhotos']);
    Route::delete('products/{product}/values/{value}', [ProductController::class, 'deletePivot']);
    Route::delete('products/{product}/options/{option}', [ProductController::class, 'deleteAllPivot']);
    Route::delete('productProperties/{property}', [ProductController::class, 'deleteProperty']);
    Route::delete('productProperties/{property}/files/{file}', [ProductController::class, 'deletePropertyFile']);
    Route::post('products/{product}/related', [ProductController::class, 'addRelatedProducts']);
    Route::delete('products/{product}/related/{relatedProduct}', [ProductController::class, 'removeRelatedProduct']);
    Route::delete('products/{product}/purchase-place/{purchasePlace}', [ProductController::class, 'removeMarket']);
    Route::post('products/{product}/copy', [ProductController::class, 'copyProduct']);
    Route::post('values/{value}/copy', [ProductController::class, 'copyValue']);

    Route::apiResource('main-banners', MainBannerController::class)->except(['index', 'show']);
    Route::apiResource('sub-banners', SubBannerController::class)->except(['index', 'show']);

    Route::apiResource('promos', PromoController::class)->except(['index', 'show']);
    Route::post('promos/{promo}/products/{product}', [PromoController::class, 'addProduct']);
    Route::delete('promos/{promo}/products/{product}', [PromoController::class, 'removeProduct']);
    Route::apiResource('patents', PatentController::class)->except(['index', 'show']);
    Route::apiResource('teams', TeamController::class)->except(['index', 'show']);
    Route::apiResource('seos', SeoController::class)->except(['index', 'show']);
    Route::apiResource('news', NewsController::class)->except(['index', 'show']);
    Route::delete('news/{news}/delete-image', [NewsController::class, 'deleteImage']);

    Route::apiResource('pages', PageSectionController::class)->except(['index', 'show']);
    Route::apiResource('emails', EmailController::class)->except(['index', 'show']);
    Route::apiResource('email-types', EmailTypeController::class)->except(['index', 'show']);
    Route::delete('email-types/{typeId}/emails/{emailId}', [EmailTypeController::class, 'destroyPivot']);


    Route::apiResource('contacts', ContactController::class)->except(['index', 'show']);
    Route::apiResource('socials', ContactSocialController::class)->except(['index', 'show']);
    Route::apiResource('purchase-place', PurchasePlaceController::class)->except(['index', 'show']);
    Route::apiResource('companies', CompanyController::class)->except(['index', 'show']);
    Route::delete('custom-details/{customId}', [CompanyController::class, 'destroyCustom']);

    Route::get('all-products', [ProductController::class, 'getAllProducts']);
    Route::get('products-for-select', [ProductController::class, 'productsForSelect']);

    Route::post('/upload-image', [ImageController::class, 'uploadImage']);
    Route::apiResource('/library-images', ImageLibraryController::class);
    Route::apiResource('/library-files', FileLibraryController::class);
});


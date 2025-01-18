<?php

use App\Http\Controllers\Api\FileController;
use Illuminate\Support\Facades\Route;

Route::any('{any}', function () {
    return redirect('https://solargy.shop');
})->where('any', '.*');

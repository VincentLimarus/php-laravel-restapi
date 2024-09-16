<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'GetAllProducts'); 
    Route::get('/{product}', 'GetProductByID');
    Route::post('/create', 'CreateProduct'); 
    Route::post('/update', 'UpdateProduct');
    Route::post('/delete', 'DeleteProduct');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/products', [ProductController::class, 'getAllProducts']);

Route::get('/product/{id}', [ProductController::class, 'getProductById']);

Route::post('/products-by-ids', [ProductController::class, 'getProductsByIds']);

Route::get('/discounted-products', [ProductController::class, 'getDiscountedProducts']);

Route::get('/categories', [CategoryController::class, 'getCategories']);

Route::get('/image/{imageName}', [ImageController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::post('/user/register', [UserController::class, 'store']);
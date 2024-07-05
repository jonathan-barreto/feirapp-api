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

Route::post('/user/register', [UserController::class, 'store']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
  // Product route
  Route::post('/products', [ProductController::class, 'index']);
  Route::get('/product/{id}', [ProductController::class, 'show']);
  Route::post('/products-by-ids', [ProductController::class, 'getProductsByIds']);
  Route::get('/discounted-products', [ProductController::class, 'getDiscountedProducts']);

  // Category route
  Route::get('/categories', [CategoryController::class, 'getCategories']);

  // Image route
  Route::get('/image/{imageName}', [ImageController::class, 'show']);

  // User route
  Route::get('/user/profile', [UserController::class, 'show']);
  Route::get('/user/logout', [UserController::class, 'logout']);
});

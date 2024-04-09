<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/products', [ProductController::class, 'getAllProducts']);

Route::get('/products/name/{name}', [ProductController::class, 'getProductByName']);

Route::post('/products', [ProductController::class, 'getProductsByIds']);

Route::get('/products/category/{category}', [ProductController::class, 'getProductsByCategory']);

Route::get('/products/order/{order}/sorted-by/{sortedBy}', [ProductController::class, 'getProductsOrderedBy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

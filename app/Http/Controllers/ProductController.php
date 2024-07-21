<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
  //
  public function getProducts(Request $request)
  {
    try {
      //code...
      Validator::make($request->all(), [
        'name' => 'string|nullable',
        'category' => 'string|nullable',
        'min_price' => 'numeric|nullable',
        'max_price' => 'numeric|nullable',
        'order' => 'string|nullable|in:asc,desc',
      ]);
    } catch (ValidationException $e) {
      //
      $errors = $e->validator->errors();
      $firstErrorMessage = collect($errors->messages())->flatten()->first();

      return response()->json([
        'data' => null,
        'message' => $firstErrorMessage,
      ], 422);
    }

    $name = $request->input('name');
    $category = $request->input('category');
    $minPrice =  $request->input('min_price');
    $maxPrice =  $request->input('max_price');
    $order = $request->input('order', 'asc');

    $query = Products::query();

    if ($request->filled('name')) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    if ($request->filled('category')) {
      $query->where('category', $category);
    }

    if ($request->filled('min_price')) {
      $minPrice = intval($request->input('min_price'));
      $query->where('price', '>=', $minPrice);
    }

    if ($request->filled('max_price')) {
      $maxPrice = intval($request->input('max_price'));
      $query->where('price', '<=', $maxPrice);
    }

    if ($request->filled('order')) {
      $query->orderBy('name', $order);
    }

    $products = $query->simplePaginate(10);
    $products->appends($request->all());

    if ($products->isEmpty()) {
      return response()->json([
        'data' => [],
        'message' => 'Nenhum produto foi encontrado.',
      ], 200);
    }

    return response()->json([
      'data' => $products,
      'message' => 'Nenhum produto foi encontrado.',
    ], 200);
  }

  public function getProduct(Request $request)
  {
    $product = Products::find($request->id);

    if ($product) {
      return ProductResource::collection([$product]);
    } else {
      return ProductResource::collection([]);
    }
  }

  public function getProductsByIds(Request $request)
  {
    $productIds = json_decode($request->getContent())->idsProducts;

    $products = Products::whereIn('id', $productIds)->orderByRaw(Products::raw("FIELD(id, " . implode(',', $productIds) . ")"))->get();

    if (!$products) {
      return ProductResource::collection([]);
    }

    return response()->json([
      "data" => $products,
      "message" => null,
    ]);
  }


  public function getDiscountedProducts()
  {
    $products = Products::where('discount', '!=', 0)->get();
    return ProductResource::collection($products);
  }
}

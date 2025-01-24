<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
  public function getProducts(Request $request)
  {
    try {
      Validator::make($request->all(), [
        'name' => 'string|nullable',
        'category' => 'string|nullable',
        'min_price' => 'numeric|nullable',
        'max_price' => 'numeric|nullable',
        'order' => 'string|nullable|in:asc,desc',
      ])->validate();
    } catch (ValidationException $e) {
      $errors = $e->validator->errors();
      $firstErrorMessage = collect($errors->messages())->flatten()->first();

      return response()->json([
        'success' => false,
        'data' => null,
        'message' => $firstErrorMessage,
      ], 422);
    }

    $name = $request->input('name');
    $category = $request->input('category');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');
    $order = $request->input('order', 'asc');

    $query = Products::query();

    if ($request->filled('name')) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    if ($request->filled('category')) {
      $query->where('category', $category);
    }

    if ($request->filled('min_price')) {
      $query->where('price', '>=', intval($minPrice));
    }

    if ($request->filled('max_price')) {
      $query->where('price', '<=', intval($maxPrice));
    }

    if ($request->filled('order')) {
      $query->orderBy('name', $order);
    }

    $products = $query->simplePaginate(10);
    $products->appends($request->all());

    if ($products->isEmpty()) {
      return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Nenhum produto foi encontrado.',
      ], 200);
    }

    return response()->json([
      'success' => true,
      'data' => $products,
      'message' => null,
    ], 200);
  }

  public function getProduct(Request $request)
  {
    $product = Products::find($request->id);

    if ($product) {
      return response()->json([
        'success' => true,
        'data' => $product,
        'message' => null,
      ], 200);
    } else {
      return response()->json([
        'success' => false,
        'data' => null,
        'message' => 'O produto não foi encontrado.',
      ], 404);
    }
  }

  public function getProductsByIds(Request $request)
  {
    $productIds = json_decode($request->getContent())->idsProducts;

    $products = Products::whereIn('id', $productIds)
      ->orderByRaw(Products::raw("FIELD(id, " . implode(',', $productIds) . ")"))
      ->get();

    if ($products->isEmpty()) {
      return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Nenhum produto encontrado para os IDs fornecidos.',
      ], 200);
    }

    return response()->json([
      'success' => true,
      'data' => $products,
      'message' => null,
    ], 200);
  }

  public function getDiscountedProducts()
  {
    $products = Products::where('discount_price', '!=', null)->get();

    if ($products->isEmpty()) {
      return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Nenhum produto com desconto foi encontrado.',
      ], 200);
    }

    return response()->json([
      'success' => true,
      'data' => $products,
      'message' => null,
    ], 200);
  }
}

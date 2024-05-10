<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
  public function getAllProducts(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'string|nullable',
      'category' => 'string|nullable',
      'min_price' => 'numeric|nullable',
      'max_price' => 'numeric|nullable',
      'order' => 'string|nullable|in:asc,desc',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 400);
    }

    $name = $request->input('name');
    $category = $request->input('category');
    $minPrice =  $request->input('min_price');
    $maxPrice =  $request->input('max_price');
    $order = $request->input('order', 'asc');

    $query = ProductModel::query();

    if ($request->has('name')) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    if ($request->has('category')) {
      $query->where('category', $category);
    }

    if ($request->has('min_price')) {
      $query->where('price', '>=', $minPrice);
    }

    if ($request->has('max_price')) {
      $query->where('price', '<=', $maxPrice);
    }

    $query->orderBy('name', $order);

    $products = $query->simplePaginate(10);

    if ($products->isEmpty()) {
      return ProductResource::collection([]);
    }

    return ProductResource::collection($products);
  }

  

  public function getProductsByIds(Request $request)
  {
    $productIds = json_decode($request->getContent())->idsProducts;

    $products = ProductModel::whereIn('id', $productIds)->orderByRaw(ProductModel::raw("FIELD(id, " . implode(',', $productIds) . ")"))->get();

    if (!$products) {
      return ProductResource::collection([]);
    }

    return response()->json(["products" => $products]);
  }


  public function getDiscountedProducts()
  {
    $products = ProductModel::where('discount', '!=', 0)->get();
    return ProductResource::collection($products);
  }
}

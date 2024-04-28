<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function getAllProducts()
  {
    $products = ProductModel::simplePaginate(10);

    if ($products->isEmpty()) {
      return ProductResource::collection([]);
    }

    return ProductResource::collection($products);
  }

  public function getProductByName($name)
  {
    $products = ProductModel::where('name', 'like', '%' . $name . '%')->simplePaginate(10);

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

  public function getProductsByCategory($category)
  {
    $products = ProductModel::where('category', $category)->simplePaginate(10);

    if ($products->isEmpty()) {
      return ProductResource::collection([]);
    }

    return response()->json($products);
  }

  public function getProductsOrderedBy($order, $sorted)
  {
    try {
      $products = ProductModel::orderBy($order, $sorted)->simplePaginate(10);
      return response()->json($products);
    } catch (\Exception $e) {
      return response()->json(["message" => $e->getMessage()], 200);
    }
  }

  public function getDiscountedProducts()
  {
    $products = ProductModel::where('discount', '!=', 0)->get();

    return ProductResource::collection($products);
  }
}

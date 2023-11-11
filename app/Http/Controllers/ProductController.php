<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  //
  public function index()
  {
    $products = ProductModel::simplePaginate(10);

    if ($products->isEmpty()) {
      return ProductResource::collection([]);
    }

    return ProductResource::collection($products);
  }

  public function findByName($name)
  {
    $products = ProductModel::where('name', 'like', '%' . $name . '%')->simplePaginate(10);
    return ProductResource::collection($products);
  }

  public function findProductByIds(Request $request)
  {
    $productIds = json_decode($request->getContent())->idsProducts;

    $products = ProductModel::whereIn('id', $productIds)
        ->orderByRaw(ProductModel::raw("FIELD(id, " . implode(',', $productIds) . ")"))
        ->get();

    if (!$products) {
        return ProductResource::collection([]);
    }

    return response()->json(["products" => $products]);
  }

  public function findByCategory($category)
  {
    $products = ProductModel::where('category', $category)->simplePaginate(10);

    if ($products->isEmpty()) {
      return ProductResource::collection([]);
    }

    return response()->json($products);
  }

  public function findProductsOrderedBy($order, $sorted)
  {
    try {
      $products = ProductModel::orderBy($order, $sorted)->simplePaginate(10);
      return response()->json($products);
    } catch (\Exception $e) {
      return response()->json(["response" => $e->getMessage()], 500);
    }
  }
}

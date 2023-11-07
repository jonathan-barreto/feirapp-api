<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  //
  public function index()
  {
    $products = ProductModel::simplePaginate(10);

    if ($products->isEmpty()) {
      return response()->json(["response" => "Not found products."], 404);
    }

    return response()->json($products);
  }

  public function findByName($name)
  {
    $products = ProductModel::where('name', 'like', '%' . $name . '%')->simplePaginate(10);

    if ($products->isEmpty()) {
      return response()->json(["response" => "Not found products"], 404);
    }

    return response()->json($products);
  }

  public function findProductByIds(Request $request)
  {
    $productIds = json_decode($request->getContent())->idsProducts;

    $products = ProductModel::find($productIds);

    if (!$products) {
      return response()->json(["response" => "Products not found"], 404);
    }

    return response()->json(["products" => $products]);
  }

  public function findByCategory($category)
  {
    $products = ProductModel::where('category', $category)->simplePaginate(10);

    if ($products->isEmpty()) {
      return response()->json(["response" => "Category not found."], 404);
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

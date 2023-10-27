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
    $products = DB::table('products')->simplePaginate(10);

    $productsArray = $products->toArray();

    if ($productsArray["data"] != null) {
      return response()->json($products);
    }

    return response()->json(["response" => "Not found products."], 404);
  }

  public function findByName($name)
  {
    $products = ProductModel::where('name', 'like', '%' . $name . '%')->simplePaginate(10);

    $productsArray = $products->toArray();

    if ($productsArray["data"] != null) {
      return response()->json($products);
    }

    return response()->json(["response" => "Not found products"], 404);
  }

  public function findProductByIds(Request $request)
  {
    $productIds = json_decode($request->getContent())->idProducts;

    $products = ProductModel::select("*")->find($productIds);

    $response = ["products" => $products];

    return response()->json($response);
  }

  public function findByCategory($category)
  {
    $products = ProductModel::where('category', $category)->simplePaginate(10);

    $productsArray = $products->toArray();

    if ($productsArray["data"] != null) {
      return response()->json($products);
    }

    return response()->json(["response" => "Category not found."], 404);
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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\CategoriesModel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function getCategories()
    {
        $categories = CategoriesModel::all();
        return CategoryResource::collection($categories);
    }
}

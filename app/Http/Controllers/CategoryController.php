<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function getCategories()
    {
        $categories = Categories::all();
        return CategoryResource::collection($categories);
    }
}

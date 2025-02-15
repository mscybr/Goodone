<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        return response()->json(Category::get(["id", "name", "image"]));
    }

    function subcategories(Request $request)
    {
        return response()->json(Subcategory::Select("name", "id", "category_id")->With(['Category' => function ($query) {
                $query->select('id', 'name', "image");
            }])->get());
    }
}

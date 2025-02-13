<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        return response()->json(Category::all());
    }

    function subcategories(Request $request)
    {
        return response()->json(Subcategory::all());
    }
}

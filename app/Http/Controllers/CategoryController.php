<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        // $categories = Category::Select("id", "name", "image")->With(['Subcategory' => function ($query) {
        //         $query->select('id', 'name');
        //     }])->get();
        $categories = Category::Select("id", "name", "image")->get();
        foreach ($categories as $key => $category ) {
            $categories[$key]["subcategory"] = DB::table('subcategories')->where('category_id', $category["id"])->select('id', 'name')->get();
        }
        if(Auth("api")->user() != null){
            $user = Auth("api")->user();
            if( $user["type"] == "worker" ){

                foreach ($categories as $key => $category ) {
                    $has_services_in_category = DB::table('services')
                    ->where('user_id', $user["id"])
                    ->where('category_id', $category["id"])
                    ->whereNotNull('license')
                    ->count() > 0;
                    $categories[$key]["has_liscence_in_category"] = $has_services_in_category;
                }
            }

        }
        // Auth("api")->user()
        return response()->json($categories);
    }

    function subcategories(Request $request)
    {
        return response()->json(Subcategory::Select("name", "id", "category_id")->With(['Category' => function ($query) {
                $query->select('id', 'name', "image");
            }])->get());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function create_category()
    {
        return view('admin.category', ["categories"=> Category::all()]);
    }

    public function store_category(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|unique:categories,name',
            "image" => "file|required",
        ]);

        if($request->file('image')){
            $file = $request->file('image');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["image"] = $file_name;
        }

        $category = Category::create($validation);
        return redirect()->back();
        // return response()->json($category);

    }


    public function delete_category(Request $request)
    {
         $validation = $request->validate([
            'id' => 'required|exists:categories,id',
        ]);
        Category::find($validation["id"])->delete();
        return redirect()->back();
    }

}

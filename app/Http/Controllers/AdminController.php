<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Subcategory;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    

    public function create_coupon()
    {
        return view('admin.coupons', ["coupons"=> Coupon::all()]);
    }

    public function withdraw_requests()
    {
        return view('admin.withdrawals', ["requests"=> WithdrawRequest::Where([["status", "<", "2"]])->get()]);
    }

    public function accept_withdraw_request( Request $request, WithdrawRequest $withdraw_request )
    {
        $withdraw_request->update(["status" => "1"]);
        return redirect()->back();
    }

    public function reject_withdraw_request( Request $request, WithdrawRequest $withdraw_request )
    {
        $withdraw_request->update(["status" => "2"]);
        return redirect()->back();
    }

    public function store_coupon(Request $request)
    {
        $validation = $request->validate([
            'coupon' => 'required|unique:coupons,coupon',
            "max_usage" => "required",
            "percentage" => "required",
        ]);


        $category = Coupon::create($validation);
        return redirect()->back();
        // return response()->json($category);

    }

      public function delete_coupon(Request $request)
    {
         $validation = $request->validate([
            'id' => 'required|exists:coupons,id',
        ]);
        Coupon::find($validation["id"])->delete();
        return redirect()->back();
    }

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

    public function create_subcategory()
    {
        return view('admin.subcategory', ["categories"=> Category::get(["id as value", "name"]), "subcategories"=> Subcategory::all()]);
    }

    public function store_subcategory(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|unique:subcategories,name',
            "category_id" => "required|exists:categories,id",
        ]);

        $category = Subcategory::create($validation);
        return redirect()->back();
        // return response()->json($category);

    }


    public function delete_subcategory(Request $request)
    {
         $validation = $request->validate([
            'id' => 'required|exists:subcategories,id',
        ]);
        Subcategory::find($validation["id"])->delete();
        return redirect()->back();
    }

}

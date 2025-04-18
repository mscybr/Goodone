<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Subcategory;
use App\Models\WithdrawRequest;
use App\Models\AppSetting;
use App\Models\RegionTax;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    function edit_setting($key, $value){

        $setting = AppSetting::Where("key", "=", $key);
        if($setting->count() > 0){
            $setting->update(["value" => $value]);
        }else{
            AppSetting::create(["key" => $key, "value" =>  $value]);
        }

    }

    function get_default_images($type = "customer"){

        $customer = AppSetting::Where("key", "=", "customer-image");
        $provider = AppSetting::Where("key", "=", "provider-image");
        $customer_image = "";
        $provider_image = "";
        if($customer->count() > 0){$customer_image->first();}
        if($provider->count() > 0){$provider_image->first();}
        return view("admin.default_images", ["customer_image" => $customer_image, $provider_image]);

    }
    
    function edit_default_images(Request $request){

        $validation = $request->validate([
            "customer_image" => "file",
            "provider_image" => "file",
        ]);

        if($request->file('customer_image')){
            $file = $request->file('customer_image');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["customer_image"] = $file_name;
        }
        if($request->file('provider_image')){
            $file = $request->file('provider_image');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["provider_image"] = $file_name;
        }

        if( $validation["customer_image"] ) $this->edit_setting("customer-image", $validation["customer_image"]);
        if( $validation["provider_image"] ) $this->edit_setting("provider-image", $validation["provider_image"]);
        

        return redirect()->back();
    }

    function get_app_settings(Request $request){
        $_settings = AppSetting::all();
        $settings = [];
        foreach ($_settings as $setting) {
            $settings[$setting->key] = $setting->value;
        }
        return view("admin.app_settings", ["settings" => $settings]);
    }

    function edit_app_settings(Request $request){

        if(isset( $request->platform_fees ))  $this->edit_setting("platform_fees", $request->platform_fees);
        if(isset( $request->platform_fees_percentage ))  $this->edit_setting("platform_fees_percentage", $request->platform_fees_percentage);
        // if(isset( $request->platform_fees ))  $this->edit_setting("platform_fees", $request->platform_fees);
        return redirect()->back();
    }



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

    public function create_region_tax()
    {
        return view('admin.region_taxes', ["regions"=> RegionTax::all()]);
    }
    

    public function store_region_tax(Request $request)
    {
        $validation = $request->validate([
            'region' => 'required',
            'percentage' => 'required',
        ]);

        $category = RegionTax::create($validation);
        return redirect()->back();
        // return response()->json($category);

    }


    public function delete_region_tax(Request $request)
    {
         $validation = $request->validate([
            'id' => 'required',
        ]);
        RegionTax::find($validation["id"])->delete();
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

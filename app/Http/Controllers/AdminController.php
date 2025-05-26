<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Subcategory;
use App\Models\WithdrawRequest;
use App\Models\AppSetting;
use App\Models\RegionTax;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{


    public function admin_home( Request $request ){
        $stats_today = [
            "users" => 0,
            "services" => 0,
            "orders" => 0,
            "revenue" => 0,
            "earnings" => 0
        ];
        $stats_month = [
            "users" => 0,
            "services" => 0,
            "orders" => 0,
            "revenue" => 0,
            "earnings" => 0
        ];
        $start_year = new \DateTime('now');
        $start_year->modify('first day of this year');
        $end_year = new \DateTime('now');
        $end_year->modify('last day of this year');

        $start_month = new \DateTime('now');
        $start_month->modify('first day of this month');
        $end_month = new \DateTime('now');
        $end_month->modify('last day of this month');

        $start_past_month = new \DateTime('now');
        $start_past_month->modify('first day of last month');
        $end_past_month = new \DateTime('now');
        $end_past_month->modify('last day of last month');

        $start_today = new \DateTime('now');
        $start_today->modify('today 00:00:00');
        $end_today = new \DateTime('now');
        $end_today->modify('today 23:59:59');

        $start_yesterday = new \DateTime('-1 day');
        $start_yesterday->modify('today 00:00:00');
        $end_yesterday = new \DateTime('-1 day');
        $end_yesterday->modify('today 23:59:59');

        $stats_year = $this->aquire_stats($start_year, $end_year);
        $stats_month = $this->aquire_stats($start_month, $end_month);
        $stats_past_month = $this->aquire_stats($start_past_month, $end_past_month);
        $stats_day = $this->aquire_stats($start_today, $end_today);
        $stats_yesterday = $this->aquire_stats($start_yesterday, $end_yesterday);

        if($stats_yesterday["users"] > 0){
            $stats_day["users_difference"] = (  ($stats_day["users"] / $stats_yesterday["users"]) - 1) * 100;
        }else{
            $stats_day["users_difference"] = $stats_day["users"] * 100;
        }

        if($stats_yesterday["services"] > 0){
            $stats_day["services_difference"] = (  ($stats_day["services"] / $stats_yesterday["services"]) - 1) * 100;
        }else{
            $stats_day["services_difference"] = $stats_day["services"] * 100;
        }

        if($stats_yesterday["orders"] > 0){
            $stats_day["orders_difference"] = (  ($stats_day["orders"] / $stats_yesterday["orders"]) - 1) * 100;
        }else{
            $stats_day["orders_difference"] = $stats_day["orders"] * 100;
        }

        if($stats_yesterday["revenue"] > 0){
            $stats_day["revenue_difference"] = (  ($stats_day["revenue"] / $stats_yesterday["revenue"]) - 1) * 100;
        }else{
            $stats_day["revenue_difference"] = $stats_day["revenue"] * 100;
        }

        if($stats_yesterday["earnings"] > 0){
            $stats_day["earnings_difference"] = (  ($stats_day["earnings"] / $stats_yesterday["earnings"]) - 1) * 100;
        }else{
            $stats_day["earnings_difference"] = $stats_day["earnings"] * 100;
        }

        if($stats_yesterday["earnings"] > 0){
            $stats_day["earnings_difference"] = (  ($stats_day["earnings"] / $stats_yesterday["earnings"]) - 1) * 100;
        }else{
            $stats_day["earnings_difference"] = $stats_day["earnings"] * 100;
        }



        if($stats_past_month["users"] > 0){
            $stats_month["users_difference"] = (  ($stats_month["users"] / $stats_past_month["users"]) - 1) * 100;
        }else{
            $stats_month["users_difference"] = $stats_month["users"] * 100;
        }

        if($stats_past_month["services"] > 0){
            $stats_month["services_difference"] = (  ($stats_month["services"] / $stats_past_month["services"]) - 1) * 100;
        }else{
            $stats_month["services_difference"] = $stats_month["services"] * 100;
        }

        if($stats_past_month["orders"] > 0){
            $stats_month["orders_difference"] = (  ($stats_month["orders"] / $stats_past_month["orders"]) - 1) * 100;
        }else{
            $stats_month["orders_difference"] = $stats_month["orders"] * 100;
        }

        if($stats_past_month["revenue"] > 0){
            $stats_month["revenue_difference"] = (  ($stats_month["revenue"] / $stats_past_month["revenue"]) - 1) * 100;
        }else{
            $stats_month["revenue_difference"] = $stats_month["revenue"] * 100;
        }

        if($stats_past_month["earnings"] > 0){
            $stats_month["earnings_difference"] = (  ($stats_month["earnings"] / $stats_past_month["earnings"]) - 1) * 100;
        }else{
            $stats_month["earnings_difference"] = $stats_month["earnings"] * 100;
        }
        
        if($stats_past_month["earnings"] > 0){
            $stats_month["earnings_difference"] = (  ($stats_month["earnings"] / $stats_past_month["earnings"]) - 1) * 100;
        }else{
            $stats_month["earnings_difference"] = $stats_month["earnings"] * 100;
        }

        dd([
            "month_stats" => $stats_month,
            "day_stats" => $stats_day,
            "stats_year" => $stats_year,
            "stats_yesterday" => $stats_yesterday,
            "stats_past_month" => $stats_past_month
        ]);
        return view("admin.index", [
            "month_stats" => $stats_month,
            "day_stats" => $stats_day,
            "stats_year" => $stats_year
        ]);
    }

    public function aquire_stats($from, $to){
        $users = User::whereBetween("created_at", [$from, $to])->count();
        $services = Service::whereBetween("created_at", [$from, $to])->count();
        $all_orders = Order::Where([["status", "=", 2]])->whereBetween("created_at", [$from, $to]);
        $orders = $all_orders->count();
        $revenue = 0;
        $earnings = 0;
        foreach ($all_orders->get() as $order) {
            $revenue += $order->price;
            $earnings += $order->platform_fee_amount - $order->discounted_amount;
        }
        return [
            "users" => $users,
            "services" => $services,
            "orders" => $orders,
            "revenue" => $revenue,
            "earnings" => $earnings,
        ];
    }


    // users
    public function get_users(Request $request){
        $users = User::Where([["type", "=", "customer"]])->get();
        foreach ($users as $user ) {

            $user_id = $user->id;
            $total_orders = 0;
            $total_discounts = 0;
            $orders = Order::select("*")->Where( [["order.user_id", "=", $user_id], ["order.status", "=", 2]])->get();
            foreach ($orders as $order ) {
                $total_amount = $order->coupon_percentage == null ? $order->price : ($order->price / (100-$order->coupon_percentage)  ) * 100;
                $total_discounts += ($order->price / (100-$order->coupon_percentage)  ) * $order->coupon_percentage;
                $total_orders += $total_amount;
            }
            $user["total_orders"] = $total_orders;
            $user["total_discounts"] = $total_discounts;

        }
        return view("admin.users", ["users" => $users]);
    }

    public function get_service_ratings(Request $request, Service $service){
        $ratings = Rating::With("user")->Where([["service_id", "=", $service->id]])->get();
        return view("admin.ratings", ["ratings" => $ratings]);
    }
    public function delete_rating( Request $request, Rating $rating ){
        $rating->delete();
        return redirect()->back();
    }

    public function get_services(Request $request){
        if(isset($request->user_id)){
            $services = Service::Where([["user_id", "=", $request->user_id]])->get();
        }else{
            $services = Service::all();
        }
        foreach ($services as $service ) {

            $user = User::Where([["id", "=", $service->user_id]])->first();
            if($user == null){ $user = (object)["full_name" => "Deleted User"]; }
            $user_id = $service->user_id;
            $total_orders = 0;
            $total_discounts = 0;
            $orders = Order::select("*")->Where( [["order.service_id", "=", $service->id], ["order.status", "=", 2]])->get();
            foreach ($orders as $order ) {
                $total_amount = $order->coupon_percentage == null ? $order->price : ($order->price / (100-$order->coupon_percentage)  ) * 100;
                $total_discounts += ($order->price / (100-$order->coupon_percentage)  ) * $order->coupon_percentage;
                $total_orders += $total_amount;
            }
            $service["total_orders"] = $total_orders;
            $service["total_discounts"] = $total_discounts;
            $service["user"] = $user;
        }
        return view("admin.services", ["services" => $services]);
    }

    public function toggle_service_activation(Request $request, Service $service){
       if($service->active){
         $service->update(["active" => false]);
        }else{
           $service->update(["active" => true]);
       }

       return redirect()->back();
    }


     public function get_service_providers (Request $request) {
        $users = User::Where([["type", "=", "worker"]])->get();
        foreach ($users as $user ) {

            $user_id = $user->id;
            $balance = 0;
            $withdrawn = 0;
            $total_orders = 0;
            $requests = WithdrawRequest::Where([
                ["user_id", "=", $user_id],
                ['status', "<", 2]
            ])->get();
            foreach ( $requests as $request ) { $withdrawn += $request["amount"]; }
            $orders = Order::join('services', "services.id", "=", "order.service_id")->select("services.*", "order.*")->Where( [["services.user_id", "=", $user_id], ["order.status", "=", 2]])->get();
            foreach ($orders as $order ) {
                $balance += $order["total_hours"] * $order["cost_per_hour"];
                $total_orders += $order["total_hours"] * $order["cost_per_hour"];
            }
            $balance -= $withdrawn;
            $user["balance"] = $balance;
            $user["total_orders"] = $total_orders;

        }
        return view("admin.service_providers", ["users" => $users]);
    }

     // user
    public function get_user(Request $request, User $user){
        return view("admin.user", ["user" => $user]);
    }
    
    public function edit_user(Request $request, User $user){
        // $user = User::Where("id", "=", $id);
        if($user->count() > 0 ){
            $validation = $request->validate([
                'full_name' => 'sometimes|string',
                'verified_liscence' => 'sometimes|boolean',
                'security_check' => 'sometimes|boolean',
                // 'password' => 'sometimes|string'
            ]);
            
            
            if(isset( $validation["password"] )) $validation["password"] = bcrypt($validation["password"]);
            if(isset( $validation["verified_liscence"] )) $validation["verified_liscence"] = $validation["verified_liscence"] == 1 ? true : false;
            if(isset( $validation["security_check"] )) $validation["security_check"] = $validation["security_check"] == 1 ? true : false;

            // if($request->file('image')){
            //     $file = $request->file('image');
            //     $temp = $file->store('public/images');
            //     $_array = explode("/", $temp);
            //     $file_name = $_array[ sizeof($_array) -1 ];
            //     $validation["image"] = $file_name;
            // }
            $user->update($validation);
            return redirect()->back();
        }else{
            return redirect()->back();
        }
    }


    public function activate_user(Request $request, User $user){
       $user->update(["active" => true]);
       return redirect()->back();
    }

    public function deactivate_user(Request $request, User $user){
       $user->update(["active" => false]);
       return redirect()->back();
    }


    public function block_user(Request $request, User $user){
       $user->update(["blocked" => true]);
       return redirect()->back();
    }

    public function unblock_user(Request $request, User $user){
       $user->update(["blocked" => false]);
       return redirect()->back();
    }

    
     public function get_orders (Request $request) {
        if( isset($request->user_id) ){
            $user = User::Where([["id", "=", $request->user_id]])->first();
            if(is_null($user) == false){
                if($user->type == "customer"){
                    $orders = Order::Where([["user_id", "=", $request->user_id]])->get();
                }else{
                    $services = Service::Where([["user_id", "=", $request->user_id]])->get();
                    foreach ($service as $service ) {
                        $service_orders = Order::Where([["service_id", "=", $service->id]])->get();
                        if(isset($orders)){
                            $orders->merge($service_orders);
                        }else{
                            $orders = $service_orders;
                        }
                    }
                }
            }else{
                $orders = [];
            }
        }elseif (isset($request->service_id)) {
            $orders = Order::Where([["service_id", "=", $request->service_id]])->get();
        }else{
            $orders = Order::all();
        }
        foreach ($orders as $order ) {
            $service = Service::Where([["id", "=", $order->service_id]])->first();
            $user = User::Where([["id", "=", $order->user_id]])->first();
            $order["user"] = $user;
            $order["service"] = $service;
        }
        return view("admin.orders", ["orders" => $orders]);
    }
    
     public function get_transactions (Request $request, User $user) {
        $total_transactions = [];
        if($user->type == "customer"){
            $orders = Order::Where([["user_id", "=", $user->id], ["status", ">", 0]])->orderBy('updated_at','DESC')->get();
            foreach ($orders as $order ) $total_transactions[] = [
                "type"=> "order", 
                "values" => $order
            ];
        }else{
            $orders = Order::join('services', "services.id", "=", "order.service_id")->select("services.*", "order.*")->Where( [["services.user_id", "=", $user->id], ["order.status", ">", 0]])->orderBy('order.updated_at','DESC')->get();
            $withdrawals = WithdrawRequest::Where([["status", "<", "2"]])->orderBy('updated_at','DESC')->get();
            $merged_dates_array = [];
            foreach ($orders as $order ) $merged_dates_array[] = ["type"=> "order", "values" => $order, "date" => $order->updated_at];
            foreach ($withdrawals as $withdrawal ) $merged_dates_array[] = ["type"=> "withdrawal", "values" => $withdrawal, "date" => $withdrawal->updated_at];
            usort($merged_dates_array, fn($a, $b) => $a['date'] <=> $b['date']);
            foreach ($merged_dates_array as $item ) {
                $total_transactions [] = [
                    "type" => $item["type"],
                    "values" => $item["values"]
                ];
            }

            // $orders = Order::Where([["user_id", "=", $user->id], ["status", ">", 0]])->orderBy('updated_at','DESC')->get();
        }
        return view("admin.transactions", ["transactions" => $total_transactions]);
    }

    
    public function edit_setting($key, $value){

        $setting = AppSetting::Where("key", "=", $key);
        if($setting->count() > 0){
            $setting->update(["value" => $value]);
        }else{
            AppSetting::create(["key" => $key, "value" =>  $value]);
        }

    }

    public function get_default_images($type = "customer"){

        $customer = AppSetting::Where("key", "=", "customer-image");
        $provider = AppSetting::Where("key", "=", "provider-image");
        $customer_image = "";
        $provider_image = "";
        $current_customer_image = "";
        $current_provider_image = "";
        if($customer->count() > 0){$current_customer_image = $customer->first()->value;}
        if($provider->count() > 0){$current_provider_image = $provider->first()->value;}
        return view("admin.default_images", ["customer_image" => $customer_image, "provider_image" => $provider_image, "current_provider_image" => $current_provider_image, "current_customer_image" => $current_customer_image]);

    }
    
    public function edit_default_images(Request $request){

        // $validation = $request->validate([
        //     "customer_image" => "file",
        //     "provider_image" => "file",
        // ]);
        $validation = [];
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

        if( isset($validation["customer_image"]) ) $this->edit_setting("customer-image", $validation["customer_image"]);
        if( isset($validation["provider_image"]) ) $this->edit_setting("provider-image", $validation["provider_image"]);

        return redirect()->back();
    }

    public function get_app_settings(Request $request){
        $_settings = AppSetting::all();
        $settings = [];
        foreach ($_settings as $setting) {
            $settings[$setting->key] = $setting->value;
        }
        return view("admin.app_settings", ["settings" => $settings]);
    }

    public function edit_app_settings(Request $request){

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

        
    public function edit_category(Request $request, Category $category){

        return view('admin.edit_category', ["category"=> $category]);
        
    }
        
    public function update_category(Request $request, Category $category){

        $update = [];

        if(isset( $request->category_name )) $update["name"] = $request->category_name;

        if($request->file('image')){
            $file = $request->file('image');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $update["image"] = $file_name;
        }
        $category->update($update);
        return redirect(route("admin_create_category"));
        
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


    public function delete_category(Request $request, Category $category)
    {
        Schema::disableForeignKeyConstraints();
        DB::delete('delete from subcategories where category_id = ?', [$category["id"]]);
        DB::delete('delete from categories where id = ?', [$category["id"]]);
        Schema::enableForeignKeyConstraints();
        // Category::find($validation["id"])->delete();
        return redirect()->back();
    }

    public function create_subcategory()
    {
        return view('admin.subcategory', ["categories"=> Category::get(["id as value", "name"]), "subcategories"=> Subcategory::all()]);
    }

    
        
    public function edit_subcategory(Request $request, Subcategory $subcategory){

        return view('admin.edit_subcategory', ["subcategory"=> $subcategory]);
        
    }
        
    public function update_subcategory(Request $request,  Subcategory $subcategory){

        $update = [];

        if(isset( $request->subcategory_name )) $update["name"] = $request->subcategory_name;

        $subcategory->update($update);
        return redirect(route("admin_create_subcategory"));
        
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


    public function delete_subcategory(Request $request, Subcategory $subcategory)
    {
        
        Schema::disableForeignKeyConstraints();
        DB::delete('delete from subcategories where id = ?', [$subcategory->id]);
        Schema::enableForeignKeyConstraints();
        return redirect()->back();
    }

    public function delete_service(Request $request, Service $service)
    {
        
        Schema::disableForeignKeyConstraints();
        DB::delete('delete from services where id = ?', [$service->id]);
        Schema::enableForeignKeyConstraints();
        return redirect()->back();
    }

    
}
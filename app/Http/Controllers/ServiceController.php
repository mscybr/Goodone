<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\ServiceGallary;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    //



        /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_to_gallary( Request $request)
    {
        $validation = $request->validate([
            "image" => "file|required",
            "service_id" => "required|exists:services,id"
        ]);

        if($request->file('image')){
            $file = $request->file('image');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["image"] = $file_name;
        }


        if ($validation) {
            $gall = ServiceGallary::create($validation);
            return response()->json($gall);

        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

    
    /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove_from_gallary( Request $request)
    {
        $validation = $request->validate([
            "id" => "required|exists:service_gallary,id",
        ]);

        if ($validation) {

            $del = ServiceGallary::Where("id",$validation["id"])->delete();
            return response()->json($del);

        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

        /**
     * create_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create_service( Request $request)
    {
        $validation = $request->validate([
            'years_of_experience' => "numeric|required",
            'about' => 'string|required',
            // 'country' => 'string|required',
            // 'city' => 'string|required',
            'location' => 'string|required',
            'cost_per_hour' => 'numeric|required',
            'service' => 'string|required',
            "license" => "file",
            "category_id" => "exists:categories,id|required",
            "subcategory_id" => "exists:subcategories,id|required",
            "active" => "boolean"
        ]);
        if(isset( $validation["password"] )) $validation["password"] = bcrypt($validation["password"]);
        $validation["user_id"] = auth("api")->user()->id;
        $validation["country"] = auth("api")->user()->country;
        $validation["country"] = auth("api")->user()->country;

        if($request->file('license')){
            $file = $request->file('license');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["license"] = $file_name;
        }

        if ($validation) {

            $service = Service::Where([["category_id", "=", $validation["category_id"]], ["user_id", "=", $validation["user_id"]], ["subcategory_id", "=", $validation["subcategory_id"]]]);
            if($service->count() == 0){
                $service = Service::create($validation);
            }else{
                $service = $service->first();
                $service->update($validation);
                // $service = $service->fresh();
            }
            // $service = Service::With(['User' => function ($query) {
            //     $query->select('id', 'email', "picture", "phone", "full_name");
            // }])->Where([["id", "=", $service["id"]]])->first();
            $service = Service::join('users', "users.id", "=", "services.user_id")->Where([["services.id", "=", $service["id"]]])->select(
                "services.id",
                "services.city",
                "services.country",
                "users.email",
                "users.phone",
                "users.full_name",
                "users.picture",
                "services.location",
                "services.cost_per_hour",
                "services.service",
                "services.years_of_experience",
                "services.about",
                "users.security_check",
                "users.verified_liscence",
            )->first();
            // Service::where('id',auth("api")->user()->id)->update($validation);
            // $updated = Auth("api")->user()->fresh();
            return response()->json($service);

        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }


    /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_services( Request $request)
    {
        // filtering method
        $query = "";
        if($request->has('query')) $query = $request->input('query');
        if($request->has('filter')){

            $services = Service::With(['Subcategory.Category' => function ($query) {
                $query->select('id', 'name', "image");
            }, 'Subcategory' => function ($query) {
                $query->select('id', 'name', "category_id");
            }])->join('users', "users.id", "=", "services.user_id")
            ->select(
                "services.id",
                "users.id AS contractor_id",
                "services.subcategory_id",
                "services.city", 
                "services.country", 
                "users.email",
                "users.phone",
                "users.full_name",
                "users.picture",
                "services.location",
                "services.cost_per_hour",
                "services.service",
                "services.years_of_experience",
                "services.about",
                "users.security_check",
                "users.verified_liscence",
            )->Where([["services.active", "=", true], ["services.service", "LIKE", "%$query%"]])->get()->sort(
            function($a, $b) {
                return $a <=> $b;
            }
            );

            // $services = User::With(['Subcategory.Category' => function ($query) {
            //     $query->select('id', 'name', "image");
            // }, 'Subcategory' => function ($query) {
            //     $query->select('id', 'name', "category_id");
            // }])->Select("city", "country", "email", "phone", "full_name", "picture", "location", "cost_per_hour", "service", "subcategory_id", "years_of_experience", "about", "security_check", "verified_liscence", "id")->Where([["active", "=", true], ["service", "LIKE", "%$query%"]])->get()->sort(
            // function($a, $b) {
            //     return $a <=> $b;
            // }
            // );
        }else{
            $services = Service::With(['Subcategory.Category' => function ($query) {
                $query->select('id', 'name', "image");
            }, 'Subcategory' => function ($query) {
                $query->select('id', 'name', "category_id");
            }])->join('users', "users.id", "=", "services.user_id")->select(
                "services.subcategory_id",
                "services.id",
                "services.city", 
                "services.country", 
                "users.id AS contractor_id",
                "users.email",
                "users.phone",
                "users.full_name",
                "users.picture",
                "services.location",
                "services.cost_per_hour",
                "services.service",
                "services.years_of_experience",
                "services.about",
                "users.security_check",
                "users.verified_liscence",
            )->Where([["services.active", "=", true], ["services.service", "LIKE", "%$query%"]])->get();
            // $services = User::With(['Subcategory.Category' => function ($query) {
            //     $query->select('id', 'name', "image");
            // }, 'Subcategory' => function ($query) {
            //     $query->select('id', 'name', "category_id");
            // }])->Select("city", "country", "email", "phone", "full_name", "picture", "location", "cost_per_hour", "service", "subcategory_id", "years_of_experience", "about", "security_check", "verified_liscence", "id")->Where([["active", "=", true], ["service", "LIKE", "%$query%"]])->get();
        }
        // dd($services);
        foreach ($services as $key => $service ) {
             $id = $service["id"];
            // $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
            // // return response()->json($gall);
            // $services[$key]["gallary"] = $gall;
            
            // $orders = Order::Where([["service_id", "=", $service["id"]]])->count();
            // $services[$key]["orders"] = $orders;

            $orders = Order::Select("total_hours", "start_at", "price")->Where([["service_id", "=", $id]])->count();
            $ratings = Rating::Select("message", "rate", "user_id", "created_at")->With(['User' => function ($query) {
                $query->select('id', 'full_name', "picture");
            }])->whereBelongsTo($service)->get();
             $total_ratings = 0;
                $times_rated = 0;
                foreach ($ratings as $key2 => $rating) {
                    $times_rated++;
                    $total_ratings += $rating["rate"];
                }
                $ratings_object = ["rating" => $times_rated != 0 ? $total_ratings / $times_rated : 0, "times_rated" => $times_rated];
                $services[$key]["rating"] = $ratings_object;
            $services[$key]["ratings"] = $ratings;
            $services[$key]["orders"] = $orders;
            $gall = ServiceGallary::Select("image")->Where([["service_id", $service["id"]]])->pluck("image");
            // return response()->json($gall);
            $services[$key]["gallary"] = $gall;
        }
        return response()->json($services);
    }

    /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_service( Request $request, $id)
    {
        // $service = User::Where("id", "=", $id)->first();
        // if($service){
        //     $orders = Order::Where([["service_id", "=", $id]])->count();
        //     $ratings = Rating::With(['User' => function ($query) {
        //         $query->select('id', 'full_name', "picture");
        //     }])->whereBelongsTo($service)->get();
        //     $service["ratings"] = $ratings;
        //     $service["orders"] = $orders;
        //     $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
        //     // return response()->json($gall);
        //     $service["gallary"] = $gall;
        // }
        // return response()->json($service);
    }

    public function get_user( Request $request, $id)
    {
        $service = User::select("full_name", "picture")->Where("id", "=", $id)->first();
        return response()->json($service);
    }

    /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_category_services( Request $request, $category_id)
    {
        $services = Service::With(['Subcategory.Category' => function ($query) {
                $query->select('id', 'name', "image");
            }, 'Subcategory' => function ($query) {
                $query->select('id', 'name', "category_id");
            }])->join('users', "users.id", "=", "services.user_id")->select(
                "services.id",
                "services.subcategory_id",
                "services.city", 
                "services.country", 
                "users.id AS contractor_id",
                "users.email",
                "users.phone",
                "users.full_name",
                "users.picture",
                "services.location",
                "services.cost_per_hour",
                "services.service",
                "services.years_of_experience",
                "services.about",
                "users.security_check",
                "users.verified_liscence",
            )->Where([["services.active", "=", true], ["services.category_id", "=", $category_id]])->get();
         foreach ($services as $key => $service ) {
            $id = $service["id"];
            // $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
            // // return response()->json($gall);
            // $services[$key]["gallary"] = $gall;
            // $orders = Order::Where([["service_id", "=", $service["id"]]])->count();
            // $services[$key]["orders"] = $orders;

            $orders = Order::Select("total_hours", "start_at", "price")->Where([["service_id", "=", $id]])->count();
            $ratings = Rating::Select("message", "rate", "user_id", "created_at")->With(['User' => function ($query) {
                $query->select('id', 'full_name', "picture");
            }])->whereBelongsTo($service)->get();
             $total_ratings = 0;
                $times_rated = 0;
                foreach ($ratings as $key2 => $rating) {
                    $times_rated++;
                    $total_ratings += $rating["rate"];
                }
                $ratings_object = ["rating" => $times_rated != 0 ? $total_ratings / $times_rated : 0, "times_rated" => $times_rated];
                $services[$key]["rating"] = $ratings_object;
            $services[$key]["ratings"] = $ratings;
            $services[$key]["orders"] = $orders;
            $gall = ServiceGallary::Select("image")->Where([["service_id", $service["id"]]])->pluck("image");
            // return response()->json($gall);
            $services[$key]["gallary"] = $gall;
        }
        
        return response()->json($services);
    }

    /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_my_services( Request $request)
    {
        $services = Service::With(['Subcategory.Category' => function ($query) {
                $query->select('id', 'name', "image");
            }, 'Subcategory' => function ($query) {
                $query->select('id', 'name', "category_id");
            }])->join('users', "users.id", "=", "services.user_id")->select(
                "services.id",
                "services.subcategory_id",
                "services.city", 
                "services.country", 
                "users.email",
                "users.phone",
                "users.full_name",
                "users.picture",
                "users.id AS contractor_id",
                "services.location",
                "services.cost_per_hour",
                "services.service",
                "services.years_of_experience",
                "services.about",
                "users.security_check",
                "users.verified_liscence",
            )->Where([["services.active", "=", true], ["users.id", "=", auth("api")->user()->id]])->get();
         foreach ($services as $key => $service ) {
            $id = $service["id"];
            // $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
            // // return response()->json($gall);
            // $services[$key]["gallary"] = $gall;
            // $orders = Order::Where([["service_id", "=", $service["id"]]])->count();
            // $services[$key]["orders"] = $orders;

            $orders = Order::Select("total_hours", "start_at", "price")->Where([["service_id", "=", $id]])->count();
            $ratings = Rating::Select("message", "rate", "user_id", "created_at")->With(['User' => function ($query) {
                $query->select('id', 'full_name', "picture");
            }])->whereBelongsTo($service)->get();
             $total_ratings = 0;
                $times_rated = 0;
                foreach ($ratings as $key2 => $rating) {
                    $times_rated++;
                    $total_ratings += $rating["rate"];
                }
                $ratings_object = ["rating" => $times_rated != 0 ? $total_ratings / $times_rated : 0, "times_rated" => $times_rated];
                $services[$key]["rating"] = $ratings_object;
            $services[$key]["ratings"] = $ratings;
            $services[$key]["orders"] = $orders;
            $gall = ServiceGallary::Select("image")->Where([["service_id", $service["id"]]])->pluck("image");
            // return response()->json($gall);
            $services[$key]["gallary"] = $gall;
        }
        
        return response()->json($services);
    }


    /**
     * rate_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rate_service( Request $request)
    {
        $validation = $request->validate([
            'rate' => 'integer|required',
            'message' => 'string|required',
            'service_id' => 'integer|required|exists:users,id',
        ]);
        $user_id = auth("api")->user()->id;
        $validation["user_id"] = $user_id;

        if ($validation) {
            $rate = Rating::Where([["user_id", "=", $validation["user_id"]], ["service_id", "=", $validation["service_id"]]]);
            if( $rate->count() > 0 ){
                $rate->update($validation);
                $rating = $rate->first();
            }else{
                $rating = Rating::create($validation);
            }
            return response()->json(['message' => 'Success', 'data' => $rating], 200);
        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function order_service( Request $request)
    {
        $validation = $request->validate([
            'total_hours' => 'integer|required',
            'start_at' => 'integer|required',
            'note' => 'string',
            'coupon' => 'string',
            'location' => 'string|required',
            'service_id' => 'integer|required|exists:services,id',
        ]);
        if($validation["coupon"]){
            $coup = Coupon::Where("coupon", "=", $validation["coupon"]);
            if( $coup->count() > 0  ){
                $coupon = $coup->first();
                if($coupon["times_used"] < $coupon["max_usage"]){

                    $validation["coupon_id"] = $coupon["id"];
                    $validation["coupon_percentage"] = $coupon["percentage"];
                    $coupon->update(["times_used" => $coupon["times_used"] + 1]);
                }
            }
        }
        $service = Service::Where("id", "=", $validation["service_id"])->first();
        if(isset($validation["coupon_percentage"])){
            $validation["price"] = ($service["cost_per_hour"] * $validation["total_hours"]) * (1-($validation["coupon_percentage"]/100)) ;
        }else{
            $validation["price"] = $service["cost_per_hour"] * $validation["total_hours"] ;
        }
        $validation["status"] = 1; //pending
        $validation["note"] = ""; //pending
        $user_id = auth("api")->user()->id;
        $validation["user_id"] = $user_id;

        if ($validation) {
            $order = Order::create($validation);
            return response()->json(['message' => 'Success', 'data' => $order], 200);
        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_orders( Request $request)
    {
        $user_id = auth("api")->user()->id;

        $orders = Order::Select("total_hours", "start_at", "price", "location", "service_id", "status")->With(['Service' => function ($query) {
            // $query->select('id', 'full_name', "picture", "service", "subcategory_id", "cost_per_hour");
            $query->join('users', "users.id", "=", "services.user_id")->select('services.id', 'users.full_name', "users.picture", "services.service", "services.subcategory_id", "services.cost_per_hour");
        }, 'Service.Subcategory' => function ($query) {
            $query->select('id', 'name');
        }])->Where([["user_id", "=", $user_id], ["status", ">", "0"]])->get();
        // $orders = Order::Where( [["user_id", "=", $user_id], ["status", ">", "0"]])->get();
        return response()->json(['message' => 'Success', 'data' => $orders], 200);
    }

     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_service_orders( Request $request)
    {
        $user_id = auth("api")->user()->id;

        // $orders = Order::Where( [["service_id", "=", $user_id], ["status", ">", "0"]])->get();
        $orders = Order::Select("total_hours", "start_at", "price", "location", "user_id", "status")->With(['User' => function ($query) {
            $query->select('id', 'full_name', "picture");
        }])->Where( [["service_id", "=", $user_id], ["status", ">", "0"]])->get();
        return response()->json(['message' => 'Success', 'data' => $orders], 200);
    }

     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_order( Request $request)
    {
        $validation = $request->validate([
            'order_id' => 'integer|required|exists:order,id',
        ]);
        $order = Order::Where("order_id", "=", $validation["order_id"])->get();
        return response()->json(['message' => 'Success', 'data' => $order], 200);
    }




     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_order( Request $request)
    {
        $validation = $request->validate([
            'total_hours' => 'integer',
            'start_at' => 'integer',
            // 'price' => 'numeric',
            'note' => 'string',
            'location' => 'string',
            'order_id' => 'integer|required|exists:order,id',
        ]);
        // $user_id = auth("api")->user()->id;

        if ($validation) {
            $order = Order::find($validation["order_id"])->first();
            $order->update($validation);
            return response()->json(['message' => 'Success', 'data' => $order], 200);
        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

    public function complete_order( Request $request ){
        return $this->change_order_status_by_user($request, 2);
    }
    public function cancel_order( Request $request ){
        return $this->change_order_status_by_worker($request, 3);
    }
     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function change_order_status_by_user( Request $request, $status)
    {
        $validation = $request->validate([
            'order_id' => 'integer|required|exists:order,id',
        ]);
        $user_id = auth("api")->user()->id;

        $collection = Order::Where([["user_id", "=", $user_id], ["id", "=", $validation["order_id"]]]);
        if ($collection->count() > 0) {
            $order = $collection->first();
            $order->update(["status" => $status]);
            return response()->json(['message' => 'Success', 'data' => $order], 200);
        }else{
            // $errors = $validator->errors();
            return response()->json(['error' => 'Not Found'], 404);
        }

    }
     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function change_order_status_by_worker( Request $request, $status)
    {
        $validation = $request->validate([
            'order_id' => 'integer|required|exists:order,id',
        ]);
        $user_id = auth("api")->user()->id;

        $collection = Order::Where([["service_id", "=", $user_id], ["id", "=", $validation["order_id"]]]);
        if ($collection->count() > 0) {
            $order = $collection->first();
            $order->update(["status" => $status]);
            return response()->json(['message' => 'Success', 'data' => $order], 200);
        }else{
            // $errors = $validator->errors();
            return response()->json(['error' => 'Not Found'], 404);
        }

    }


    
     /**
     * order_service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_coupon( Request $request)
    {
        $validation = $request->validate([
            'coupon' => 'required',
        ]);
        $coup = Coupon::Where("coupon", "=", $validation["coupon"]);
        if( $coup->count() > 0 ){
            $coupon = $coup->first();
            if($coupon["times_used"] < $coupon["max_usage"]){
                return response()->json(['message' => 'Success', 'data' => $coup->select("percentage")->first()], 200);
            }else{
                return response()->json(['message' => 'Not found'], 404);
            }
        }else{
            return response()->json(['message' => 'Not found'], 404);
        }
    }



}

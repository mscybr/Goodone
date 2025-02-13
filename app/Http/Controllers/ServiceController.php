<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Rating;
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
    public function get_services( Request $request)
    {
        // filtering method
        $query = "";
        if($request->has('query')) $query = $request->input('query');
        if($request->has('filter')){
     
            $services = User::Select("email", "phone", "full_name", "picture", "location", "cost_per_hour", "service", "category", "years_of_experience", "about", "security_check", "verified_liscence", "id")->Where([["active", "=", true], ["service", "LIKE", "%$query%"]])->get()->sort(
            function($a, $b) {
                return $a <=> $b;
            }
            );
        }else{
            $services = User::Where([["active", "=", true], ["service", "LIKE", "%$query%"]])->get();
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
            $ratings = Rating::Select("message", "rate", "user_id")->With(['User' => function ($query) {
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
            $gall = ServiceGallary::Select("image")->Where([["user_id", $service["id"]]])->pluck("image");
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
        $services = User::Where([["active", "=", true], ["category", "=", $category_id]])->get();
         foreach ($services as $key => $service ) {
            $id = $service["id"];
            // $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
            // // return response()->json($gall);
            // $services[$key]["gallary"] = $gall;
            // $orders = Order::Where([["service_id", "=", $service["id"]]])->count();
            // $services[$key]["orders"] = $orders;

            $orders = Order::Where([["service_id", "=", $id]])->count();
            $ratings = Rating::With(['User' => function ($query) {
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
            $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
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
            $rating = Rating::create($validation);
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
            'location' => 'string|required',
            'service_id' => 'integer|required|exists:users,id',
        ]);
        $service = User::Where("id", "=", $validation["service_id"])->first();
        $validation["price"] = $service["cost_per_hour"] * $validation["total_hours"];
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

        $orders = Order::Where( [["user_id", "=", $user_id], ["status", ">", "0"]])->get();
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

        $orders = Order::Where( [["service_id", "=", $user_id], ["status", ">", "0"]])->get();
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
            'price' => 'numeric',
            'note' => 'string',
            'location' => 'string',
            'order_id' => 'integer|required|exists:order,id',
        ]);
        $user_id = auth("api")->user()->id;

        if ($validation) {
            $order = Order::find($validation["order_id"])->get();
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

}

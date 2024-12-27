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
            // $services =DB::table("users")
            //             ->leftJoin("order", 'order.service_id', "=", 'users.id')
            //             ->selectRaw("users.*, count(`order`.id) total")
            //             ->groupBy('users.id')
            //             ->orderBy('total','desc')
            //             ->get();
            $services = User::With("Rating")->Where([["active", "=", true], ["service", "LIKE", "%$query%"]])->get()->sort(
            function($a, $b) {
                return $a <=> $b;
            }
            );
        }else{
            $services = User::With("Rating")->Where([["active", "=", true], ["service", "LIKE", "%$query%"]])->get();
        }
        
        // dd($services);
        foreach ($services as $key => $service ) {
            $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
            // return response()->json($gall);
            $services[$key]["gallary"] = $gall;

             $ratings = 0;
                $times_rated = 0;
                foreach ($service["rating"] as $key2 => $rating) {
                    $times_rated++;
                    $ratings += $rating["rate"];
                }
                $ratings_object = ["rating" => $ratings / $times_rated, "times_rated" => $times_rated];
                $services[$key]["ratings"] = $ratings_object;
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
        $service = User::Where("id", "=", $id)->first();
        $ratings = Rating::With(['user' => function ($query) {
            $query->select('id', 'full_name', "picture");
        }])->whereBelongsTo($service)->get();
        $service["rating"] = $ratings;
        $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
        // return response()->json($gall);
        $service["gallary"] = $gall;
        return response()->json($service);
    }

    /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_category_services( Request $request, $category_id)
    {
        $services = User::With("Rating")->Where([["active", "=", true], ["category", "=", $category_id]])->get();
         foreach ($services as $key => $service ) {
            $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
            // return response()->json($gall);
            $services[$key]["gallary"] = $gall;

             $ratings = 0;
                $times_rated = 0;
                foreach ($service["rating"] as $key2 => $rating) {
                    $times_rated++;
                    $ratings += $rating["rate"];
                }
                $ratings_object = ["rating" => $ratings / $times_rated, "times_rated" => $times_rated];
                $services[$key]["ratings"] = $ratings_object;
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

}

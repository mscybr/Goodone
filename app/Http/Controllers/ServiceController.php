<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
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
        if(isset($request->filter)){
            // $services =DB::table("users")
            //             ->leftJoin("order", 'order.service_id', "=", 'users.id')
            //             ->selectRaw("users.*, count(`order`.id) total")
            //             ->groupBy('users.id')
            //             ->orderBy('total','desc')
            //             ->get();
            $services = User::Where("active", "=", true)->get()->sort(
            function($a, $b) {
                return $a <=> $b;
            }
            );
        }else{
            $services = User::Where("active", "=", true)->get();
        }
        // dd($services);
        foreach ($services as $key => $service ) {
            $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
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
        $services = User::Where("id", "=", $id)->get();
        foreach ($services as $key => $service ) {
            $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
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
    public function get_category_services( Request $request, $category_id)
    {
        $services = User::Where([["active", "=", true], ["category", "=", $category_id]])->get();
         foreach ($services as $key => $service ) {
            $gall = ServiceGallary::Where([["user_id", $service["id"]]])->get();
            // return response()->json($gall);
            $services[$key]["gallary"] = $gall;
        }
        return response()->json($services);
    }
}

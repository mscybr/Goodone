<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ServiceGallary;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use App\Models\AppSetting;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', "register"]]);
    }


    /**
     * edit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( Request $request)
    {
        $validation = $request->validate([
            'email' => 'email|unique:users,email',
            'password' => 'string',
            'phone' => 'numeric',
            // 'type' => 'in:customer,worker',
            'location' => 'string',
            'city' => 'string',
            'country' => 'string',
            'full_name' => 'string',
            "picture" => "file",
        ]);
        if(isset( $validation["password"] )) $validation["password"] = bcrypt($validation["password"]);
        $validation["id"] = auth("api")->user()->id;
        if($request->file('picture')){
            $file = $request->file('picture');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["picture"] = $file_name;
        }

        if ($validation) {

            User::where([["id", "=", auth("api")->user()->id]])->update($validation);
            $updated = Auth("api")->user()->fresh();
            return response()->json($updated);

        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

    /**
     * register
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register( Request $request)
    {
        $validation = $request->validate([
            'email' => 'email|required|unique:users,email',
            'password' => 'required',
            'phone' => 'required|numeric',
            'type' => 'required|in:customer,worker',
            'full_name' => 'required',
            'city' => 'string',
            'country' => 'string',
            "device_token" => "required",
            "picture" => "file|sometimes",
        ]);
         if(isset( $validation["password"] )) $validation["password"] = bcrypt($validation["password"]);
        if($request->file('picture')){
            $file = $request->file('picture');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["picture"] = $file_name;
        }else{
            $customer = AppSetting::Where("key", "=", "customer-image");
            $provider = AppSetting::Where("key", "=", "provider-image");
            $customer_image = "";
            $provider_image = "";
            if($customer->count() > 0){$customer_image = $customer->first()->value;}
            if($provider->count() > 0){$provider_image = $provider->first()->value;}
            if($validation["type"] == "worker") $validation["picture"] = $provider_image;
            if($validation["type"] != "worker") $validation["picture"] = $customer_image;
        
        }


        if ($validation) {
            $user = User::create($validation);
            return $this->respondWithToken(auth("api")->login($user));
        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login( )
    {
        $user = User::Where("email", "=", $credentials["email"]);
        if( $user->count() > 0 && $user->first()["active"] == false ) return response()->json(['error' => 'Account is deactivated'], 403);
        $credentials = request(['email', 'password']);

        if (! $token = auth("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user->update(["device_token" => request("device_token")]);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth("api")->user();
        $_active = Service::Where([["user_id", "=", $user["id"]]]);
        $active = false;
        if($_active->count() > 0 ){
            $active = $_active->first()->active;
        }
        $user["active"] = $active;
        unset($user["verified_liscence"]);
        unset($user["location"]);
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth("api")->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth("api")->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL() * 60
        ]);
    }
}
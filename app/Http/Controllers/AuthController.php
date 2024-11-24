<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ServiceGallary;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', "register", "get_gallary"]]);
    }

    /**
     * gallary
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_gallary( Request $request, $user_id)
    {
        $gall = ServiceGallary::Where([["user_id", $user_id]])->get();
        return response()->json($gall);
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
        $user_id = auth("api")->user()->id;
        $validation["user_id"] = $user_id;
        if ($validation) {
            $del = ServiceGallary::Where("user_id", $user_id)->Where("id",$validation["id"])->delete();
            // ddd($del);
            $all = ServiceGallary::Where([["user_id", $user_id]])->get();
            return response()->json($all);

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
    public function add_to_gallary( Request $request)
    {
        $validation = $request->validate([
            "image" => "file|required",
        ]);
        $validation["user_id"] = auth("api")->user()->id;
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
     * edit
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( Request $request)
    {
        $validation = $request->validate([
            'years_of_experience' => "numeric",
            'username' => 'unique:users,username',
            'email' => 'email|unique:users,email',
            'password' => 'string',
            'phone' => 'numeric',
            'type' => 'in:customer,worker',
            'full_name' => 'string',
            'about' => 'string',
            'location' => 'string',
            'cost_per_hour' => 'numeric',
            'service' => 'string',
            "picture" => "file",
            "license" => "file",
            "category" => "exists:categories,id",
            "active" => "boolean"
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

        if($request->file('license')){
            $file = $request->file('license');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["license"] = $file_name;
        }

        if ($validation) {

            User::where('id',auth("api")->user()->id)->update($validation);
            return response()->json(auth("api")->user());

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
            'username' => 'unique:users,username',
            'email' => 'email|required|unique:users,email',
            'password' => 'required',
            'phone' => 'required|numeric',
            'type' => 'required|in:customer,worker',
            'full_name' => 'required',
            'location' => 'string',
            'cost_per_hour' => 'numeric',
            'service' => 'nullable',
            "picture" => "file|required",
            "license" => "file",
        ]);
         if(isset( $validation["password"] )) $validation["password"] = bcrypt($validation["password"]);
        if($request->file('picture')){
            $file = $request->file('picture');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["picture"] = $file_name;
        }

        if($request->file('license')){
            $file = $request->file('license');
            $temp = $file->store('public/images');
            $_array = explode("/", $temp);
            $file_name = $_array[ sizeof($_array) -1 ];
            $validation["license"] = $file_name;
        }

        if ($validation) {
            $user = User::create($validation);
            // $credentials = request(['email', 'password']);
            // $token = auth("api")->attempt($credentials);
            // if (! $token = auth("api")->attempt($credentials)) {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }
            // $token = auth("api")->attempt($validation);
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
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth("api")->user());
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
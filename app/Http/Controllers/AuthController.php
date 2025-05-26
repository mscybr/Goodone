<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ServiceGallary;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use App\Models\AppSetting;
use Twilio\Rest\Client;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', "register", "sendVerificationCode", "verifyAccount"]]);
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
            $token = $this->createOtpCode($user->email);
            $this->sendOtpCode($user->email, $token);
            return response()->json(['message' => 'Successfully created account, Otp code is sent to email']);
            // return $this->respondWithToken(auth("api")->login($user));
        }else{
            $errors = $validator->errors();
            return response()->json(['error' => 'Bad Request', 'details' => $errors], 400);
        }

    }

    public function sendVerificationCode( Request $request){
        $request->validate([
            // 'phone' => 'required|numeric',  // Ensure it's a valid phone number
            'email' => 'required|string',  // Ensure it's a valid phone number
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $token = $this->createOtpCode($request->email);
        $sent_otp = $this->sendOtpCode($user->email, $token);
        // if($sent_otp){

            return response()->json(['message' => 'Otp code sent via email']);
        // }else{
            // return response()->json(['message' => 'Unable to sent Otp Code']);
        // }
    }

        // Verify the reset code and reset the password
    public function verifyAccount(Request $request)
    {
        $request->validate([
            'phone' => 'required_if:email,null|numeric',
            'email' => 'required_if:phone,null',
            'otp' => 'required|numeric',
            // 'password' => 'required',
        ]);

        if($request->phone){
            $user = User::where('phone', $request->phone)->first();
        }else{
            $user = User::where('email', $request->email)->first();
        }

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if token is valid and not expired
        if ($user->reset_token !== $request->otp || $user->reset_token_expiry < now()) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }

        
        $user->update([
            'reset_token' => null, // Clear the reset token
            'reset_token_expiry' => null, // Clear the token expiry
            'verified' => true
        ]);

        return $this->respondWithToken(auth("api")->login($user));
        // return response()->json(['message' => 'Password reset successfully']);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login( )
    {
        $credentials = request(['email', 'password']);
        $user = User::Where("email", "=", $credentials["email"]);
        if( $user->count() > 0 && $user->first()["blocked"] == true ) return response()->json(['error' => 'Account is blocked'], 403);
        if( $user->count() > 0 && $user->first()["verified"] == false ) return response()->json(['error' => 'Account is not verified'], 401);

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
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_account()
    {
        $user = auth("api")->user();
        $user->delete();
        return response()->json([
            "message" => "Successfully deleted account"
        ]);
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

    protected function sendOtpCode($email, $token){
        $this->sendEmail($email, "Your Otp code is: $token");
    }

    protected function createOtpCode($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return 404;
        }

        // Generate a random token for password reset
        $token = rand(100000, 999999);
        $user->update(['reset_token' => $token, 'reset_token_expiry' => now()->addMinutes(5)]);
        return $token;
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

     // Helper function to send SMS using Twilio
    protected function sendSms($to, $message)
    {
        // some sms sending method
        // return response()->json[["to"=>$to, 'message'=>$message]];
        // $sid = env('TWILIO_SID');
        // $auth_token = env('TWILIO_AUTH_TOKEN');
        // $from = env('TWILIO_PHONE_NUMBER');

        // $client = new Client($sid, $auth_token);

        // $client->messages->create(
        //     $to, // To phone number
        //     [
        //         'from' => $from,  // From Twilio phone number
        //         'body' => $message
        //     ]
        // );
    }

    // Helper function to send SMS using Twilio
    protected function sendEmail($to, $message)
    {

        Mail::to($to)->send(new OtpMail($message));
        // some sms sending method
        // return response()->json[["to"=>$to, 'message'=>$message]];
        // $sid = env('TWILIO_SID');
        // $auth_token = env('TWILIO_AUTH_TOKEN');
        // $from = env('TWILIO_PHONE_NUMBER');

        // $client = new Client($sid, $auth_token);

        // $client->messages->create(
        //     $to, // To phone number
        //     [
        //         'from' => $from,  // From Twilio phone number
        //         'body' => $message
        //     ]
        // );
    }
}
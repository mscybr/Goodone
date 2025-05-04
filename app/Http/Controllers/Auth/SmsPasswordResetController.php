<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Twilio\Rest\Client;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class SmsPasswordResetController extends Controller
{
    // Send SMS with reset token
    public function sendResetCode(Request $request)
    {
        $request->validate([
            // 'phone' => 'required|numeric',  // Ensure it's a valid phone number
            'email' => 'required|string',  // Ensure it's a valid phone number
        ]);

        // Get the user by phone number
        // $user = User::where('phone', $request->phone)->first();
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Generate a random token for password reset
        $token = rand(100000, 999999);
        // $token = "000000";

        // Save the token and expiry time in the database or cache (token expiry after 5 minutes for example)
        $user->update(['reset_token' => $token, 'reset_token_expiry' => now()->addMinutes(5)]);

        // Send the token via SMS using Twilio
        // response()->json(['message' => "Your password reset code is: $token"]);
        // $this->sendSms($user->phone, "Your password reset code is: $token");
        $this->sendEmail($user->email, "Your password reset code is: $token");

        return response()->json(['message' => 'Reset code sent via SMS']);
    }

    // Verify the reset code and reset the password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required_if:email,null|numeric',
            'email' => 'required_if:phone,null',
            'reset_token' => 'required|numeric',
            'password' => 'required',
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
        if ($user->reset_token !== $request->reset_token || $user->reset_token_expiry < now()) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }

        
        $user->update([
            'password' => bcrypt($request->password),
            'reset_token' => null, // Clear the reset token
            'reset_token_expiry' => null, // Clear the token expiry
        ]);

        return response()->json(['message' => 'Password reset successfully']);
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

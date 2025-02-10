<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PushNotification extends Controller
{


    public function notify_user(Request $request, $user_id){
        $validation = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $user_query = User::Where("id", "=", $user_id);
        if($user_query->count() > 0){
            $user = $user_query->first();
            $response = $this->sendNotification($user["device_token"], $validation["title"], $validation["body"]);
            return response()->json($response, 200);
        }else{
            return response()->json([], 404);
        }
    }


    protected function sendNotification($deviceToken, $title, $body)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/goodone-73cff/messages:send';
        $accessToken =  $this->generateAccessToken('goodone-73cff-a404a8a9d747.json');
        if($accessToken){

            // Build the notification payload
            $payload = [
                'message' => [
                    'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    ]
            ],
        ];
         $headers = [
             'Authorization: Bearer ' . $accessToken,
             'Content-Type: application/json',
            ];
                    // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            // Execute the request
            $response = curl_exec($ch);
            if ($response === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }

            curl_close($ch);
            return response()->json(["message"=> "sent notification"], 200 );
        }else{
            return response()->json(["message"=> "couldn't send notification, info: $deviceToken, $title, $body "], 500 );
        }
    }

    protected function generateAccessToken($serviceAccountPath) {
        // Read the service account JSON file
         // File path relative to the `storage/app` directory

        // Check if the file exists
        if (Storage::exists($serviceAccountPath)) {
            // Read the file contents
            $_serviceAccount = Storage::get($serviceAccountPath);
            $serviceAccount = json_decode($_serviceAccount, true);

            $header = json_encode([
                'alg' => 'RS256',
                'typ' => 'JWT',
            ]);

            $now = time();
            $claims = json_encode([
                'iss' => $serviceAccount['client_email'], // Issuer
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging', // Scope
                'aud' => 'https://oauth2.googleapis.com/token', // Audience
                'exp' => $now + 3600, // Expiry (1 hour)
                'iat' => $now, // Issued at
            ]);

            // Encode the header and claims
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64UrlClaims = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claims));

            // Sign the JWT
            $signatureInput = $base64UrlHeader . '.' . $base64UrlClaims;
            $signature = '';
            openssl_sign($signatureInput, $signature, $serviceAccount['private_key'], 'SHA256');
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

            // Construct the JWT
            $jwt = $base64UrlHeader . '.' . $base64UrlClaims . '.' . $base64UrlSignature;

            // Exchange the JWT for an access token
            // $response = file_get_contents('https://oauth2.googleapis.com/token', false, stream_context_create([
            //     'http' => [
            //         'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            //         'method'  => 'POST',
            //         'content' => http_build_query([
            //             'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            //             'assertion' => $jwt,
            //         ]),
            //     ],
            // ]));

         $headers = [
             'Content-Type: application/x-www-form-urlencoded',
            ];
                    // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                        'assertion' => $jwt,
                    ]));

            // Execute the request
            $response = curl_exec($ch);

            $tokenInfo = json_decode($response, true);

            if (isset($tokenInfo['access_token'])) {
                return $tokenInfo['access_token'];
            } else {
                dd($response);
                throw new Exception('Failed to obtain access token: ' . $response);
            }
        }else{
            return false;
        }

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageController extends Controller
{

    public function intiate_chat(Request $request)
    {
        $user_id = auth("api")->user()->id;
        $validation = $request->validate([
            "to" => "exists:users,id"
        ]);
        if(Message::Where([["from", "=", $user_id], ["to", "=", $validation["to"]]] )->count() == 0 && Message::Where([["to", "=", $user_id], ["from", "=", $validation["to"]]])->count() == 0 ){

            $validation["from"] = $user_id;
            $validation["latest_message"] = "";

            $response = Message::create($validation);

        }else{
            if(Message::Where([["from", "=", $user_id], ["to", "=", $validation["to"]]] )->count() > 0 ){
                $response = Message::Where([["from", "=", $user_id], ["to", "=", $validation["to"]]] )->first();
                // $response->update(["seen_by_from" => true]);
                $response = [
                    "id" => $response["id"],
                    "new_messages" => $response["seen_by_from"] == false,
                    "latest_message" => $response["latest_message"]
                ];
            }else{
                $response = Message::Where([["to", "=", $user_id], ["from", "=", $validation["to"]]])->first();
                $response = [
                    "id" => $response["id"],
                    "new_messages" => $response["seen_by_to"] == false,
                    "latest_message" => $response["latest_message"]
                ];
                // $response->update(["seen_by_to" => true]);
            }
        }
        return response()->json($response);
    }

    public function get_chats(Request $request)
    {
        $user_id = auth("api")->user()->id;
        $chats = [];
        $from_chats = Message::Where("from", "=", $user_id);
        $to_chats = Message::Where("to", "=", $user_id);
        foreach ($from_chats->get() as $_msg ) {
            $user = User::Select("email", "full_name", "picture")->Where( "id", "=", $_msg["from"])->first();
            $chats[] = [
                "id" => $_msg["id"],
                "from" => $user,
                "new_messages" => $_msg["seen_by_from"]  == false,
                "latest_message" =>$_msg["latest_message"],
                "time" => Carbon::parse($_msg->sent_at)->diffForHumans()
            ];
        }
        foreach ($to_chats->get() as $_msg ) {
             $user = User::Where( "id", "=", $_msg["to"])->first();
            $chats[] = [
                "id" => $_msg["id"],
                "from" => $user,
                "new_messages" => $_msg["seen_by_to"] == false,
                "latest_message" =>$_msg["latest_message"],
                "time" => Carbon::parse($_msg->sent_at)->diffForHumans()
            ];
        }
        $from_chats->update(["seen_by_from" => true]);
        $to_chats->update(["seen_by_to" => true]);

        return response()->json($chats);
    }



     public function update_chat(Request $request)
    {
        $validation = $request->validate([
            "from" => "exists:users,id",
            "to" => "exists:users,id",
            "latest_message" => "string",
        ]);
        // if(Message::Where("from", "=", $user_id )->count() == 0 && Message::Where("to", "=", $user_id )->count() == 0 ){

        //     $user_id = auth("api")->user()->id;
        
        //     $validation["from"] = $user_id;
        //     $validation["latest_message"] = "";

        //     $response = Message::create($validation);

        // }else{
            // if(Message::Where("from", "=", $user_id )->count() > 0 ){
            //     $response = Message::Where("from", "=", $user_id )->get();
            // }else{
            //     $response = Message::Where("to", "=", $user_id )->get();
            // }
        // }

            if(Message::Where("from", "=", $validation["from"] )->count() > 0 ){
                $message = Message::Where("from", "=", $validation["from"] );
                $message->update([
                    "latest_message" => $validation["latest_message"],
                    "new_message_from" => true,
                    "seen_by_from" => true,
                    "seen_by_to" => false,
                    "sent_at" => time()
                ]);
            }else{
                $message = Message::Where("to", "=", $validation["from"] );
                  $message->update([
                    "latest_message" => $validation["latest_message"],
                    "new_message_to" => true,
                    "seen_by_to" => true,
                    "seen_by_from" => false,
                     "sent_at" => time()
                ]);
            }

        return response()->json($message->get());
    }
}

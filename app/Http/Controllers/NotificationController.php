<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

use function PHPSTORM_META\type;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if(!$token)
        {
            return response()->json(['message' => "unAuth"],401);
        }
        $user = $token->tokenable;
        if(!$user)
        {
            return response()->json(['message' => "unAuth"],401);
        }
        $notifications = $user->notifications;
        foreach($notifications as $notification){
            $userNameSender= $notification->sender->firstname . " " .$notification->sender->lastname;
            $profile_image_sender = $notification->profile_image;
            unset($notification['sender']);
            $notification['sender'] = $userNameSender;
            $notification['profile_image'] = $profile_image_sender;
            $dataInfo = json_decode($notification['data']);
            $notification['message'] = $dataInfo->message;
            $notification['post_id'] = $dataInfo->post_id;
            unset($notification['type']);
            unset($notification['data']);
            
        }
        return response()->json(['data'=>$notifications]);
    }
    public function markAsRead(Request $request,Notification $notification)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if(!$token)
        {
            return response()->json(['message' => "unAuth"],401);
        }
        $user = $token->tokenable;
        if(!$user)
        {
            return response()->json(['message' => "unAuth"],401);
        }
        $notification->markAsRead();
         return response()->json(['message' => 'notif read succesfully']);
    }
    public function markAllAsRead(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if(!$token)
        {
            return response()->json(['message' => "unAuth"],401);
        }
        $user = $token->tokenable;
        if(!$user)
        {
            return response()->json(['message' => "unAuth"],401);
        }
        $user->notifications->scopeUnread->markAsRead();
        return response()->json(['message' => 'notifs read successfully']);
    }

//     public function handleNotificationMessage(Notification $notification)
//     {
//         if($notification->type == 'like')
//         {
//
//         }
//
//     }

}

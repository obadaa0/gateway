<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
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
        $notifications = $user->notifications->sortByDesc('created_at');
        foreach($notifications as $notification){
            $userNameSender= $notification->sender->firstname . " " .$notification->sender->lastname;
            $profile_image_sender = $notification->profile_image;
            unset($notification['sender']);
            $notification['sender'] = $userNameSender;
            $notification['profile_image'] = $profile_image_sender;
            $dataInfo = json_decode($notification['data']);
            $notification['message'] = $dataInfo->message;
            if($notification->type == "friend_request" || $notification->type == 'accept_friend_request')
            {
                $notification['post_id'] = 0;
            }
            else{
                $notification['post_id'] = $dataInfo->post_id;
            }
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
         $user->notifications()->where('is_read', false)->update(['is_read' => true]);
        return response()->json(['message' => 'notifs read successfully']);
    }

    public function numberOfNotification(Request $request)
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
        $user->loadCount([
            'notifications as notifications'
        ]);
        return response()->json(['data'=>['notifications_number' => $user->notifications]]);
    }
}

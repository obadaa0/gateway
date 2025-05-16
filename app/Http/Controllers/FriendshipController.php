<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Services\NotificationService;
class FriendshipController extends Controller
{
    private $NotificationService;
    public function __construct(NotificationService $NotificationService)
    {
        $this->NotificationService = $NotificationService;
    }
    public function sendRequest(Request $request, User $friend)
{
    $token = PersonalAccessToken::findToken($request->bearerToken());
    if (!$token) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $user = $token->tokenable;
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    if ($user->id === $friend->id) {
        return response()->json(['message' => 'Cannot send request to yourself'], 422);
    }
    $existingRequest = Friend::where([
        ['user_id', $user->id],
        ['friend_id', $friend->id],
    ])->orWhere([
        ['user_id', $friend->id],
        ['friend_id', $user->id],
    ])->first();
    if ($existingRequest) {
        return response()->json(['message' => 'Friend request already exists'], 422);
    }
    $friendCreate = Friend::create([
        'user_id' => $user->id,
        'friend_id' => $friend->id,
        'status' => 'pending'
    ]);
    $this->NotificationService->sendFriendRequestNotification($user,$friend);
    return response()->json([
        'message' => 'Friend request sent successfully',
        'data' => $friendCreate
    ], 201);
}
    public function acceptRequest(Request $request, Friend $friend)
    {
        if (!$friend) {
            return response()->json(['message' => 'Friend request not found'], 404);
        }
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $user = $token->tokenable;

        if ($user->id !== $friend->friend_id) {
            return response()->json(['message' => 'Unauthorized to accept this request'], 403);
        }
        $friend->update(['status' => 'accepted']);
        return response()->json([
            'message' => 'Friend request accepted successfully',
            'friendship' => $friend
        ]);
    }
    public function rejectRequest(Request $request,Friend $friend)
{
    if (!$friend) {
        return response()->json(['message' => 'request not found'], 404);
    }
    $token = PersonalAccessToken::findToken($request->bearerToken());
    $user = $token->tokenable;
    if ($user->id !== $friend->friend_id) {
        return response()->json(['message' => 'unAuth'], 403);
    }
    if ($friend->status === 'accepted') {
        return response()->json(['message' => 'cannot reject this request'], 400);
    }
    $friend->update(['status' => 'rejected']);

    return response()->json([
        'message' => 'reject successfully',
        'friendship' => $friend
    ]);
}
    public function getFriendList(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if (!$token) {
            return response()->json(['message' => 'unAuth'], 401);
        }
        $user = $token->tokenable;
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }
        $friends = $user->friends()->get();
        return response()->json(['friends' => $friends]);
    }
    public function getPendingRequest(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if (!$token) {
            return response()->json(['message' => 'unAuth'], 401);
        }
        $user = $token->tokenable;
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }
        $requests = Friend::with('sender')
        ->where('friend_id',$user->id)
        ->where('status','pending')
        ->get();
        return response()->json(['requests' => $requests->pluck('sender')]);
    }
    public function isFriend(Friend $friend,Request $request)
    {
    $token = PersonalAccessToken::findToken($request->bearerToken());
    if (!$token) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    $user = $token->tokenable;
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    $existingRequest = Friend::where([
        ['user_id', $user->id],
        ['friend_id', $friend->id],
    ])->orWhere([
        ['user_id', $friend->id],
        ['friend_id', $user->id],
    ])->first()->exists();

    if ($existingRequest) {
        $isFriend = $existingRequest;
        return response()->json(['data' =>$isFriend ]);
    }
    }
    public function removeRequest(Request $request, User $friend)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
    if (!$token) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    $user = $token->tokenable;
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    if ($user->id === $friend->id) {
        return response()->json(['message' => 'Cannot send request to yourself'], 422);
    }
    $existingRequest = Friend::where([
        ['user_id', $user->id],
        ['friend_id', $friend->id],
    ])->orWhere([
        ['user_id', $friend->id],
        ['friend_id', $user->id],
    ])->where('status','pending')->first();
    if ($existingRequest) {
        $existingRequest->delete();
        return response()->json(['message' => 'Friend request deleted successfully']);
    }
    return response()->json(['message' => 'request not found'],404);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Services\NotificationService;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class CommentController extends Controller
{
    private $NotificationService;
    public function __construct(NotificationService $NotificationService)
    {
        $this->NotificationService = $NotificationService;
    }
    public function addComment(Request $request,Post $post)
{
    try{
        $validate = $request->validate([
            'comment' => 'required|string|max:1000'
        ]);
    }catch(ValidationException $e)
    {
        return response()->json(['message' => $e],422);
    }
    $token = PersonalAccessToken::findToken($request->bearerToken());
    if(!$token)
    {
        return response()->json(['message' => 'unAuth'],401);
    }
    $user = $token->tokenable;
    if(!$user)
    {
        return response()->json(['message' => 'unAuth'],401);
    }
    $comment = $post->comment()->create([
        'user_id' => $user->id,
        'comment' => $request->comment
    ]);
    $comment['username'] = $user->firstname . ' ' .$user->lastname;
    $comment['profile_image'] = $user->profile_image;
    if($user->id != $post->User->id){
        $this->NotificationService->sendCommentNotification($user,$post->User,$post->id,$comment);
    }
    return response()->json([
        'message' => 'comment add successfully',
        'data' => $comment
    ],201);
}

    public function deleteComment(Comment $comment,Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if(!$token)
        {
            return response()->json(['message' => 'unAuth'],401);
        }
        $user = $token->tokenable;
        if(!$user)
        {
            return response()->json(['message' => 'unAuth'],401);
        }
        if($comment->id != $user->id)
        {
            return response()->json([
                'message' => "can't delete this comment"
            ], 403);
        }

        $comment->delete();
        return response()->json([

            'message' => 'delete comment successfully !'
        ]);
    }

    public function getAllCommentsPost(Post $post,Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if(!$token)
        {
            return response()->json(['message' => 'unAuth'],401);
        }
        $user = $token->tokenable;
        if(!$user)
        {
            return response()->json(['message' => 'unAuth'],401);
        }
        $comments = $post->comment;
        if(!$comments)
        {
            return response()->json(['message' => 'no comment yet']);
        }
        foreach($comments as $comment)
        {
            if($comment['user_id'] == $user->id)
            {
                $comment['flag'] = true;
            }else{
                $comment['flag'] = false;
            }
            $comment['username'] = $this->getusername($comment['user_id']);
            $comment['profile_image'] = $this->getImageProfile($comment['user_id']);
        }
        return response()->json(['data' => $comments]);
    }
    public function getusername($userid)
    {
        $user = User::find($userid);
        if(!$user)
        {
            return;
        }
        return $user->firstname . ' ' .$user->lastname;
    }
    public function getImageProfile($userid)
    {
        $user = User::find($userid);
        if(!$user)
        {
            return;
        }
        return $user->profile_image ? $user->profile_image : "";
    }

}

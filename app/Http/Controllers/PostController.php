<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\PostReaction;
use App\Models\User;
use Doctrine\Common\Lexer\Token;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'content' => 'required|string',
                'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi',
            ]);
            $path = null;
            if ($request->hasFile('media')) {
                $image = $request->file('media');
                $imageName = Str::random(20) . '.' . $image->getClientOriginalExtension();

                $directory = public_path('storage/posts');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $image->move($directory, $imageName);
                $path = asset('storage/posts/' . $imageName);
               }
            $token=PersonalAccessToken::findToken($request->bearerToken());
            if(!$token)
            {
                return response()->json([
                    'message' => 'unAuth'
                ]);
            }
            $user=$token->tokenable;
            if(!$user){
                return response()->json([
                    'message' => 'user not found !'
                ]);
            }
            $post=Post::create([
                'user_id' =>$user->id,
                'content' => $request['content'],
                'media' => $path
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function delete(Request $request,Post $post)
    {
        $token=PersonalAccessToken::findToken($request->bearerToken());
        if(!$token)
        {
            return response()->json([
                'message' => 'unAuth'
            ]);
        }
        $user=$token->tokenable;
        if(!$user){
            return response()->json([
                'message' => 'user not found !'
            ]);
        }
        if(!$post){
            return response()->json([
                'message' => 'post not found !'
            ],404);
        }
        $post=$post->where('user_id',$user->id);
        if(!$post)
        {
            return response()->json([
                'message' => 'post not found !'
            ],404);
        }
        if($post->delete()){
            return response()->json([
                'mesasge' => 'post deleted'
            ],200);
        }
        return response()->json([
            'message' => ' Error'
        ],501);
    }
    public function getPosts(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if (!$token) {
            return response()->json(['message' => 'unAuth'], 401);
        }
        $user = $token->tokenable;
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }
        $posts=$user->posts;
        if(!$posts)
        {
            return response()->json(['message' => "can't find any post"],200);
        }
        // $posts->eac
        foreach($posts as $post)
        {
                    $hasLiked = $post->reactions()
            ->where('reaction_type', 'like')
            ->where('user_id', $user->id)
            ->exists();
            $post->has_liked = $hasLiked;
            $post->loadCount([
                'reactions as likes' => function ($q) {
                    $q->where('reaction_type', 'like');
                }
            ]);
            $post->loadCount([
                'comment as comments'
            ]);
            $post['user_name'] = $user->firstname.' '.$user->lastname;
            $post['profile_image'] = $user->profile_image;
            if($post->media == null)
            {
                $post->media = "";
            }
        }
        return response()->json(['message' => 'Successfully response','data'=>
        [
        'posts' => $posts
        ] ],200);
    }

    public function getAllPost(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        if (!$token) {
            return response()->json(['message' => 'unAuth'], 401);
        }
        $user = $token->tokenable;
        if (!$user) {
            return response()->json(['message' => 'user not found'], 404);
        }
        $posts=Post::inRandomOrder()->get();
        if(!$posts)
        {
            return response()->json(['message' => "can't find any post"],204);
        }
        $userPost = [];
        $otherPost = [];
        $filterPost = [];
        foreach($posts as $post)
        {
        $isFriend = Friend::where([
        ['user_id', $user->id],
        ['friend_id', $post->user_id],
    ])->orWhere([
        ['user_id', $post->user_id],
        ['friend_id', $user->id],
    ])->exists();
    $post['is_friend'] = $isFriend;
            if($post->user_id == $user->id)
            {
                if($post->created_at->isToday())
                {
                    $post['his_post'] = true;
                    $hasLiked = $post->reactions()
                ->where('reaction_type', 'like')
                ->where('user_id', $user->id)
                ->exists();
                $post->has_liked = $hasLiked;
                $post->loadCount([
                    'reactions as likes'
            ]);
            $post->loadCount([
                'comment as comments'
            ]);
            $post['user_name'] = $user->firstname . ' ' . $user->lastname;
            $post['profile_image'] = $user->profile_image;
                    $userPost[] =$post;
                }
                continue;
            }
            $hasLiked = $post->reactions()
            ->where('reaction_type', 'like')
            ->where('user_id', $user->id)
            ->exists();
            $post->has_liked = $hasLiked;
            $post->loadCount([
                'reactions as likes'
            ]);
            $post->loadCount([
                'comment as comments'
            ]);
            $post['user_name'] = $post->User->firstname . ' ' . $post->User->lastname;
            $post['profile_image'] = $post->user->profile_image;
            $otherPost[] = $post;
        }
        $filterPost =array_merge($userPost,$otherPost);
        return response()->json(['message' => 'Successfully response','data'=>
        [
        'posts' => $filterPost
        ] ],200);
    }
}

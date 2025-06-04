<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Helpers\MediaHelper;
use App\Models\Friend;
use App\Models\User;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function create(Request $request)
    {
        try{
            $validate=$request->validate([
                'firstname' => 'string',
                'lastname' => 'string',
                'email' => 'email|required',
                'birthday' => 'date|required',
                'gender' => 'required|in:male,female',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
                'phone' => 'required|digits:10',
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        $userEx=User::where('email',$request['email'])->exists();
        if($userEx)
        {
            return response()->json(['message' => 'User has been exist'],400);
        }
        $user=User::create($validate);
        return response()->json(['data' => ['userId' => $user->id],200]);
    }
    public function login(Request $request)
    {
        try{
            $valide=$request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
        }
        catch(\Illuminate\Validation\ValidationException $e)
        {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        $user=User::
        where('email',$request['email'])
        ->where('block',false)
        ->first();
        if(!$user)
        {
            return response()->json(['data' =>'user not found'],404);
        }
        if(!Hash::check($valide['password'],$user->password))
        {
            return response()->json(['data' =>'Not correct password'],401);
        }
        $token=$user->createToken('auth_token')->plainTextToken;
        return response()->json(['data' =>['token' => $token]],200);
    }
    public function editProfile(Request $request)
    {
        try
        {
            $validData= $request->validate([
                'image' => 'required|file|mimes:jpeg,png,jpg',
                'bio' => 'required|string'
            ]);
        }
            catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json(['message' =>$e->errors()]);
            }
            $user = AuthHelper::getUserFromToken($request);
            if(!$user){
                return response()->json([
                    'message' => 'user not found !'
                ],404);
            }
            $path = MediaHelper::StoreMedia('profileImage',$request,'image');
            $addProfileImage=User::find($user->id)->update([
                'profile_image' => $path,
                'bio' =>$request->input('bio')
            ]);
            return response()->json(['message' => 'Image Profile update successfully'],200);
    }
    public function addBio(Request $request)
    {
            $user=AuthHelper::getUserFromToken($request);
            if(!$user){
                return response()->json([
                    'message' => 'user not found !'
                ],404);
            }
             User::find($user->id)->update([
                'bio' => $request['text']
            ]);
            return response()->json(['message' => 'Bio added successfully'],200);
    }
    public function show(Request $request)
    {
        $user =AuthHelper::getUserFromToken($request);
        if(!$user)
        {
            return response()->json(['message' => "unAuth"],401);
        }
        $user->loadCount(['friends as friends']);
        $user->loadCount(['posts as posts']);
        return response()->json(['data' => $user]);
    }
    public function showpost(User $user, Request $request)
    {
    $user1 =AuthHelper::getUserFromToken($request);
    if (!$user1) {
        return response()->json(['message' => 'user not found'], 404);
    }
    $posts = $user->posts()->withCount([
        'reactions as likes',
        'comment as comments'
    ])->get();
    if ($posts->isEmpty()) {
        return response()->json(['message' => "can't find any post"], 204);
    }
    foreach ($posts as $post) {
        $isFriend = Friend::where([
            ['user_id', $user1->id],
            ['friend_id', $post->user_id],
        ])->orWhere([
            ['user_id', $post->user_id],
            ['friend_id', $user1->id],
        ])->exists();
        $hasLiked = $post->reactions()
            ->where('reaction_type', 'like')
            ->where('user_id', $user1->id)
            ->exists();
        $post->setAttribute('is_friend', $isFriend);
        $post->setAttribute('has_liked', $hasLiked);
        $post->setAttribute('user_name', $user->firstname . ' ' . $user->lastname);
        $post->setAttribute('profile_image', $user->profile_image);
        unset($post->user);
    }

    return response()->json([
        'message' => 'Successfully response',
        'data' => [
                'posts' => $posts,
        ]
    ], 200);
}
    public function showprofile(User $user,Request $request)
    {
        $token = AuthHelper::getUserFromToken($request);
        if(!$token)
        {
            return response()->json(['message' => 'UnAuth'],401);
        }
         $friendsCount = $user->all_friends_count;
         $user['friends'] = $friendsCount;
        $user->loadCount(['posts as posts']);
        return response()->json(['data' => $user]);
    }

    public function getPolice(Request $request)
    {
        $polices = User::where('role','police')->paginate(10);
        return response()->json(['data' => $polices]);
    }
    public function getUsers(Request $request)
    {
        $users = User::where('role','user')->paginate(10);
        return response()->json(['data' => $users]);
    }
    public function blockUser(User $user)
    {
        $user->block();
        return "user block suc";
    }
    public function UnblockUser(User $user)
    {
        $user->Unblock();
        return "removed block";
    }

}


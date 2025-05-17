<?php

namespace App\Http\Controllers;

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
        $user=User::where('email',$request['email'])->first();
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
            $token=PersonalAccessToken::findToken($request->bearerToken());
            if(!$token)
            {
                return response()->json([
                    'message' => 'unAuth'
                ],401);
            }
            $user=$token->tokenable;
            if(!$user){
                return response()->json([
                    'message' => 'user not found !'
                ],404);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $directory = public_path('storage/profileImage');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $image->move($directory, $imageName);
                $path = asset('storage/profileImage/' . $imageName);
            }
            $addProfileImage=User::find($user->id)->update([
                'profile_image' => $path,
                'bio' =>$request->input('bio')
            ]);
            return response()->json(['message' => 'Image Profile added successfully'],200);
    }
    public function addBio(Request $request)
    {
        $token=PersonalAccessToken::findToken($request->bearerToken());
            if(!$token)
            {
                return response()->json([
                    'message' => 'unAuth'
                ],401);
            }
            $user=$token->tokenable;
            if(!$user){
                return response()->json([
                    'message' => 'user not found !'
                ],404);
            }
            $addBio = User::find($user->id)->update([
                'bio' => $request['text']
            ]);
            return response()->json(['message' => 'Bio added successfully'],200);
    }
    public function show(Request $request)
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
        $user->loadCount(['friends as friends']);
        $user->loadCount(['posts as posts']);
        return response()->json(['data' => $user]);
    }
    public function showProfile(User $user)
    {
        return $user;
    }

}


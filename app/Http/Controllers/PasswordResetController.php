<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class PasswordResetController extends Controller
{
//     public function sendCode(Request $request)
//     {
//
//         $user =User:: ;
//         $code = rand(100000, 999999);
//         PasswordReset::create([
//             'user_id' => $user_id,
//             'code' => $code,
//             'expires_at' => Carbon::now()->addMinutes(10),
//         ]);
//         Mail::raw("Your verification code is: $code", function ($message) use ($user) {
//             $message->to($user->email)->subject('Password Reset Code');
//         });
//         return response()->json(['message' => 'Verification code sent.']);
//     }
//     public function resetPassword(Request $request)
// {
//     $request->validate([
//         'email' => 'required|email',
//         'code' => 'required|string',
//         'password' => 'required|confirmed|min:6',
//     ]);
//     try {
//         $user = User::where('email', $request['email'])->firstOrFail();
//     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
//         return response()->json(['message' => 'User not found!'], 400);
//     }
//     $reset = PasswordReset::where('user_id', $user->id)
//         ->where('code', $request['code'])
//         ->where('used', false)
//         ->where('expires_at', '>', now())
//         ->first();
//     if (!$reset) {
//         return response()->json(['message' => 'Invalid or expired code.'], 400);
//     }
//     $user->update(['password' => bcrypt($request['password'])]);
//     $reset->update(['used' => true]);
//     return response()->json(['message' => 'Password has been reset successfully.']);
// }
    public function checkEmail(Request $request)
    {
        try{

            $validData = $request->validate([
                'email' => 'required|email'
            ]);
        }catch(ValidationException $e){
            return response()->json(['message'=> $e->errors()]);
        }
        $user = User::where('email',$validData['email'])->first();
        if(!$user){
            return response()->json(['message' => 'User Not Found !'],404);
        }
        return response()->json(['message' =>"found User successfully"],200);
    }




    public function editPasswordInProfile(Request $request)
    {
        try{

            $validData = $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);
        }catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' =>$e->errors() ]);
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
        if(!Hash::check($validData['old_password'],$user->password))
        {
            return response()->json(['message' => 'Not correct password'],401);
        }
        $user->update(['password' => bcrypt($request['password'])]);
        return response()->json(['message' => 'update password successfully']);
    }
}

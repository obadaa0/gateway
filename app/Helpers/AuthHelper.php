<?php
namespace App\Helpers;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;
class AuthHelper
{
    public static function getUserFromToken(Request $request)
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());

        if (!$token || !$token->tokenable) {
            return null;
        }
        return $token->tokenable;
    }
public static function checkUserRole(Request $request, string|array $roles): bool
{
    $user = self::getUserFromToken($request);
    if (!$user) {
        return false;
    }
    $userRole = $user->role ?? null;
    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }
    return $userRole === $roles;
}
}

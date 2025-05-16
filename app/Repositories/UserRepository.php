<?php

namespace App\Repositories;
use App\Models\User;


class UserRepository
{
    public function getEmail(User $user)
    {
        return $user->email ?: null;
    }
    
}

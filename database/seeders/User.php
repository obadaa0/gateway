<?php

namespace Database\Seeders;

use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsUser::create([
            'firstname' => 'police',
            'lastname' => 'police',
            'email' => 'police@gmail.com',
            'birthday' => '2000/1/1',
            'gender' => 'male',
            'password' => 'police@1234',
            'phone' => '123',
            'role' =>  'police'
        ]);
    }
    //php artisan db:seed --class=User
}

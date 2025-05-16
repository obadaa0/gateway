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
            'firstname' => 'garuo',
            'lastname' => 'garuo',
            'email' => 'garuo@gmail.com',
            'birthday' => '2000/1/1',
            'gender' => 'male',
            'password' => '12345678',
            'phone' => '123',
            'role' =>  'police'
        ]);
    }
    //php artisan db:seed --class=User
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'hamza',
            'lastname' => 'saad aldeen',
            'father_name' => "adasd",
            'mother_name' => 'dasdas',
            'email' => 'hamzasaadaldeen20022@gmail.com',
            'birthday' => '2002/7/17',
            'gender' => 'male',
            'password' => '12345678',
            'phone' => '0954649881',
            'role' =>  'admin',
            'education' => "Software Ing",
            'live' => 'Damascus',
            'work' => 'software',
            'national_number' => '1'
        ]);
        User::create([
            'firstname' => 'obada',
            'lastname' => 'sabbagh',
            'father_name' => "adasd",
            'mother_name' => 'dasdas',
            'email' => 'obadasabbagh@gmail.com',
            'birthday' => '2002/7/17',
            'gender' => 'male',
            'password' => '12345678',
            'phone' => '0954649881',
            'role' =>  'police',
            'education' => "Software Ing",
            'live' => 'Damascus',
            'work' => 'software',
            'national_number' => '2'
        ]);
    }
}

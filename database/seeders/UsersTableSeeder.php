<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = [
            'name' => "Administrator",
            'email' => "admin@gmail.com",
            'user_name' => "admin",
            'user_role' => "ADMIN",
            'password' => bcrypt('password@123'),
            'registered_at' => date("Y-m-d H:i:s")
        ];
        User::create($user);
    }
}

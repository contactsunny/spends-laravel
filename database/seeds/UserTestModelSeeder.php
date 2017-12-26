<?php

use App\Models\User;
use Illuminate\Database\Seeder;  
//use Illuminate\Database\Eloquent\Model;

class UserTestModelSeeder extends Seeder {

	public function run() {
        
        $config = app()->make('config');

        \DB::table('users')->delete();

        User::create([
            'id' => '$2y$10$1rmEA5P6OuPLeBzSRchFFuIBbBTWhhWBYgJRVPeOHMRrI5hnQY5u',
        	'name' => 'Sunny',
        	'email' => 'sunny.3mysore@gmail.com',
        	'password' => \Hash::make('testing123'),
        	'user_group' => 1,
    	]);
    }
}
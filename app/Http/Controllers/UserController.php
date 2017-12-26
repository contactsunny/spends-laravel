<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends  Controller {

    public function showProfile(Request $request) {

        return view('users/profile', [
            'title' => 'Profile',
        ]);
    }

    public function showUsers(Request $request) {

    	return view('users/index', [
    		'title' => 'Users',
		]);
    }
}
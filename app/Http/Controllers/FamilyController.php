<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FamilyController extends  Controller {

    protected function showFamilies(Request $request) {

    	return view('families/index', [
    		'title' => 'Families',
		]);
    }

    protected function showMyFamilies(Request $request) {

    	return view('families/my_families', [
    		'title' => 'My Families',
		]);
    }
}
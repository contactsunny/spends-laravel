<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncomeController extends Controller {

	public function index(Request $request) {

		return view('income/index', [
			'title' => 'Income'
		]);
	}
}
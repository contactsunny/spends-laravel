<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncomeType;
use App\Core\CustomResponse;

class IncomeTypeController extends Controller {

	protected function index(Request $request) {

		$incomeTypes = IncomeType::all();

		return CustomResponse::success([
			'message' => 'Income Types listed successfully!',
			'data' => [
				'incomeTypes' => $incomeTypes,
			],
		]);
	}
}
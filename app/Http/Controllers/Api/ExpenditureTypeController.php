<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenditureType;
use App\Core\CustomResponse;

class ExpenditureTypeController extends Controller {

	protected function index(Request $request) {

		$expenditureTypes = ExpenditureType::all();

		return CustomResponse::success([
			'message' => 'Expenditure Types listed successfully!',
			'data' => [
				'expenditureTypes' => $expenditureTypes,
			],
		]);
	}
}
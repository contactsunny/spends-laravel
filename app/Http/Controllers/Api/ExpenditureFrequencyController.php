<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenditureFrequency;
use App\Core\CustomResponse;

class ExpenditureFrequencyController extends Controller {

	protected function index(Request $request) {

		$expenditureFrequencies = ExpenditureFrequency::all();

		return CustomResponse::success([
			'message' => 'Expenditure Frequencies listed successfully!',
			'data' => [
				'expenditureFrequencies' => $expenditureFrequencies,
			],
		]);
	}
}
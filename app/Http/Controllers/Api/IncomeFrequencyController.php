<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncomeFrequency;
use App\Core\CustomResponse;

class IncomeFrequencyController extends Controller {

	protected function index(Request $request) {

		$incomeFrequencies = IncomeFrequency::all();

		return CustomResponse::success([
			'message' => 'Income Frequencies listed successfully!',
			'data' => [
				'incomeFrequencies' => $incomeFrequencies,
			],
		]);
	}
}
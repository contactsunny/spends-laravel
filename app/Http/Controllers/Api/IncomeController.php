<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthToken;
use App\Models\Income;
use App\Models\User;
use App\Models\PasswordReset;
use App\Core\CustomResponse;
use App\Core\UuidGenerator;
use \Validator;

class IncomeController extends Controller {

	protected function index(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$incomes = Income::where('user_id', $userId)
								->with('incomeType')
								->get();

			return CustomResponse::success([
				'message' => 'Income listed successfully!',
				'data' => [
					'incomes' => $incomes,
				],
			]);
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function store(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$input = $request->all();

			$input['user_id'] = $userId;
			$input['id'] = UuidGenerator::getNewUuid();

			$validator = Validator::make($input, [
				'income_name' => 'required',
				'id' => 'required|unique:income',
				'user_id' => 'required|exists:users,id',
				'income_type' => 'required|exists:income_type,id',
				'income_value' => 'required|numeric'
			]);

			if($validator->fails()) {
				return response()->json([
					'status' => 0,
					'error' => [
						'message' => $validator->messages()->first(),
					]
				], 422);
			}

			$existingIncome = Income::where('income_name', $input['income_name'])
										->where('user_id', $userId)
										->first();

			if ($existingIncome) {
				return CustomResponse::error([
					'message' => 'Income with this name already exists!',
				], 422);
			}

			$income = new Income($input);

			if ($income->save()) {
				return CustomResponse::success([
					'message' => 'Income saved successfully!',
					'data' => $income,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Error saving income!',
				], 500);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function update(Request $request, $incomeId) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$input = $request->all();
			$input['user_id'] = $userId;

			$validator = Validator::make($input, [
				'income_name' => 'required',
				'user_id' => 'required|exists:users,id',
				'income_type' => 'required|exists:income_type,id',
				'income_value' => 'required|numeric'
			]);

			if($validator->fails()) {
				return response()->json([
					'status' => 0,
					'error' => [
						'message' => $validator->messages()->first(),
					]
				], 422);
			}

			$existingIncome = Income::where('income_name', $input['income_name'])
										->where('user_id', $userId)
										->where('id', '!=', $incomeId)
										->first();

			if ($existingIncome) {
				return CustomResponse::error([
					'message' => 'Income with this name already exists!',
				], 422);
			}

			$income = Income::find($incomeId);

			if ($income->update($input)) {
				return CustomResponse::success([
					'message' => 'Income updated successfully!',
					'data' => $income,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Error updating income!',
				], 500);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function destroy(Request $request, $incomeId) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$income = Income::where('id', $incomeId)
								->where('user_id', $userId)
								->first();

			if (!$income) {
				return CustomResponse::error([
					'message' => 'Income not found!',
				], 404);
			}

			if ($income->delete()) {
				return CustomResponse::success([
					'message' => 'Income deleted successfully!',
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Error deleting income!',
				], 500);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}
}
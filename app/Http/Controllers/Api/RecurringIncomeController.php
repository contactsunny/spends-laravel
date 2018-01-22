<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthToken;
use App\Models\RecurringIncome;
use App\Models\User;
use App\Models\PasswordReset;
use App\Core\CustomResponse;
use App\Core\UuidGenerator;
use \Validator;

class RecurringIncomeController extends Controller {

	protected function index(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$incomes = RecurringIncome::where('user_id', $userId)
								->with('incomeType')
								->with('incomeFrequency')
								->get();

			return CustomResponse::success([
				'message' => 'Recurring Income listed successfully!',
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
				'income_frequency' => 'required|exists:income_frequencies,id',
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

			$existingIncome = RecurringIncome::where('income_name', $input['income_name'])
										->where('user_id', $userId)
										->first();

			if ($existingIncome) {
				return CustomResponse::error([
					'message' => 'Recurring Income with this name already exists!',
				], 422);
			}

			$income = new RecurringIncome($input);

			if ($income->save()) {
				return CustomResponse::success([
					'message' => 'Recurring Income saved successfully!',
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
				'income_frequency' => 'required|exists:income_frequencies,id',
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

			$existingIncome = RecurringIncome::where('income_name', $input['income_name'])
										->where('user_id', $userId)
										->where('id', '!=', $incomeId)
										->first();

			if ($existingIncome) {
				return CustomResponse::error([
					'message' => 'Recurring Income with this name already exists!',
				], 422);
			}

			$income = RecurringIncome::find($incomeId);

			if ($income->update($input)) {
				return CustomResponse::success([
					'message' => 'Recurring Income updated successfully!',
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

			$income = RecurringIncome::where('id', $incomeId)
								->where('user_id', $userId)
								->first();

			if (!$income) {
				return CustomResponse::error([
					'message' => 'Recurring Income not found!',
				], 404);
			}

			if ($income->delete()) {
				return CustomResponse::success([
					'message' => 'Recurring Income deleted successfully!',
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
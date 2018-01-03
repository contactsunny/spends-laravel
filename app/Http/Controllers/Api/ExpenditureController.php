<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthToken;
use App\Models\Expenditure;
use App\Models\User;
use App\Models\PasswordReset;
use App\Core\CustomResponse;
use App\Core\UuidGenerator;
use \Validator;

class ExpenditureController extends Controller {

	protected function index(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$expenditures = Expenditure::where('user_id', $userId)
								->with('expenditureType')
								->with('expenditureFrequency')
								->get();

			return CustomResponse::success([
				'message' => 'Expenditures listed successfully!',
				'data' => [
					'expenditures' => $expenditures,
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
				'expenditure_name' => 'required',
				'id' => 'required|unique:expenditures',
				'user_id' => 'required|exists:users,id',
				'expenditure_type' => 'required|exists:expenditure_types,id',
				'expenditure_frequency' => 'required|exists:expenditure_frequencies,id',
				'expenditure_value' => 'required|numeric'
			]);

			if($validator->fails()) {
				return response()->json([
					'status' => 0,
					'error' => [
						'message' => $validator->messages()->first(),
					]
				], 422);
			}

			$existingExpenditure = Expenditure::where('expenditure_name', $input['expenditure_name'])
										->where('user_id', $userId)
										->first();

			if ($existingExpenditure) {
				return CustomResponse::error([
					'message' => 'Expenditure with this name already exists!',
				], 422);
			}

			$expenditure = new Expenditure($input);

			if ($expenditure->save()) {
				return CustomResponse::success([
					'message' => 'Expenditure saved successfully!',
					'data' => $expenditure,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Error saving expenditure!',
				], 500);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function update(Request $request, $expenditureId) {

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
				'expenditure_name' => 'required',
				'user_id' => 'required|exists:users,id',
				'expenditure_type' => 'required|exists:expenditure_types,id',
				'expenditure_frequency' => 'required|exists:expenditure_frequencies,id',
				'expenditure_value' => 'required|numeric'
			]);

			if($validator->fails()) {
				return response()->json([
					'status' => 0,
					'error' => [
						'message' => $validator->messages()->first(),
					]
				], 422);
			}

			$existingExpenditure = Expenditure::where('expenditure_name', $input['expenditure_name'])
										->where('user_id', $userId)
										->where('id', '!=', $expenditureId)
										->first();

			if ($existingExpenditure) {
				return CustomResponse::error([
					'message' => 'Expenditure with this name already exists!',
				], 422);
			}

			$expenditure = Expenditure::find($expenditureId);

			if ($expenditure->update($input)) {
				return CustomResponse::success([
					'message' => 'Expenditure updated successfully!',
					'data' => $expenditure,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Error updating expenditure!',
				], 500);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function destroy(Request $request, $expenditureId) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$expenditure = Expenditure::where('id', $expenditureId)
								->where('user_id', $userId)
								->first();

			if (!$expenditure) {
				return CustomResponse::error([
					'message' => 'Expenditure not found!',
				], 404);
			}

			if ($expenditure->delete()) {
				return CustomResponse::success([
					'message' => 'Expenditure deleted successfully!',
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Error deleting expenditure!',
				], 500);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}
}
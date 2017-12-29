<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuthToken;
use App\Models\Family;
use App\Models\UserFamily;
use App\Models\User;
use App\Models\PasswordReset;
use App\Core\CustomResponse;
use App\Core\UuidGenerator;
use \Validator;
use App\Mail\InviteToFamilyMail;

class FamilyController extends Controller {

	protected function index(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$families = Family::where('owner_id', $userId)->with('owner')->get();

			return CustomResponse::success([
				'message' => 'Families listed successfully!',
				'data' => [
					'families' => $families,
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
			$input['id'] = UuidGenerator::getNewUuid();
			$input['owner_id'] = $userId;

			$validator = Validator::make($input, [
				'family_name' => 'required',
				'id' => 'required|unique:families',
				'owner_id' => 'required',
			]);

			if($validator->fails()) {
				return response()->json([
					'status' => 0,
					'error' => [
						'message' => $validator->messages()->first(),
					]
				], 422);
			}

			$existingFamily = Family::where('owner_id', $userId)
								->where('family_name', $input['family_name'])
								->first();

			if ($existingFamily != null) {
				return CustomResponse::error([
					'message' => 'Family with name \'' . $input['family_name']
								. '\' already exists!',
				], 422);
			}

			$family = new Family($input);

			if ($family->save()) {

				$userFamily = new UserFamily([
					'id' => UuidGenerator::getNewUuid(),
					'user_id' => $userId,
					'family_id' => $family->id,
				]);

				$userFamily->save();

				return CustomResponse::success([
					'message' => 'Family saved successfully!',
					'data' => $family,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Something went wrong!',
				], 500);
			}
		} catch(\Exception $e) {
			\Log::error($e);
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function update(Request $request, $familyId) {
		
		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$input = $request->all();
			$input['id'] = UuidGenerator::getNewUuid();
			$input['owner_id'] = $userId;

			$validator = Validator::make($input, [
				'family_name' => 'required',
				'owner_id' => 'required',
			]);

			if($validator->fails()) {
				return response()->json([
					'status' => 0,
					'error' => [
						'message' => $validator->messages()->first(),
					]
				], 422);
			}

			$existingFamily = Family::where('owner_id', $userId)
								->where('family_name', $input['family_name'])
								->first();

			if ($existingFamily != null) {
				return CustomResponse::error([
					'message' => 'Family with name \'' . $input['family_name']
								. '\' already exists!',
				], 422);
			}

			$family = Family::find($familyId);

			if ($family->update($input)) {
				return CustomResponse::success([
					'message' => 'Family updated successfully!',
					'data' => $family,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Something went wrong!',
				], 500);
			}
		} catch(\Exception $e) {
			\Log::error($e);
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function show(Request $request, $familyId) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$family = Family::find($familyId);

			if ($family != null) {
				return CustomResponse::success([
					'message' => 'Families listed successfully!',
					'data' => $family,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Family with ID \'' . $familyId . '\' not found!'
				], 404);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function destroy(Request $request, $familyId) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$family = Family::find($familyId);

			if ($family != null) {
				if ($family->delete()) {

					UserFamily::where('family_id', $familyId)->delete();

					return CustomResponse::success([
						'message' => 'Family deleted successfully!'
					]);
				} else {
					return CustomResponse::error([
						'message' => 'Unable to delete family at this time!'
					], 500);
				}
			} else {
				return CustomResponse::error([
					'message' => 'Family not found!'
				], 404);
			}
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function getAllFamilies(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$families = Family::with('owner')->get();

			return CustomResponse::success([
				'message' => 'Families listed successfully!',
				'data' => $families,
			]);
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	protected function inviteToFamily(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$authToken = AuthToken::where('auth_token', $authToken)->first();

			if(!$authToken) {
				throw new \Exception('Invalid auth token!');
			}

			$userId = $authToken->user_id;

			$user = User::find($userId);

			$input = $request->all();

			$familyId = $input['family_id'];
			unset($input['family_id']);

			$validator = Validator::make($input, [
				'email' => 'required|email|unique:users,email',
			]);

			if($validator->fails()) {
				return response()->json([
					'status' => 0,
					'error' => [
						'message' => $validator->messages()->first(),
					]
				], 422);
			}

			$input['id'] = UuidGenerator::getNewUuid();
			$input['password'] = 'NA';

			$newUser = new User($input);
			$newUser->save();

			$userFamily = new UserFamily([
				'id' => UuidGenerator::getNewUuid(),
				'user_id' => $newUser->id,
				'family_id' => $familyId,
			]);

			$userFamily->save();

			$passwordResetCode = \Hash::make(strtotime('now') . $newUser->id . $newUser->email);

			$passwordReset = new PasswordReset([
				'email' => $input['email'],
				'token' => $passwordResetCode,
				'created_at' => date('Y-m-d H:i:s', strtotime('now')),
			]);

			$passwordReset->save();

			$viewData = [
				'user' => $user,
				'newUser' => $newUser,
				'passwordReset' => $passwordReset,
			];

			\Mail::to($newUser->email)->queue(new InviteToFamilyMail($viewData));

			\Log::info($newUser->email . ' invited to Spends!');

			return CustomResponse::success([
				'message' => 'User invited to Spends!',
			]);

		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}
}
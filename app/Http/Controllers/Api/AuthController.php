<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\PasswordReset;
use App\Models\AuthToken;
use App\Core\CustomResponse;
use App\Core\UuidGenerator;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller {

    private $user, $authToken;

    function __construct(User $user, AuthToken $authToken) {
        $this->user = $user;
        $this->authToken = $authToken;
    }

    function register(Request $request) {

		$input = $request->all();

		$validator = Validator::make($input, [
			'email' => 'required|email|unique:users,email',
			'password' => 'required|min:8',
			'name' => 'required|string',
		]);

		if($validator->fails()) {
			return response()->json([
				'status' => 0,
				'error' => [
					'message' => $validator->messages()->first(),
				]
			], 422);
		}

		$input['password'] = \Hash::make($input['password']);
		$input['id'] = UuidGenerator::getNewUuid();

		if(!isset($input['user_group'])) {
		    $input['user_group'] = 2;
        }

		$user = new User($input);
		$user->save();

		return response()->json([
			'status' => 1,
			'data' => [
				'user' => $user
			]
		], 200);
	}

	function login(Request $request) {

		$input = $request->all();

		$validator = Validator::make($input, [
			'email' => 'required|email',
			'password' => 'required|min:8'
		]);

		if($validator->fails()) {
			return response()->json([
				'status' => 0,
				'error' => [
					'message' => $validator->messages()->first(),
				]
			], 422);
		}

		if(\Auth::attempt([
			'email' => $input['email'],
			'password' => $input['password']
		])) {
			$authToken = \Hash::make(\Auth::user()['id'] . strtotime('now'));

			AuthToken::create([
				'user_id' => \Auth::user()['id'],
				'auth_token' => $authToken,
			]);

			return CustomResponse::success([
				'user' => \Auth::user(),
				'auth_token' => $authToken,
			]);
		} else {
			return CustomResponse::error([
				'message' => 'Invalid email/password!',
			]);
		}
	}

	public function logout(Request $request) {

		$authToken = \Request::header()['authorization'][0];

		try {
			$result = $this->authToken->invalidateAuthToken($authToken);

			if(!$result) {
				throw new \Exception('Invalid auth token!');
			}

			return CustomResponse::success([
				'message' => 'Logged out successfully!',
			]);
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}
	}

	function sendForgotPasswordCodeEmail(Request $request) {

		$input = $request->all();

		$validator = Validator::make($input, [
			'email' => 'required|email',
		]);

		if($validator->fails()) {
			return response()->json([
				'status' => 0,
				'error' => [
					'message' => $validator->messages()->first(),
				]
			], 422);
		}

		try {
			$user = User::where('email', $input['email'])->first();

			if(!$user) {
				throw new \Exception('Email is not registered!');
			}

			$passwordResetCode = \Hash::make(strtotime('now') . $user->id . $user->email);

			$passwordReset = new PasswordReset([
				'email' => $input['email'],
				'token' => $passwordResetCode,
				'created_at' => date('Y-m-d H:i:s', strtotime('now')),
			]);

			$passwordReset->save();

			$viewData = [
				'user' => $user,
				'passwordReset' => $passwordReset,
			];

			\Mail::to($user->email)->queue(new ResetPasswordMail($viewData));
		} catch(\Exception $e) {
			return CustomResponse::error([
				'message' => $e->getMessage()
			], 401);
		}

		return CustomResponse::success([
			'message' => 'Email sent successfully!',
		]);
	}

	public function resetPassword(Request $request) {

		$input = $request->all();

		$validator = Validator::make($input, [
			'password' => 'required|min:8',
			'repeat_password' => 'required|min:8'
		]);

		if($validator->fails()) {
			return response()->json([
				'status' => 0,
				'error' => [
					'message' => $validator->messages()->first(),
				]
			], 422);
		}

		try {
			PasswordReset::validateToken($input['token']);

			if($input['password'] != $input['repeat_password']) {
				throw new \Exception('Passwords don\'t match!', 422);
			}

			$newPassword = \Hash::make($input['password']);

			$passwordReset = PasswordReset::where('token', $input['token'])->first();

			if($passwordReset) {
				$user = User::where('email', $passwordReset->email)->first();

				if($user) {
					$user->password = $newPassword;
					$user->save();

					$passwordReset->delete();
				} else {
					throw new \Exception('User not found!', 401);
				}
			} else {
				throw new \Exception('Invalid token!', 401);
			}

			return CustomResponse::success([
				'message' => 'Password changed successfully!',
			]);
		} catch(\Exception $e) {
			return response()->json([
				'status' => 0,
				'error' => [
					'message' => $e->getMessage(),
				]
			], 422);
		}
	}

	public function changePassword(Request $request, $userId) {

		$input = $request->all();

		$validator = Validator::make($input, [
			'current_password' => 'required|min:8',
			'new_password' => 'required|min:8',
			'repeat_password' => 'required|min:8'
		]);

		if($validator->fails()) {
			return response()->json([
				'status' => 0,
				'error' => [
					'message' => $validator->messages()->first(),
				]
			], 422);
		}

		try {
			$user = User::find($userId);

			if(!$user) {
				throw new \Exception('User not found!', 401);
			}

			if(\Auth::once([
				'email' => $user->email,
				'password' => $input['current_password'],
			])) {
				if($input['new_password'] != $input['repeat_password']) {
					throw new \Exception('Passwords don\'t match!', 401);
				}

				$user->password = \Hash::make($input['new_password']);
				if($user->update()) {
					return CustomResponse::success([
						'message' => 'Password updated successfully!',
					]);
				} else {
					throw new \Exception('Unable to update password! Try agian later.', 500);
				}
			} else {
				throw new \Excpetion('Wrong current password!', 401);
			}
		} catch(\Exception $e) {
			return response()->json([
				'status' => 0,
				'error' => [
					'message' => $e->getMessage(),
				]
			], 422);
		}
	}

}
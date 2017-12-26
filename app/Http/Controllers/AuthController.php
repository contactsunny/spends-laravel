<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;

class AuthController extends Controller {

	public function index(Request $request) {

		if(session('user')) {
			return redirect('dashboard');
		}

		return view('welcome');
	}

	public function login(Request $request) {

		session([
			'user' => $request->get('user'),
			'authToken' => $request->get('auth_token')
		]);

		return redirect('dashboard');
	}

	public function logout(Request $request) {

		$authToken = session('authToken');

		session()->forget('user');
		session()->forget('authToken');

		return response()->json([
			'status' => 1,
			'data' => [
				'authToken' => $authToken
			]
		]);
	}

	public function showRegisterPage(Request $request) {

		if(session('user')) {
			return redirect('dashboard');
		}

		return view('register');
	}

	function showForgotPasswordPage(Request $request) {

		if(session('user')) {
			return redirect('dashboard');
		}

		return view('forgotPassword');
	}

	function showResetPasswordpage(Request $request) {

		$token = $request->get('token');

		try {
			PasswordReset::validateToken($token);
		} catch(\Exception $e) {
			return view('error', [
				'error' => $e->getMessage(),
			]);
		}

		return view('resetPassword', [
			'token' => $token,
		]);
	}
}
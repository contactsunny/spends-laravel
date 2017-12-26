<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use App\Models\AuthToken;
use App\Core\CustomResponse;

class VerifyAuthHeaderMiddleware {

	public function handle($request, Closure $next) {

//        if (env('APP_ENV') === 'testing') {
//            $testAttributes = [
//                new AuthToken([
//                    'auth_token' => 'testingAuthToken',
//                ]),
//                'user' => new User([
//                    'id' => 1,
//                ]),
//            ];
//
//            $request->attributes->add($testAttributes);
//            return $next($request);
//        }

		if($request->header('Authorization')) {
			$authToken = AuthToken::where('auth_token', $request->header('Authorization'))->first();

			if($authToken) {
				$request->attributes->add([
					'authToken' => $authToken,
					'user' => $authToken->user,
				]);
			} else {
				return CustomResponse::error([
					'message' => 'Invalid/missing auth token',
					'errorCode' => 1,
				], 401);
			}

			return $next($request);
		} else {
			return CustomResponse::error([
				'message' => 'Invalid/missing auth token',
			], 401);
		}
	}
}
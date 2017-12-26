<?php

namespace App\Core;

class CustomResponse {

	public static function success($data = []) {

		return response()->json([
			'status' => 1,
			'data' => $data
		], 200);
	}

	public static function error($data = [], $statusCode = 500) {

		if($statusCode == 0) {
			$statusCode = 500;
		}

		return response()->json([
			'status' => 0,
			'error' => $data
		], $statusCode);
	}
}
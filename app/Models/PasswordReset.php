<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model {

    protected $table = 'password_resets';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('\App\Models\User', 'email');
    }

    public static function validateToken($token) {
        if(!$token) {
			throw new \Exception('Token not found.', 403);
		}

		$passwordReset = PasswordReset::where('token', $token)->first();

		if(!$passwordReset) {
			throw new \Exception('Invalid token.', 403);
		}

		$user = User::where('email', $passwordReset->email)->first();

		if(!$user) {
			throw new \Exception('User not found', 403);
		}

        return true;
    }

}
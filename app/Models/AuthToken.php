<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $table = 'auth_tokens';
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function invalidateAuthToken($authToken = null) {

    	if($authToken == null) {
    		throw new \Exception('Invalid auth token');
    	}

    	$result = AuthToken::where('auth_token', $authToken)->delete();
    	return $result;
    }
}

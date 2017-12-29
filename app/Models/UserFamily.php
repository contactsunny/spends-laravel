<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFamily extends Model {

	protected $table = 'user_families';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $casts = ['id' => 'string'];
    public $incrementing = false;

    public function owner() {
        return $this->belongsTo('App\Models\User');
    }

    public function family() {
        return $this->belongsTo('App\Models\Family');
    }
}
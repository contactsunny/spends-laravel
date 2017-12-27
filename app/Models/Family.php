<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model {

	protected $table = 'families';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $casts = ['id' => 'string'];
    public $incrementing = false;

    public function owner() {
        return $this->belongsTo('App\Models\User');
    }
}
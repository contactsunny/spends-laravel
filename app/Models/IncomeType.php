<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model {

	protected $table = 'income_type';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];
}
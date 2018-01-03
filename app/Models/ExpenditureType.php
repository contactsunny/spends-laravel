<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenditureType extends Model {

	protected $table = 'expenditure_types';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];
}
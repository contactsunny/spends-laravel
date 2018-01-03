<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenditureFrequency extends Model {

	protected $table = 'expenditure_frequencies';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];
}
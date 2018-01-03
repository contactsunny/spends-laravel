<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model {

	protected $table = 'expenditures';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $casts = ['id' => 'string'];
    public $incrementing = false;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function expenditureType() {
    	return $this->belongsTo('App\Models\ExpenditureType', 'expenditure_type');
    }

    public function expenditureFrequency() {
    	return $this->belongsTo('App\Models\ExpenditureFrequency', 'expenditure_frequency');
    }
}
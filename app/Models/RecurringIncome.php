<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringIncome extends Model {

	protected $table = 'recurring_income';
    protected $guarded = [];
    protected $primaryKey = 'id';
    protected $casts = ['id' => 'string'];
    public $incrementing = false;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function incomeType() {
    	return $this->belongsTo('App\Models\IncomeType', 'income_type');
    }

    public function incomeFrequency() {
    	return $this->belongsTo('App\Models\IncomeFrequency', 'income_frequency');
    }
}
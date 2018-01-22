<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringExpenditureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_expenditure', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('expenditure_name');
            $table->double('expenditure_value', 8, 2);
            $table->tinyInteger('expenditure_type');
            $table->string('expenditure_frequency');
            $table->string('status')->default('active');
            $table->date('status_change_datetime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurring_expenditure');
    }
}

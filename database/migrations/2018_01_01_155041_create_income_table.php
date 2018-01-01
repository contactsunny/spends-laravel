<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income', function(Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('income_name');
            $table->string('income_type');
            $table->string('income_value');
            $table->string('income_frequency');
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
        Schema::drop('income');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_income', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->string('income_name');
            $table->integer('income_type');
            $table->decimal('income_value', 8, 2);
            $table->string('income_frequency');
            $table->string('status');
            $table->string('status_change_datetime')->nullable()->default(null);
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
        Schema::dropIfExists('recurring_income');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToIncomeAndExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income', function(Blueprint $table) {
            $table->integer('income_type')->after('income_value');
            $table->date('income_date')->after('income_type')->nullable()->default(null);
        });

        Schema::table('expenditures', function(Blueprint $table) {
            $table->integer('expenditure_type')->after('expenditure_value');
            $table->date('expenditure_date')->after('expenditure_type')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income', function(Blueprint $table) {
            $table->dropColumn('income_type');
            $table->dropColumn('income_date');
        });

        Schema::table('expenditures', function(Blueprint $table) {
            $table->dropColumn('expenditure_type');
            $table->dropColumn('expenditure_date');
        });
    }
}

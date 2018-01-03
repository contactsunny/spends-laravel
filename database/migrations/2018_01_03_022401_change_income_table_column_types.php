<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIncomeTableColumnTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income', function(Blueprint $table) {
            $table->decimal('income_value', 8, 2)->change();
        });

        Schema::table('income', function(Blueprint $table) {
            $table->integer('income_type')->change();
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
            $table->string('income_value')->change();
        });

        Schema::table('income', function(Blueprint $table) {
            $table->string('income_type')->change();
        });
    }
}

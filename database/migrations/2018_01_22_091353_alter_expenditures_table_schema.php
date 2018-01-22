<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExpendituresTableSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenditures', function(Blueprint $table) {
            $table->dropColumn('expenditure_type');
            $table->dropColumn('expenditure_frequency');
            $table->dropColumn('status');
            $table->dropColumn('status_change_datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenditures', function(Blueprint $table) {
            $table->tinyInteger('expenditure_type');
            $table->string('expenditure_frequency');
            $table->string('status')->default('active');
            $table->date('status_change_datetime')->nullable();
        });
    }
}

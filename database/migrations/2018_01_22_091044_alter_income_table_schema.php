<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIncomeTableSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income', function(Blueprint $table) {
            $table->dropColumn('income_type');
            $table->dropColumn('income_frequency');
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
        Schema::table('income', function(Blueprint $table) {
            $table->integer('income_type');
            $table->string('income_frequency');
            $table->string('status');
            $table->string('status_change_datetime')->nullable()->default(null);
        });
    }
}

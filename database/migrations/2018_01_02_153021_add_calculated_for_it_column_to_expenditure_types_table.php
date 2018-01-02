<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalculatedForItColumnToExpenditureTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenditure_types', function(Blueprint $table) {
            $table->boolean('calculated_for_it')->default(false)->after('expenditure_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenditure_types', function(Blueprint $table) {
            $table->dropColumnIfExists('calculated_for_it');
        });
    }
}

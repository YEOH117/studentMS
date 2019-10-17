<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToBuildingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('building', function (Blueprint $table) {
            //
            $table->tinyInteger('layers')->notnull();
            $table->tinyInteger('layer_roon_num')->notnull();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building', function (Blueprint $table) {
            //
            $table->dropColumn(['layers','layer_roon_num']);
        });
    }
}

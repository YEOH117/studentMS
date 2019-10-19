<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('area')->notnull();
            $table->index('area');
            $table->tinyInteger('building')->notnull();
            $table->index('building');
            $table->tinyInteger('sex')->notnull();
            $table->index('sex');
            $table->string('preference',200)->notnull();
            $table->index('preference');
            $table->integer('empty_num')->notnull();
            $table->index('empty_num');
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
        Schema::dropIfExists('building');
    }
}

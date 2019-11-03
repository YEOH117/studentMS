<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDormitorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dormitorys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_num')->notnull();
            $table->index('house_num');
            $table->integer('building_id')->notnull();
            $table->index('building_id');
            $table->tinyInteger('floor')->notnull();
            $table->tinyInteger('num')->notnull();
            $table->string('preference',200)->default('');
            $table->index('preference');
            $table->tinyInteger('roon_people')->default(6);
            $table->tinyInteger('Remain_number')->default(6);
            $table->index('Remain_number');
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
        Schema::dropIfExists('dormitorys');
    }
}

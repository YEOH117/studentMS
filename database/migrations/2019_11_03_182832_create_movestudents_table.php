<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovestudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movestudents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('target_id')->notnull();
            $table->index('target_id');
            $table->integer('user_id')->notnull();
            $table->unique('user_id');
            $table->string('state',20)->notnull();
            $table->index('state');
            $table->string('token',100)->notnull();
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
        Schema::dropIfExists('movestudents');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->notnull();
            $table->index('student_id');
            $table->tinyInteger('sort')->notnull();
            $table->index('sort');
            $table->text('info');
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
        Schema::dropIfExists('filings');
    }
}

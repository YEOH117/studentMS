<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account')->notnull();
            $table->unique('account');
            $table->string('name',50)->default('未设置名称');
            $table->string('email',30)->default('未绑定邮箱');
            $table->string('phone',20)->notnull();
            $table->string('password',200)->notnull();
            $table->string('grade',5)->notnull();
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
        Schema::dropIfExists('users');
    }
}

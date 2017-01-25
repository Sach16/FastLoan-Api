<?php

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
            $table->uuid('uuid')->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('role');
            $table->integer('designation_id')->unsigned();
            $table->enum('track_status', array('TRUE','FALSE'))->default('FALSE');
            $table->text('settings')->nullable();
            $table->string('api_token')->nullable();
            $table->string('otp')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('designation_id')->references('id')->on('designations');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

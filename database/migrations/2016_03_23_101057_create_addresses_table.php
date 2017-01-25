<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('addressable');
            $table->uuid('uuid')->index();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('alpha_street')->nullable();
            $table->string('beta_street')->nullable();
            //$table->string('city')->nullable();
            $table->integer('city_id')->unsigned();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->integer('zip')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addresses');
    }
}

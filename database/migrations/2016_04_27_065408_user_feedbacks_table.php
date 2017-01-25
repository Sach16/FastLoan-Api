<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_feedbacks', function (Blueprint $table) {
             $table->increments('id');
             $table->uuid('uuid')->index();
             $table->integer('feedback_id')->unsigned();
             $table->decimal('rating',5,2);
             $table->integer('user_id')->unsigned();
             $table->timestamps();
             $table->softDeletes();
             
             $table->foreign('feedback_id')->references('id')->on('feedbacks');
             $table->foreign('user_id')->references('id')->on('users');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('user_feedbacks');
    }
}

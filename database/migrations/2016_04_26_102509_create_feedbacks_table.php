<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('feedbacks', function (Blueprint $table) {
             $table->increments('id');
             $table->uuid('uuid')->index();
             $table->text('feedback');
             $table->integer('category_id')->unsigned();
             $table->timestamps();
             $table->softDeletes();
             
             $table->foreign('category_id')->references('id')->on('feedback_categories');
             
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feedbacks');
    }
}

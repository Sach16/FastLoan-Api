<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_categories', function (Blueprint $table) {
             $table->increments('id');
             $table->uuid('uuid')->index();
             $table->string('name');
             $table->string('key');
             $table->timestamps();
             $table->softDeletes();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feedback_categories');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->integer('user_id')->unsigned();
            $table->morphs('taskable');
            $table->integer('task_status_id')->unsigned();
            $table->integer('task_stage_id')->unsigned();
            $table->string('priority');
            $table->text('description');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');            
            $table->foreign('task_status_id')->references('id')->on('task_statuses');
            $table->foreign('task_stage_id')->references('id')->on('task_stages');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            Schema::drop('tasks');
        });
    }
}

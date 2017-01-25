<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->text('query');
            $table->integer('project_id')->unsigned();
            $table->integer('assigned_to')->unsigned();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('due_date');
            $table->datetime('raised_date')->nullable();
            $table->string('status');
            $table->string('pending_with');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('assigned_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('queries');
    }
}

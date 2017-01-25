<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name');
            $table->integer('builder_id')->unsigned();
            $table->integer('owner_id')->unsigned();
            $table->integer('unit_details');
            $table->integer('status_id')->unsigned();
            $table->datetime('possession_date');
            $table->datetime('lsr_start_date')->nullable();
            $table->datetime('lsr_end_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('builder_id')->references('id')->on('builders');
            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('project_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projects');
    }
}

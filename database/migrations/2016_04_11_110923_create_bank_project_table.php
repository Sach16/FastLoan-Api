<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_project', function (Blueprint $table) {
            $table->integer('bank_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->integer('agent_id')->unsigned();
            $table->string('status');
            $table->timestamp('approved_date');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['bank_id', 'project_id', 'agent_id', 'deleted_at']);
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('agent_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bank_project');
    }
}

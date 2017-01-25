<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->integer('user_id')->unsigned();
            $table->integer('source_id')->unsigned();
            $table->integer('created_by')->unsigned()->nullable()->default(NULL);
            //$table->integer('referral_id')->unsigned();
            $table->integer('assigned_to')->unsigned()->nullable()->default(NULL);
            $table->integer('loan_id')->unsigned();
            $table->float('existing_loan_emi');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('assigned_to')->references('id')->on('users');
            $table->foreign('source_id')->references('id')->on('sources');
            $table->foreign('created_by')->references('id')->on('users');
            //$table->foreign('referral_id')->references('id')->on('loans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('leads');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->integer('loan_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('agent_id')->unsigned()->nullable()->default(NULL);
            $table->integer('modified_by')->unsigned();
            $table->decimal('amount',12,2);
            $table->decimal('eligible_amount',12,2);
            $table->decimal('approved_amount',12,2);
            $table->decimal('interest_rate',4,2);
            $table->datetime('applied_on');
            $table->datetime('approval_date')->nullable()->default(NULL);
            $table->datetime('emi_start_date')->nullable()->default(NULL);
            $table->decimal('emi',12,2);
            $table->string('appid');
            $table->string('loan_status_id');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('agent_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('modified_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loan_histories');
    }
}

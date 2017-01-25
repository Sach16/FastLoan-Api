<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->integer('type_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('agent_id')->unsigned()->nullable()->default(NULL);
            $table->decimal('amount',12,2);
            $table->decimal('eligible_amount',12,2);
            $table->decimal('approved_amount',12,2);
            $table->decimal('interest_rate',4,2);
            $table->datetime('applied_on');
            $table->datetime('approval_date')->nullable()->default(NULL);
            $table->datetime('emi_start_date')->nullable()->default(NULL);
            $table->decimal('emi',12,2);
            $table->string('appid');
            $table->integer('loan_status_id')->unsigned();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('agent_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('loan_status_id')->references('id')->on('loan_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loans');
    }
}

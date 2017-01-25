<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoanAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('loan_alerts', function (Blueprint $table) {
             $table->increments('id');
             $table->uuid('uuid')->index();
             $table->integer('user_id')->unsigned();
             $table->integer('bank_id')->unsigned();
             $table->integer('type_id')->unsigned();
             $table->decimal('loan_emi_amount',12,2);
             $table->datetime('due_date');
             $table->decimal('interest_rate',12,2);
             $table->decimal('balance_amount',12,2);
             $table->char('emi_start_date',64);
             $table->boolean('emi');
             $table->boolean('take_over');
             $table->boolean('part_pre_payement');
             $table->text('type_of_property');
             
             $table->timestamps();
             $table->softDeletes();
             
             $table->foreign('user_id')->references('id')->on('users');
             $table->foreign('bank_id')->references('id')->on('banks');
             $table->foreign('type_id')->references('id')->on('types');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loan_alerts');
    }
}

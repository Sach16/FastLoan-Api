<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->integer('loan_id')->unsigned();
            $table->integer('attachment_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();  
            
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->foreign('attachment_id')->references('id')->on('attachments');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('loan_documents');
    }
}

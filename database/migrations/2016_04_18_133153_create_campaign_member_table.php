<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_member', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->timestamps();

            $table->primary(['campaign_id', 'member_id']);
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->foreign('member_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('campaign_member');
    }
}

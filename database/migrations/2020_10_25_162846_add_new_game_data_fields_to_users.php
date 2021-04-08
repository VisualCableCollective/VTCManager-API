<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewGameDataFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("current_game_running")->nullable();
            $table->dateTime("last_client_update")->nullable();
            $table->double("PositionX")->nullable();
            $table->double("PositionY")->nullable();
            $table->double("PositionZ")->nullable();
            $table->float("OrientationHeading")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}

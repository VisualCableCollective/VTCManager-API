<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobDataEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_data_entries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("job_id");
            $table->integer("current_speed_kph");
            $table->integer("current_speed_limit_kph");
            $table->integer("trailers_attached");
            $table->dateTime("current_ingame_time");
            $table->float("current_truck_cabin_damage");
            $table->float("current_truck_chassis_damage");
            $table->float("current_truck_engine_damage");
            $table->float("current_truck_transmission_damage");
            $table->float("current_truck_wheels_avg_damage");

            $table->float("current_trailer_avg_damage_chassis");
            $table->float("current_trailer_avg_damage_wheels");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_data_entries');
    }
}

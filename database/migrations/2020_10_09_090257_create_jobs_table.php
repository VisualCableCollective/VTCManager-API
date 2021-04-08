<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("user_id");
            $table->string("truck_model_id");
            $table->string("city_departure_id");
            $table->string("city_destination_id");
            $table->string("company_departure_id");
            $table->string("company_destination_id");
            $table->string("cargo_id");
            $table->integer("planned_distance_km");
            $table->boolean("special_job");
            $table->dateTime("job_ingame_started");
            $table->dateTime("job_ingame_deadline");
            $table->string("market_id");
            $table->float("truck_cabin_damage_at_start");
            $table->float("truck_chassis_damage_at_start");
            $table->float("truck_engine_damage_at_start");
            $table->float("truck_transmission_damage_at_start");
            $table->float("truck_wheels_avg_damage_at_start");
            $table->float("trailer_avg_damage_chassis_at_start");
			$table->float("trailer_avg_damage_wheels_at_start");
            $table->float("truck_cabin_damage_at_end")->nullable();
            $table->float("truck_chassis_damage_at_end")->nullable();
            $table->float("truck_engine_damage_at_end")->nullable();
            $table->float("truck_transmission_damage_at_end")->nullable();
            $table->float("truck_wheels_avg_damage_at_end")->nullable();
            $table->float("trailer_avg_damage_chassis_at_end")->nullable();
            $table->float("trailer_avg_damage_wheels_at_end")->nullable();
            $table->dateTime("remaining_delivery_time")->nullable();
            $table->float("remaining_distance")->nullable();
            $table->float("cargo_damage")->nullable();
            $table->float("cargo_mass");
            $table->string("status");
            $table->integer("income");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}

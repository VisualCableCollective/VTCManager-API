<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeRelationshipsNullableInJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string("truck_model_id")->nullable()->change();
            $table->string("city_departure_id")->nullable()->change();
            $table->string("city_destination_id")->nullable()->change();
            $table->string("company_departure_id")->nullable()->change();
            $table->string("company_destination_id")->nullable()->change();
            $table->string("cargo_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string("truck_model_id")->change();
            $table->string("city_departure_id")->change();
            $table->string("city_destination_id")->change();
            $table->string("company_departure_id")->change();
            $table->string("company_destination_id")->change();
            $table->string("cargo_id")->change();
        });
    }
}

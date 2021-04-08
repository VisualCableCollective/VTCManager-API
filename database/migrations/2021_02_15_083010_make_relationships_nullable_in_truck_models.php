<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeRelationshipsNullableInTruckModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('truck_models', function (Blueprint $table) {
            $table->string("truck_manufacturer_id")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('truck_models', function (Blueprint $table) {
            $table->string("truck_manufacturer_id")->change();
        });
    }
}

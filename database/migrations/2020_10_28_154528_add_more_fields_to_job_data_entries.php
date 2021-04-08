<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToJobDataEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_data_entries', function (Blueprint $table) {
            $table->float("navigation_distance_remaining");
            $table->dateTime("navigation_time_remaining");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_data_entries', function (Blueprint $table) {
            $table->dropColumn("navigation_distance_remaining");
            $table->dropColumn("navigation_time_remaining");
        });
    }
}

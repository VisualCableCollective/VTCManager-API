<?php

namespace App\Http\Controllers\API\Client;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobDataEntry;
use Illuminate\Support\Carbon;

class ClientController extends Controller
{
    public function update(Request $request){
        $request->validate([
            'game_running' => 'required|string',
        ]);

        $user = User::findorfail($request->user()->id);
        $user->last_client_update = Carbon::now();

        if($request->game_running != "false"){
            $user->current_game_running = $request->game_running;
            $request->validate([
                //player data
                'PositionX' => 'required',
                'PositionY' => 'required',
                'PositionZ' => 'required',
                'OrientationHeading' => 'required',
            ]);
            $user->PositionX = (double)$request->PositionX;
            $user->PositionY = (double)$request->PositionY;
            $user->PositionZ = (double)$request->PositionZ;
            $user->OrientationHeading = (float)$request->OrientationHeading;
            if($request->has("JobID")){
                $request->validate([
                    //player data
                    'JobID' => 'required',
                    'TrailersAttached' => 'required|integer',
                    'CurrentIngameTime' => 'required|date',

                    //truck damage data
                    'current_truck_cabin_damage' => 'required',
                    'current_truck_chassis_damage' => 'required',
                    'current_truck_engine_damage' => 'required',
                    'current_truck_transmission_damage' => 'required',
                    'current_truck_wheels_avg_damage' => 'required',

                    //trailer damage data
                    'current_trailer_avg_damage_chassis' => 'required',
                    'current_trailer_avg_damage_wheels' => 'required',

                    //navigation data
                    'CurrentSpeedKph' => 'required|integer',
                    'CurrentSpeedLimitKph' => 'required|integer',
                    'navigation_distance_remaining' => 'required',
                    'navigation_time_remaining' => 'required|date',
                ]);
                if(Job::find($request->JobID)){
                    $entry = new JobDataEntry;
                    $entry->job_id = (int)$request->JobID;
                    $entry->current_speed_kph = (int)$request->CurrentSpeedKph;
                    $entry->current_speed_limit_kph = (int)$request->CurrentSpeedLimitKph;
                    $entry->trailers_attached = (int)$request->TrailersAttached;
                    $entry->current_ingame_time = $request->CurrentIngameTime;

                    $entry->current_truck_cabin_damage = (float)$request->current_truck_cabin_damage;
                    $entry->current_truck_chassis_damage = (float)$request->current_truck_chassis_damage;
                    $entry->current_truck_engine_damage = (float)$request->current_truck_engine_damage;
                    $entry->current_truck_transmission_damage = (float)$request->current_truck_transmission_damage;
                    $entry->current_truck_wheels_avg_damage = (float)$request->current_truck_wheels_avg_damage;

                    $entry->current_trailer_avg_damage_chassis = (float)$request->current_trailer_avg_damage_chassis;
                    $entry->current_trailer_avg_damage_wheels = (float)$request->current_trailer_avg_damage_wheels;

                    $entry->navigation_distance_remaining = (float)$request->NavigationDistanceRemaining;
                    $entry->navigation_time_remaining = $request->navigation_time_remaining;

                    $entry->save();
                }
            }
        }else{
            $user->current_game_running = "None";
            $user->PositionX = null;
            $user->PositionY = null;
            $user->PositionZ = null;
            $user->OrientationHeading = null;
        }
        $user->save();
        abort(204);
    }
}

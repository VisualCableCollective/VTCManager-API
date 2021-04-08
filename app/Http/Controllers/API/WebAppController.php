<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\WebAppRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class WebAppController extends Controller
{
    public function check(Request $request){
        $response = $request->user()->with(['company'])->find($request->user()->id);
        $response["VCC_User"] = Socialite::driver('vcc')->stateless()->userFromToken($request->user()->latest_vcc_api_token);
        return $response;
    }

    public function dashboard(WebAppRequest $request){
        $response = [];

        $response["jobs_delivered_total"] = $request->user()->jobs()->where("status", "=", "delivered")->count();

        $last_7_days = \Carbon\Carbon::today()->subDays(7);
        $response["jobs_delivered_7_days"] = $request->user()->jobs()->where('created_at', '>=', $last_7_days)->where("status", "=", "delivered")->count();

        $response["latest_tour_status"] = $request->user()->jobs()->latest()->first()->status;

        $response["online_status"] = "Online";
        if(strtotime($request->user()->last_client_update) > strtotime("-1 minutes")){
            $response["online_status"] = "ClientOnline";
        }

        $response["latest_5_tours"] = $request->user()->jobs()->latest()->limit(5)->with([
            'truck_model',
            'truck_model.truck_manufacturer',
            'city_departure',
            'city_destination',
            'company_departure',
            'company_destination',
            'cargo',
            'cargo.game_item_translation' => function($q) use ($request){
                $q->where('language_code', '=', $request["language_code"])->orWhere('language_code', '=', "en");
            }])->get();

        return $response;
    }
}

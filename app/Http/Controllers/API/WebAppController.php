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

        // remove API tokens for VCC
        unset($response["latest_vcc_api_token"]);
        unset($response["VCC_User"]->token);
        unset($response["VCC_User"]->refreshToken);
        unset($response["VCC_User"]->accessTokenResponseBody);
        unset($response["VCC_User"]->approvedScopes);
        unset($response["VCC_User"]->expiresIn);

        // remove private user data
        unset($response["VCC_User"]->first_name);
        unset($response["VCC_User"]->last_name);

        unset($response["VCC_User"]->user["email"]);
        unset($response["VCC_User"]->user["first_name"]);
        unset($response["VCC_User"]->user["last_name"]);
        unset($response["VCC_User"]->user["created_at"]);
        unset($response["VCC_User"]->user["updated_at"]);
        unset($response["VCC_User"]->user["two_factor_confirmed_at"]);
        unset($response["VCC_User"]->user["email_verified_at"]);
        unset($response["VCC_User"]->user["email_verified_at"]);

        return $response;
    }

    public function dashboard(WebAppRequest $request){
        // set job stats values to default values to prevent errors, if no jobs exist yet.
        $response = [
            "jobs_delivered_total" => 0,
            "jobs_delivered_7_days" => 0,
            "latest_tour_status" => "",
            "latest_5_tours" => [],
        ];

        if ($request->user()->jobs()->exists()) {
            $response["jobs_delivered_total"] = $request->user()->jobs()->where("status", "=", "delivered")->count();

            $last_7_days = \Carbon\Carbon::today()->subDays(7);
            $response["jobs_delivered_7_days"] = $request->user()->jobs()->where('created_at', '>=', $last_7_days)->where("status", "=", "delivered")->count();

            $response["latest_tour_status"] = $request->user()->jobs()->latest()->first()->status;

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
        }

        $response["online_status"] = "Online";
        if(strtotime($request->user()->last_client_update) > strtotime("-1 minutes")){
            $response["online_status"] = "ClientOnline";
        }

        return $response;
    }
}

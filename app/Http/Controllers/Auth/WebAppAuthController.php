<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Http;

/**
 * Handles the WebApp user authentication
 */
class WebAppAuthController extends Controller
{
    /**
     * Redirects the WebApp user to the VCC to get the oauth token
     */
    public static function redirect(){
        return Socialite::driver('vcc')->redirect();
    }

    /**
     * Authenticates the user and redirects the user back to the WebApp with the API token
     */
    public static function callback(){
        //create user if necessary
        $VCC_User = Socialite::driver('vcc')->stateless()->user();
        $VTCM_User = User::where(['id' => $VCC_User->id])->first();
        if(!$VTCM_User){
            $VTCM_User = User::create([
                'id' => $VCC_User->id,
            ]);
        }

        //refresh the vcc API token
        $VTCM_User->latest_vcc_api_token = $VCC_User->accessTokenResponseBody["access_token"];
        $VTCM_User->save();

        //redirect back to the VTCManager WebApp
        return redirect(
            config("services.vtcm-web-app.redirect").
            "?token=".
            urlencode($VTCM_User->createToken("VTCManager WebApp")->plainTextToken));
    }

    public static function logout(Request $request) {
        $msg = ["message" => "OK"];

        $request->user()->currentAccessToken()->delete();

        return response($msg, 200);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

/**
 * Handles the desktop client user authentication
 */
class DesktopClientAuthController extends Controller
{
    /**
     * Redirects the desktop client user to the VCC to get the oauth token
     */
    public static function redirect(Request $request){
        return redirect(
            config("services.vcc-client-auth.redirect_to_url").
            "?client_id=".config("services.vcc-client-auth.client_id").
            "&redirect_uri=".urlencode(route("auth.vcc.desktop-client.callback")).
            "&response_type=code&scope=&state=".urlencode($request->session()->get('state')));
    }

    /**
     * Authenticates the user and returns a API token for the client
     */
    public static function callback(){
        $response = [];

        $VCC_User = Socialite::driver('vcc-client-auth')->stateless()->user();
        $VTCM_User = User::where(['id' => $VCC_User->id])->first();

        //if user don't exists in the db
        if(!$VTCM_User){
            $response["message"] = "FAILED";
            $response["error"] = "USER_NOT_REGISTERED";
            return $response;
        }

        //refresh the vcc API token
        $VTCM_User->latest_vcc_api_token = $VCC_User->accessTokenResponseBody["access_token"];
        $VTCM_User->save();

        $response["message"] = "OK";
        $response["token"] = $VTCM_User->createToken("VTCManager Client")->plainTextToken;
        return $response;
    }
}

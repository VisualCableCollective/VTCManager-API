<?php

namespace App\Http\Controllers;

use App\Models\LicenseKey;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function activate(Request $request){
        $request->validate([
            'licenseKey' => 'required|uuid',
        ]);

        $key = LicenseKey::findorfail($request["licenseKey"]);

        $response = [];

        if ($key->user_id == null) {
            $key->user_id = $request->user()->id;
            $key->save();

            $response["success"] = true;
        } else {
            $response["success"] = false;
            $response["error"] = "LICENSE_KEY_ALREADY_IN_USE";
        }

        return $response;
    }
}
